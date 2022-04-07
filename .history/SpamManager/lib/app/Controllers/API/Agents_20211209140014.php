<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
class Agents extends API
{
    public function get()
    {
        $action = trim($_GET['a']);
        if($action == 'GetAgentsList')
        {
            $query = trim($_GET['q']);
            $result = Admin::where(function($q) use($query)
            {
                $q->where('firstname', 'LIKE', '%'.$query.'%')
                ->orWhere('lastname', 'LIKE', '%'.$query.'%')
                ->orWhere('username', 'LIKE','%'.$query.'%')
                ->orWhere('email', 'LIKE', '%'.$query.'%');

            })
            ->where('disabled', '0')
            ->whereIn('roleid', array_merge(AdminGroupsConsts::AGENT, AdminGroupsConsts::ADMIN))
            ->whereNotIn('id', AdminGroupsConsts::AGENT_DISALLOWED)
            ->orderBy('firstname', 'ASC')
            ->get();
            return ['data' => $result, 'result' => 'success'];

        }
    }
    public function post()
    {
    }
}
