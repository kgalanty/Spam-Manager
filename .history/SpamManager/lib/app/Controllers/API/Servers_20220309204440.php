<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\Servers as ServersModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Service;

class Servers extends API
{
    public function get()
    {
       
        return ['servers' => ServersModel::orderBy('name', 'ASC')->get()];
    }
    public function post()
    {
        if($_GET['a'] == 'calculateRecipients')
        {
            $hostingStatuses = $this->input['hostingstatuses'];
            $serversID = [];
            foreach($this->input['servers'] as $server)
            {
                $serversID[] = $server['id'];
            }
            $recipients = Service::with(['client'])->whereIn('domainstatus', $hostingStatuses)->whereIn('server', $serversID)->get(['id', 'domainstatus', 'domain' ]);
            var_dump($recipients);die;

            return ['recipients' => $recipients ];
        }
    }
}
