<?php
namespace WHMCS\Module\Addon\SpamManager\app\Middlewares;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\StaffOnline as Staff;
use WHMCS\Module\Addon\SpamManager\app\Classes\DateTimeHelper;
//use WHMCS\Module\Addon\SpamManager\app\Classes\StatsRoleHelper; StatsRoleHelper::getPermID()
trait StaffOnline
{
    public function MarkOnline()
    {
       $staff = Staff::firstOrNew(['adminid' => $_SESSION['adminid']]);
       $staff->date = DateTimeHelper::NowUTC();
       $staff->save();
    }
}