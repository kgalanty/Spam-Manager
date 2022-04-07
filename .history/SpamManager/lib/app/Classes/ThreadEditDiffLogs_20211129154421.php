<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Module\Addon\ChatManager\app\Models\TagHistory;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Logs as LogsModel;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewThread as ReviewThreadModel;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewOrder;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads as ThreadsModel;

class ThreadEditDiffLogs
{
    const FIELDSMAP = [
        'name' => 'ClientName',
        'email' => 'ClientEmail',
        'domain' => 'Domain',
        'order' => 'Order',
        'notes' => 'Notes',
        'agent' => 'Agent',
        'invoice' => 'Invoice'
    ];
    public static function process($itemid, $doer, $update, $threaddata)
    {
 
        if (is_array($update)) {
            $log = [];
            foreach ($update as $field => $updatedItem) {
                $fieldController = 'WHMCS\\Module\\Addon\\ChatManager\\app\\Classes\\ThreadEditFields\\'.self::FIELDSMAP[$field];
                
                if(class_exists($fieldController))
                {
                    $log[] = $fieldController::handle($updatedItem, $threaddata);
                }   
            }
            if($_SESSION['adminid'] == 230)
            {
             // var_dump($log, $update, $threaddata);die;
            }
            $log = array_filter($log, function($v) { return $v != null; });
        
            if(count($log))
            {
                return implode(', ', $log);
            }
            return;
        }
    }
}
