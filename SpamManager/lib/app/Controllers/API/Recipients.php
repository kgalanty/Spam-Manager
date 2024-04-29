<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\Servers as ServersModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Service;

class Recipients extends API
{
    public function get()
    {
       
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
            foreach($this->input['products'] as $product)
            {
                $productID[] = $product['id'];
            }
            
            foreach($this->input['customServers'] as $server)
            {
                $serversID[] = (int) $server;
            }

            $recipients = Service::whereIn('domainstatus', $hostingStatuses);
            
            if(count($serversID))
            {
                $recipients->whereIn('server', $serversID);
            }
            if(count($productID))
            {
                $recipients->whereIn('packageid', $productID);
            }
            //['id', 'domainstatus', 'domain' ]
            return ['recipients' => $recipients->count() ];
        }
    }
}
