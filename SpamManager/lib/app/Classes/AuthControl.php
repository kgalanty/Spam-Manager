<?php

namespace WHMCS\Module\Addon\SpamManager\app\Classes;

use WHMCS\Module\Addon\SpamManager\app\Consts\AdminGroupsConsts;
use WHMCS\Module\Addon\SpamManager\app\Models\Admin;

class AuthControl
{
    public static function isAdmin()
    {
        $role = self::getAdminRole();
        if (in_array($role, AdminGroupsConsts::ADMIN)) {
            return true;
        }
        return false;
    }
    public static function isAgent()
    {
        $role = self::getAdminRole();
        if (in_array($role, AdminGroupsConsts::AGENT)) {
            return true;
        }
        return false;
    }
    public static function getAdminRole()
    {
        $role = Admin::where('id', $_SESSION['adminid'])->value('roleid');
        return $role;
    }
}
