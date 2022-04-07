<?php
namespace WHMCS\Module\Addon\ChatManager\app\Middlewares;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
//use WHMCS\Module\Addon\ChatManager\app\Classes\StatsRoleHelper; StatsRoleHelper::getPermID()
trait AuthMid
{
    public function checkPermission()
    {
        if($_SESSION['adminpw'] && !in_array($_SESSION['adminid'], AdminGroupsConsts::AGENT_DISALLOWED) && (AuthControl::isAdmin() || AuthControl::isAgent()))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}