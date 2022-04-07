<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Models\FollowUp as FollowupModel;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
class FollowUp extends API
{
    public function get()
    {
        
            
    }
    public function post()
    {
        $threadid = intval($this->input['threadid']);
        FollowupModel::create(
            [
                'threadid' => $threadid,
                'followupdate' => gmdate('Y-m-d H:i:s'),
                'doer' => $_SESSION['adminid']
            ]
            );
        Logs::FollowUp($threadid, $_SESSION['adminid']);    
        return ['result' => 'success'];
    }
}
