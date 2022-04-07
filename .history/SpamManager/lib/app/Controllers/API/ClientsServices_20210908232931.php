<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Service;

class ClientsServices extends API
{
    public function get()
    {
        $orderBy = $_GET['order'] ? $_GET['order'] : 'id';
        $ascdesc = $_GET['dir'] ? $_GET['dir'] : 'desc';
        $result = Service::userId($_GET['cid'])->with(['product'])
        ->orderBy($orderBy, $ascdesc)
            ->get();
        return ['data' => $result];
    }
    public function post()
    {
    }
}
