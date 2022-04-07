<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\Cron;

use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Module\Addon\ChatManager\app\Models\Client;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Classes\TagsLog;
use WHMCS\Module\Addon\ChatManager\app\Models\Tags;
use WHMCS\Module\Addon\ChatManager\app\Classes\TagsHelper;
use WHMCS\Module\Addon\ChatManager\app\DBTables\DBTables;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewOrder;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;

class OrderCron
{
    public $timezone;
    public function __construct($timezone)
    {
        if ($timezone) {
            $this->timezone = $timezone;
        }
    }
    private function getThreads()
    {
        $threads = Threads::with(['tags', 'customer'])
            ->whereNull('orderid')
            ->where('created_at', '>', date('Y-m-d h:i:s', strtotime("-45 days")))
            ->get();
        return $threads;
    }
    private function processThreads($threads = [])
    {
        foreach ($threads as $thread) {
            $this->singleThreadHandler($thread);
        }
    }
    private function singleThreadHandler($thread)
    {
        $potentiallyCompletedOrder = DB::table('chat_completedorders as co')
            ->join('tblorders as o', 'o.id', '=', 'co.ordernumber')
            ->join('tblinvoices as i', 'i.id', '=', 'o.invoiceid')
            ->join('tblinvoiceitems as ii', function ($join) {
                $join->on('ii.invoiceid', '=', 'i.id');
                $join->where('ii.type', '=', 'Hosting');
            })
            ->join('tblhosting as h', 'h.id', '=', 'ii.relid')
            // ->leftJoin('chat_threads as t', 't.orderid', '=', 'o.id')
            // ->leftJoin('chat_tags as tg', 'tg.t_id', '=', 't.id')
            ->where('i.status', 'Paid')
            ->where('co.lcchatid', $thread->chatid)
            //->orderByRaw("FIELD(tag, 'wcb') DESC")
            ->first(['h.domain', 'co.ordernumber as orderid']);
        if ($potentiallyCompletedOrder && Threads::orderOf($potentiallyCompletedOrder->orderid)->count() == 0) {
            $thread->orderid = $potentiallyCompletedOrder->orderid;
            $thread->domain = $potentiallyCompletedOrder->domain;

            Threads::where('id', $thread->id)
                ->update(['domain' => $potentiallyCompletedOrder->domain, 'orderid' => $potentiallyCompletedOrder->orderid]);
            if (Tags::thread($thread->id)->tag('wcb')->count() > 0) {
                TagsHelper::addTag('convertedsale', $thread->id, true, AdminGroupsConsts::CRON_ADMIN);
                TagsLog::AddedByCron($thread->id, 'convertedsale');
            }

            Logs::MatchedOrderByCron($thread);

            return;
        }
        $email = $thread->email ? $thread->email : $thread->customer[0]->email;
        //  if($email != 'domain@pushpatechnologies.com') return;
        if ($email) {
            //check cases when in one field are more than one email separated by comma
            if (strpos($email, ',') !== false) {
                $emails = explode(',', $email);
                foreach ($emails as $emm) {
                    $client = Client::where('email', trim($emm))->first(['id']);

                    if ($client) {
                        //Threads::where('id', $thread->id)->update()
                        $this->handleClient($thread, $client);
                        return;
                    }
                }
            } else {
                $client = Client::where('email', trim($email))->first(['id']);

                if ($client) {
                    //Threads::where('id', $thread->id)->update()
                    $this->handleClient($thread, $client);
                    return;
                }
            }
        }
        //matching by IP
        $client = Client::where('ip', $thread->customer[0]->ip)
            ->where('status', 'Active')
            ->whereBetween('created_at', [date('Y-m-d h:i:s', strtotime($thread->date . " -1 days")), date('Y-m-d h:i:s')])
            ->first();
        if ($client) {
            $this->handleClient($thread, $client);
            return;
        }
    }
    public function run()
    {
        $threads = $this->getThreads();
        if ($threads) {
            $this->processThreads($threads);
        }
        $this->GetThreadsWithPendingOrderAsCurrentlySet();
        $this->FindOrdersWithNoClientData();
       // $this->FindConvertedSalesWithPossibleInvoice();
        //$unpaidThreads = $this->getUnpaidThreads();
    }
    public function FindConvertedSalesWithPossibleInvoice()
    {
        // $q1 = DB::table(DBTables::Threads.' as t')
        // ->join('tblhosting as h', 'h.domain', '=', 't.domain')
        // ->whereNull('t.orderid')->whereNull('t.invoiceid')
        // ->whereExists(function($query)
        // {
        //     $query->select(DB::raw(1))->from(DBTables::Tags.' as tags')->whereColumn('tags.t_id', '=', 't.id')->whereRaw('tags.tag = "convertedsale"');
        // })
        // ->whereExists(function($query)
        // {
        //     $query->select(DB::raw(1))->from('tblclients as c')->whereColumn('c.email', '=', 't.email');
        // })
        // ->where('h.domainstatus', 'Active')
        // ->where('t.date', '>', date('Y-m-d\T00:00:00.000000\Z', strtotime("-45 days")))
        // ->get(['t.id', 'h.id as hid', 'h.userid']);
        
        // foreach($q1 as $thread)
        // {
        //     $q2 = DB::table('tblinvoiceitems as invitem')
        //     ->join('tblinvoices as inv', 'inv.id','=','invitem.invoiceid')
        //     ->where('invitem.type', 'Hosting')
        //     ->where('invitem.relid', $thread->hid)
        //     ->where('invitem.userid', $thread->userid)
        //     ->orderBy('inv.id', 'DESC')
        //     ->first(['invitem.invoiceid', 'inv.status']);
        //     echo('<pre>');var_dump($q2);die;
        //     if($q2->status == 'Paid')
        //     {
        //         ReviewOrder::where('threadid', $thread->id)->where('invoice', '1')->where('orderid', $q2->invoiceid)

        //        // Threads::where('id', $thread->id)->update(['invoiceid' => $q2->invoiceid]);

        //     }
        // }
       
    }
    public function FindOrdersWithNoClientData()
    {
        $threads = Threads::with(['order.client' => function ($query) {
            $query->select('id', 'firstname', 'lastname', 'companyname', 'email');
        }])->has('order')
            ->where('orderid', '!=', '')
            ->where('name', '')
            ->where('email', '')
            ->get();
        foreach ($threads as $t) {

            Threads::where('id', $t->id)->update(
                [
                    'name' => $t->order->client->firstname . ' ' . $t->order->client->lastname,
                    'email' => $t->order->client->email
                ]
            );
            Logs::SetClientDataWhenEmpty($t->id);
        }
    }
    public function GetThreadsWithPendingOrderAsCurrentlySet()
    {
        $threads = DB::table(DBTables::Threads . ' as t')
            ->join(DBTables::ReviewOrders . ' as ro', 'ro.threadid', '=', 't.id')
            ->whereColumn('t.orderid', 'ro.orderid')
            ->get(['ro.*']);
        if ($threads) {
            foreach ($threads as $t) {
                ReviewOrder::where('threadid', $t->threadid)->where('orderid', $t->orderid)->delete();
                Logs::duplicatedPendingOrder($t->threadid, $t->orderid);
            }
        }
    }
    private function handleClient($thread, $client)
    {
        $order = DB::table('tblorders as o')
            ->join('tblinvoices as i', 'i.id', '=', 'o.invoiceid')
            ->join('tblinvoiceitems as ii', function ($join) {
                $join->on('ii.invoiceid', '=', 'i.id');
                $join->where('ii.type', '=', 'Hosting');
            })
            ->join('tblhosting as h', 'h.id', '=', 'ii.relid')
            ->leftJoin(DBTables::Threads . ' as t', 't.orderid', '=', 'o.id')
            ->where('o.userid', $client->id)
            ->whereBetween('o.date', [date('Y-m-d h:i:s', strtotime($thread->date . " -1 days")), date('Y-m-d h:i:s')])
            ->where('o.status', 'Active')
            ->where('i.status', 'Paid')
            ->whereNull('t.orderid')
            ->first(['h.domain', 'o.id as orderid']);
        // var_dump($order);die;
        if ($order) {
            if ($order->domain) {
                Threads::where('id', $thread->id)->update(['domain' => $order->domain, 'orderid' => $order->orderid]);

                $orderchangesPending = ReviewOrder::where('threadid', $thread->id)->where('orderid', $thread->orderid)->get();
                if ($orderchangesPending) {
                    ReviewOrder::where('threadid', $thread->id)->where('orderid', $thread->orderid)->delete();
                    if($thread->orderid)
                    {
                        Logs::duplicatedPendingOrder($thread->id, $thread->orderid);
                    }
                    elseif($thread->invoiceid)
                    {
                        
                    }
                }

                Logs::AddOrderInCron($order->orderid, $thread->id);
                if (Tags::thread($thread->id)->tag('wcb')->count() > 0) {
                    TagsHelper::addTag('convertedsale', $thread->id, true, AdminGroupsConsts::CRON_ADMIN);
                    TagsLog::AddedByCron($thread->id, 'convertedsale');
                }
            }
        }
    }
    // private function getUnpaidThreads()
    // {

    // }
}
