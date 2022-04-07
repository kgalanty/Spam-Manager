<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads as ThreadsModel;
use WHMCS\Module\Addon\ChatManager\app\Classes\LiveChatHelper;

class LiveChat extends API
{
    public function get()
    {
        if (AuthControl::isAdmin()) {
            $threadid = $_GET['tid'];
            $livechat = new LiveChatHelper();
            $result = $livechat->findChatByID($threadid);
            if ($result['result'] == 'success') {
                if($result['found_chats'] == 0)
                {
                    return ['result' => 'error', 'msg' => 'Cannot found the chat with this thread id.'];
                }
                $livechat->runParseStore();
                $thread = ThreadsModel::with(['followup', 'tags', 'customer', 'agentdata'])->where('threadid', $threadid)->first();
                Logs::CreatedByID($thread->id, $_SESSION['adminid']);
                return ['result' => 'success', 'data' => $thread];
            }
            return ['result' => 'error', 'msg' => $result['result']];
        }
        return ['result' => 'error', 'msg' => 'No permission to complete this operation'];
        //  echo('<pre>');var_dump($result); die;
    }
    public function post()
    {
        return 'success';
        return 'Nothing has been changed';
    }
}
