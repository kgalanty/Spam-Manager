<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\Servers as ServersModel;

class Servers extends API
{
    public function get()
    {
        if($_GET['a'] == 'calculateRecipients')
        {
            $hostingStatuses = $_GET['hostingstatuses'];
            $servers = $_GET['servers'];
            $recipients = Service::with(['client'])->whereIn('domainstatus', $hostingStatuses)->whereIn('server', $servers)->get();
            return ['recipients' => $recipients ];
        }
        return ['servers' => ServersModel::orderBy('name', 'ASC')->get()];
    }
    public function post()
    {
       
    }
}
