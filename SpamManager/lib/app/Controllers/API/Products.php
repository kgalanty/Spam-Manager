<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\Product as ProductsModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Service;

class Products extends API
{
    public function get()
    {
       // echo('<pre>');var_dump(ProductsModel::with(['group'])->orderBy('name', 'ASC')->get());die;
        return ['products' => ProductsModel::with(['group'])->orderBy('id', 'ASC')->get()];
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
            $recipients = Service::whereIn('domainstatus', $hostingStatuses)->whereIn('server', $serversID)->count();
            //['id', 'domainstatus', 'domain' ]
            return ['recipients' => $recipients ];
        }
    }
}
