<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Module\Addon\ChatManager\app\Classes\FindClientHelper;
use WHMCS\Module\Addon\ChatManager\app\Classes\FindOrderHelper;

class MatchClient
{
    public static function execute(array $params = [])
    {
        $client = FindClientHelper::execute($params['customer']);
        if ($client) {
            $orderid = FindOrderHelper::execute($params['customer'], $params['chatitem']->thread->events[0]->created_at);
            if ($orderid && $orderid != 0) {
                $domain = FindOrderHelper::findDomainByOrder($orderid);
            }
        }
        return ['name' => $client ? $client->firstname.' '.$client->lastname:'', 'email' => $client ? $client->email : '', 'domain' => $domain];
    }
}
