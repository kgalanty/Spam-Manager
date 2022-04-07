<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\Servers as ServersModel;

class Servers extends API
{
    public function get()
    {
        return ['servers' => ServersModel::orderBy('name', 'ASC')->get()];
    }
    public function post()
    {
       
    }
}
