<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads as ThreadsModel;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewOrder;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewDuplicatedOrder;

class DuplicateReview extends API
{
    public function get()
    {
    }
    public function post()
    {
        if(!AuthControl::isAdmin()) return;
        $threadid = $this->input['threadid'];
        $action = $this->input['a'];
        $order = $this->input['orderid'];
        if ($action == 'SingleChat') {

            if ($threadid && is_int($threadid)) {
                ReviewDuplicatedOrder::insert([
                    'threadid' => (int)$threadid,
                    'doer' => $_SESSION['adminid'],
                    'created_at' => gmdate('Y-m-d H:i:s')
                ]);
                Logs::AcceptDuplicateOrder($threadid, $_SESSION['adminid']);
                return 'success';
            }
        } elseif ($action == 'AllChatsWithGivenOrder') {
            if ($order && is_int($order)) {
                $threads = ThreadsModel::where('orderid', $order)->get();
                foreach ($threads as $t) {
                    ReviewDuplicatedOrder::insert([
                        'threadid' => (int)$t->id,
                        'doer' => $_SESSION['adminid'],
                        'created_at' => gmdate('Y-m-d H:i:s')
                    ]);
                    Logs::AcceptDuplicateOrder((int)$t->id, $_SESSION['adminid']);
                }

                return 'success';
            }
        }
    }
}
