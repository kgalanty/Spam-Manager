<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\SpamManager\app\Classes\Logs;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;
use WHMCS\Module\Addon\SpamManager\app\Models\Service;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailTemplates;

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
