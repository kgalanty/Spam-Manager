<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;


class Queue extends API
{
    public function get()
    {
        var_dump($_GET);die;
        return ['total' => EmailQueue::count(), 'queue' => EmailQueue::with(['admin', 'service', 'service.server'])->orderBy('id', 'DESC')->take(100)->get()];
    }
    public function post()
    {
    }
}
