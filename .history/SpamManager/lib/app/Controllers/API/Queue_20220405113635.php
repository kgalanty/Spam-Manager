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
        return ['total' => EmailQueue::count(), 
        'queue' => EmailQueue::with(['admin', 'service', 'service.server'])->orderBy('id', 'DESC')->skip($page)->take($_GET['perpage'])->get()];
    }
    public function post()
    {
    }
}
