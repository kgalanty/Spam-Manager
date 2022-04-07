<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Module\Addon\ChatManager\app\Classes\DateTimeHelper;
use WHMCS\Database\Capsule as DB;

class FindOrderHelper
{
    public static function findDomain($currentCustomer, $referenceDate)
    {
        $orderNumber = DB::table('tblorders as o')
        ->where('o.userid', $currentCustomer->id)
        ->where('o.date', '>', DateTimeHelper::setFormat($referenceDate, 'Y-m-d H:i:s'))
        ->count();
        if($orderNumber == 1)
        {
            $orderChosen = DB::table('tblorders')
                ->where('userid', $currentCustomer->id)
                ->where('date', '>', DateTimeHelper::setFormat($referenceDate, 'Y-m-d H:i:s'))
                ->where('status', 'Active')
                ->first();
                return $orderChosen->id;

        }
        elseif($orderNumber > 1)
        {
            $orderChosen = DB::table('tblorders')
                ->where('userid', $currentCustomer->id)
                ->where('date', '>', DateTimeHelper::setFormat($referenceDate, 'Y-m-d H:i:s'))
                ->where('status', 'Active')
                ->orderBy('id', 'ASC')
                ->first();
                return $orderChosen->id;
        }
    }
    public static function findDomainByOrder(int $orderid)
    {
        $assignedInvoice = DB::table('tblorders')->where('id', $orderid)->value('invoiceid');
        if($assignedInvoice)
        {
           return DB::table('tblinvoiceitems i')->join('tblhosting h', 'h.id', '=', 'i.relid')
            ->where('i.type', 'Hosting')->where('i.invoiceid', $assignedInvoice)
            ->value('h.domain');
        }
    }
    public static function execute($currentCustomer, $referenceDate)
    {
        $orderNumber = DB::table('tblorders as o')
        ->where('o.userid', $currentCustomer->id)
        ->where('o.date', '>', DateTimeHelper::setFormat($referenceDate, 'Y-m-d H:i:s'))
        ->count();
        if($orderNumber == 1)
        {
            $orderChosen = DB::table('tblorders')
                ->where('userid', $currentCustomer->id)
                ->where('date', '>', DateTimeHelper::setFormat($referenceDate, 'Y-m-d H:i:s'))
                ->where('status', 'Active')
                ->first();
                return $orderChosen->id;

        }
        elseif($orderNumber > 1)
        {
            $orderChosen = DB::table('tblorders')
                ->where('userid', $currentCustomer->id)
                ->where('date', '>', DateTimeHelper::setFormat($referenceDate, 'Y-m-d H:i:s'))
                ->where('status', 'Active')
                ->orderBy('id', 'ASC')
                ->first();
                return $orderChosen->id;
        }
    }
}