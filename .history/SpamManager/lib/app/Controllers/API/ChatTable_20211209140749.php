<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;

class ChatTable extends API
{
    public function get()
    {
        if ($_GET['pending'] == 1) {
            if (AuthControl::isAdmin()) {
                $dateTo = $_GET['dateto'] != '' ? $_GET['dateto'] : gmdate('Y-m-d\TH:i:s.000000\Z');
                $result = Threads::with(['tags', 'customer', 'pendingReviews', 'followup', 'revieworder', 'agentdata', 'invoice'])
                    ->withCount(['revieworder', 'sameorder'])
                    ->has('pendingReviews')
                    ->orWhereHas('tags', function ($query) {
                        $query->where('approved', '0')->orWhere('proposed_deletion', '1');
                    })
                    ->orWhere(function ($q) {
                        $q->has('sameorder', '>', '1')->whereDoesntHave('reviewduplicatedorders');
                    })
                    ->orHas('revieworder')
                    ->orderBy('id', 'DESC')->orderBy('orderid');
                if ($_GET['datefrom']) {
                    $result->whereBetween('date', [$_GET['datefrom'], $dateTo]);
                }
                $total = $result->count();
                $result = $result->get();
                return ['data' => $result, 'total' => $total];
            }
            return ['data' => [], 'total' => 0];
        }
        $perpage = $_GET['perpage'] ? $_GET['perpage'] : 10;
        $page = $_GET['page'] == 1 ? 0 : ($_GET['page'] - 1) * $_GET['perpage'];
        $dateTo = $_GET['dateto'] != '' ? $_GET['dateto'] : gmdate('Y-m-d\TH:i:s.000000\Z');
        //$myemail = Admin::where('id', $_SESSION['adminid'])->value('email');
        $tags = $_GET['tags'];
        $extags = $_GET['extags'];
        $operator = $_GET['operator'] != '' ? intval(trim($_GET['operator'])) : '';
        //$myemail = 'emiliya.sergieva@tmdhosting.com';

        $result = Threads::with(['followup', 'order.invoice', 'agentdata', 'invoice'])
            ->withCount(['pendingReviews', 'revieworder'])
            ->with(['tags', 'customer']);
        if ($_GET['datefrom']) {
            $result->whereBetween('date', [$_GET['datefrom'], $dateTo]);
        }
        if ($operator) {
            $result->whereHas('agentdata', function ($query) use ($operator) {
                $query->where('id', $operator);
            });
        }
        if ($tags) {
            $tagsExploded = explode(',', $tags);
            foreach ($tagsExploded as $tag) {
                $result->whereHas('tags', function ($query) use ($tag) {
                    //$query->whereIn('tag', explode(',', $tags));

                    $query->where('tag', $tag)->where('approved', 1);
                });
            }
        }
        if ($extags) {
            $tagsExploded = explode(',', $extags);
            foreach ($tagsExploded as $tag) {
                $result->whereDoesntHave('tags', function ($query) use ($tag) {
                    //$query->whereIn('tag', explode(',', $tags));

                    $query->where('tag', $tag)->where('approved', 1);
                });
            }
        }
        if ($_GET['q']) {
            $q = trim($_GET['q']);
            $result->where(function ($query) use ($q) {
                $query->whereHas('customer', function ($query2) use ($q) {
                    $query2->where('email', 'LIKE', '%' . $q . '%')
                        ->orWhere('geolocation', 'LIKE', '%country_code":"' . $q . '%');
                })
                    ->orWhere('threadid', 'LIKE', '%' . $q . '%')
                    ->orWhere('email', 'LIKE', '%' . $q . '%')
                    ->orWhere('domain', 'LIKE', '%' . $q . '%')
                    ->orWhere('chatid', 'LIKE', '%' . $q . '%')
                    ->orWhere('orderid', 'LIKE', '%' . $q . '%');
            });
        }
        if (AuthControl::isAgent()) {

            if (isset(AdminGroupsConsts::TAGSAGENTMAP[$_SESSION['adminid']])) {
                $result->where(function ($query) {
                    $query->whereHas('tags', function ($query2) {
                        $query2->where('tag', AdminGroupsConsts::TAGSAGENTMAP[$_SESSION['adminid']]);
                    });
                    $query->orWhere('agent',  $_SESSION['adminid']);
                });
            } else {
                $result->where('agent',  $_SESSION['adminid']);
            }
        }
        //if (AuthControl::isAdmin()) {

        //dont show threads with pending reviews because they are moved to upper table
        //$result->has('pendingReviews', '0');
        //}
        $total = $result->count();
        $result = $result->skip($page)->take($perpage)->orderBy('date', 'DESC')
            ->get();

        return ['data' => $result, 'total' => $total];
    }
    public function post()
    {
        $threadid = $this->input['tid'];
        $action = $this->input['a'];

        if ($action == 'DelThread' && AuthControl::isAdmin()) {
            if ($threadid) {
                $t = Threads::findOrFail($threadid);
                $t->tags()->delete();
                $t->tagshistory()->delete();
                $t->reviewthread()->delete();
                $t->revieworder()->delete();
                $t->logs()->delete();
                $t->delete();
                return 'success';
                //Tags::where('t_id', )
            }
            return 'Cannot find given Chat ID';
        }
        return 'Cannot found method or you have no permissions to run it';
    }
}
