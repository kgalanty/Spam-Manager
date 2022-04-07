<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Module\Addon\ChatManager\app\Models\Logs as LogsModel;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;

class SystemLogs extends API
{
    public function get()
    {
        if (AuthControl::isAdmin()) {
            $perpage = $_GET['perpage'] ? $_GET['perpage'] : 25;
            $page = (!$_GET['page'] || $_GET['page'] == 1) ? 0 : ((int)$_GET['page'] - 1) * $perpage;
           // $dateTo = $_GET['dateto'] != '' ? $_GET['dateto'] : gmdate('Y-m-d\TH:i:s.000000\Z');
            $operator = $_GET['operator'] != '' ? intval(trim($_GET['operator'])) : '';

            $logs = LogsModel::with(['doer', 'relateditem'])
                ;
                if ($_GET['datefrom']) {
                    $logs->where('created_at', '>=', $_GET['datefrom']);
                }
                if($_GET['dateto'])
                {
                    $logs->where('created_at', '<=', $_GET['dateto']);
                }
                if ($operator) {
                    $logs->where('doer', $operator);
                }
                if($_GET['q'] != '')
                {
                    $logs->where(function($q4)
                    {
                        $q4->where('desc', 'LIKE', '%'.trim($_GET['q']).'%')
                        ->orWhere(function($q2)
                        {
                            $q2->whereHas('thread',function($q3)
                            {
                                $q3->where('threadid', 'LIKE', '%'.trim($_GET['q']).'%')
                                ->orWhere('chatid', 'LIKE', '%'.trim($_GET['q']).'%');
                            });
                        });
                    });
                 
                    // ->orWhereHas('relateditem', function($q2)
                    // {
                    //     $q2->where('threadid', 'LIKE', '%'.trim($_GET['q']).'%')
                    //     ->orWhere('chatid', 'LIKE', '%'.trim($_GET['q']).'%');
                    // });
                }
            $total = $logs->count();
            $result = $logs->skip($page)
                ->take($perpage)
                ->orderBy('id', 'DESC')
                ->get();
            return ['result' => 'success', 'data' => $result, 'total' => $total];
        }
    }
    public function post()
    {
    }
}
