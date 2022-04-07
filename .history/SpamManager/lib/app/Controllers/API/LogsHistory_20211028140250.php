<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Tags as Tag;
use WHMCS\Module\Addon\ChatManager\app\Models\TagHistory;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\TagsLog;
use WHMCS\Module\Addon\ChatManager\app\Models\Logs as LogHistoryModel;
class LogsHistory extends API
{
    public function get()
    {
        // if ($_GET['a'] == 'GetTagsLog') {
        //     if (AuthControl::isAdmin()) {
        //         $t_id = (int)trim($_GET['threadid']);
        //         $results = TagHistory::with('doer')->where('thread_id', $t_id)->orderBy('id', 'DESC')->get();
        //         return ['data' => $results];
        //     }
        //     return 'No permission';
        // }
        $itemid = (int) $_GET['itemid'];
        //$itemtype = trim($_GET['itemtype']);
        $data = LogHistoryModel::with(['doer'])
        ->where('itemid', $itemid)->where('itemclass', 'Thread')
        ->orderBy('chat_logs.created_at', 'DESC')
        ->get();
       // ->get(['itemclass', 'desc', 'created_at', 'doer']);
            return ['data' => $data];
    }
    public function post()
    {

    }
}
