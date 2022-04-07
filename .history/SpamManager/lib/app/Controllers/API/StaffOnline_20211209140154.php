<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\StaffOnline as SOModel;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
use WHMCS\Module\Addon\ChatManager\app\Classes\DateTimeHelper;
use WHMCS\Module\Addon\ChatManager\app\Consts\moduleVersion;
class StaffOnline extends API
{
    public function get()
    {
        $data = SOModel::with('agent')
        ->where('date', '>', DateTimeHelper::subDate('UTC', new \DateInterval('PT20M'))
        ->format('Y-m-d H:i:s'))
        ->get();
            return ['data' => $data, 'result' => 'success', 'appver' => moduleVersion::VERSION ];

    }
    public function post()
    {
    }
}
