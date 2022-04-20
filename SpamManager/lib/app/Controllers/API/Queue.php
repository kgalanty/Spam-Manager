<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;


class Queue extends API
{
    public function get()
    {
        $page = $_GET['page'] == 1 ? 0 : ($_GET['page'] - 1) * $_GET['perpage'];
        if ($_GET['listid']) {
            return [
                'total' => EmailQueue::filterList((int)$_GET['listid'])->count(),
                'queue' => EmailQueue::with(['service', 'service.product', 'service.server'])->filterList((int)$_GET['listid'])->orderBy('id', 'DESC')->skip($page)->take($_GET['perpage'])->get()
            ];
        }
    }
    public function post()
    {
    }
}
