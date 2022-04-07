<?php
namespace WHMCS\Module\Addon\ChatManager\app\Middlewares;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\StaffOnline as Staff;
use WHMCS\Module\Addon\ChatManager\app\Classes\DateTimeHelper;
//use WHMCS\Module\Addon\ChatManager\app\Classes\StatsRoleHelper; StatsRoleHelper::getPermID()
trait StaffOnline
{
    public function MarkOnline()
    {
       $staff = Staff::firstOrNew(['adminid' => $_SESSION['adminid']]);
       $staff->date = DateTimeHelper::NowUTC();
       $staff->save();
    }
}