<?php
namespace WHMCS\Module\Addon\SpamManager\app\Classes;

use WHMCS\Module\Addon\SpamManager\app\Models\Client;

class FindClientHelper
{
    public static function execute($user)
    {
        $currentCustomer = Client::where('email', $user->email)->first();
        return $currentCustomer;
    }
}