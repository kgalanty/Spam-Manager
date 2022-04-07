<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads as ThreadsModel;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewOrder;

class Threads extends API
{
    public function get()
    {
        //     $query= $_GET['q'];

        //     if($query)
        //     {
        //         $result = Client::where('firstname', 'LIKE', '%'.$query.'%')
        //             ->orWhere('lastname', 'LIKE', '%'.$query.'%')
        //             ->orWhere('email', 'LIKE', '%'.$query.'%')
        //             ->take(10)
        //             ->get();
        //     }
        //     else
        //     {
        //         $result = Client::take(10)
        //         ->get();
        //     }

        //    // $total = $result->count();
        //     return ['data' => $result];
        //     $results = DB::table('tbladmins as a')
        //    ->orderBy('a.firstname', 'ASC')
        //    ->get(['a.id', 'a.firstname', 'a.lastname']);
        //     return ['data' => $results];
    }
    public function post()
    {
        $name = $this->input['name'];
        $email = trim($this->input['email']);
        $domain = trim($this->input['domain']);
        $order = trim($this->input['order']);
        $itemid = (int)$this->input['id'];
        $notes = trim($this->input['notes']);
        $customoffer = trim($this->input['customoffer']);
        $agent = trim($this->input['agent']);
        $invoiceid = (int)trim($this->input['invoiceid']);
        $takeinvoice = (bool)$this->input['takeinvoice'];
        if ($itemid) {
            $thread = ThreadsModel::with('customer')->where('id', $itemid);
        } else {
            return ['result' => 'error', 'msg' => 'No thread id was given.'];
        }
        $threaddata = $thread->first();
        $update = [];
        if (($name != $threaddata->customer->name && $name != $threaddata->name) || (strlen($name) == 0 && ($name != $threaddata->customer->name || $name != $threaddata->name)  )) {
            $update['name'] = $name;
        }
        if ($email != $threaddata->customer->email || ($threaddata->email && $threaddata->email != $email)) {
            $update['email'] = $email;
        }
        if ($domain != $threaddata->domain) {
        $update['domain'] = $domain;
         }
        if (!$takeinvoice && $order != $threaddata->orderid) {
            if (AuthControl::isAgent()) {
                if (!$order) return ['result' => 'error', 'msg' => 'You cannot remove order id', 'orderid' => $threaddata->orderid];
                if (ReviewOrder::where('threadid', $itemid)->where('orderid', $order)->count() == 0) {
                    ReviewOrder::insert([
                        'orderid' => $order,
                        'threadid' => $itemid,
                        'invoice' => 0,
                        'sender' => $_SESSION['adminid'],
                        'created_at' => gmdate('Y-m-d H:i:s')
                    ]);
                    Logs::submitOrderReview($order, $_SESSION['adminid'], $itemid);
                }
            } elseif (AuthControl::isAdmin()) {
                $update['orderid'] = ($order && is_numeric($order)) ? (int)$order : null;
                $update['invoiceid'] = $update['orderid'] ? null : $threaddata->orderid;
            }
        } elseif (strlen($order) == 0) {
            $update['orderid'] = null;
        }
        if ($takeinvoice && $invoiceid != $threaddata->invoiceid) {
           
            if (AuthControl::isAgent()) {
                if (!$invoiceid) return ['result' => 'error', 'msg' => 'You cannot remove invoice id', 'orderid' => $threaddata->invoiceid];
                if (ReviewOrder::where('threadid', $itemid)->where('invoice', '1')->where('orderid', $order)->count() == 0) {
                    ReviewOrder::insert([
                        'orderid' => $invoiceid,
                        'invoice' => 1,
                        'threadid' => $itemid,
                        'sender' => $_SESSION['adminid'],
                        'created_at' => gmdate('Y-m-d H:i:s')
                    ]);
                    Logs::submitInvoiceReview($invoiceid, $_SESSION['adminid'], $itemid);
                }
            } elseif (AuthControl::isAdmin()) {
                $update['invoiceid'] = ($invoiceid && is_numeric($invoiceid)) ? (int)$invoiceid : null;
                $update['orderid'] = $update['invoiceid'] ? null : $threaddata->orderid;
            }
        } elseif (strlen($order) == 0) {
            $update['orderid'] = null;
        }
        //if ($notes) {
        $update['notes'] = $notes;
        // }
        if ($customoffer) {
            $update['customoffer'] = $customoffer;
        }
        if ($agent && AuthControl::isAdmin()) {
            $update['agent'] = $agent;
        }
    
        Logs::updateThread($itemid, $_SESSION['adminid'], $this->input, $threaddata);

        // order: this.selectedOrder,
        // notes: this.notes,
        // customoffer: this.customoffer,
        if (count($update) > 0) {
            ThreadsModel::where('id', $itemid)->update($update);

            return ['result' => 'success'];
        }
        return ['result' => 'error', 'msg' => 'Nothing has been changed'];
    }
}
