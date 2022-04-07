<?php
namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Module\Addon\ChatManager\app\Models\Client;

class FindClientHelper
{
    public static function execute($user)
    {
        $currentCustomer = Client::where('email', $user->email)->first();
        return $currentCustomer;
    }
}