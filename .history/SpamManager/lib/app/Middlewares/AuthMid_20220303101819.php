<?php
namespace WHMCS\Module\Addon\SpamManager\app\Middlewares;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\SpamManager\app\Consts\AdminGroupsConsts;
//use WHMCS\Module\Addon\SpamManager\app\Classes\StatsRoleHelper; StatsRoleHelper::getPermID()
trait AuthMid
{
    public function checkPermission()
    {
        if($_SESSION['adminpw'] )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}