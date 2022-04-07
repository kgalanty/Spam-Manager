<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\SpamManager\app\Classes\Logs;
use WHMCS\Module\Addon\SpamManager\app\Models\Threads as ThreadsModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Servers;

class Templates extends API
{
    public function get()
    {
        return ['servers' => Servers::all()];
    }
    public function post()
    {
       
    }
}
