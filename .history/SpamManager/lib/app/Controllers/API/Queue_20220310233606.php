<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;


class Queue extends API
{
    public function get()
    {
        return ['queue' => EmailQueue::with(['admin', 'service'])->orderBy('id', 'DESC')->take(100)];
    }
    public function post()
    {
    }
}
