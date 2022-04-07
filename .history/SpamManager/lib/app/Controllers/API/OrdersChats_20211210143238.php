<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;

use WHMCS\Module\Addon\ChatManager\app\Models\OrdersChats as OrdersChatsModel;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;

class OrdersChats extends API
{
    public function get()
    {
        if (AuthControl::isAdmin()) {
            $reviews = OrdersChatsModel::with(['order.service', 'order.domain', 'order.service.product','lccustomer'])
            ->whereDoesntHave('chat')
            ->orderBy('id', 'DESC')
            ->get();
            return ['result' => 'success', 'data' => $reviews, 'total' => count($reviews)];
        }
    }
    public function post()
    {
        if (AuthControl::isAdmin()) {
            $id = (int)$this->input['id'];
            if($id)
            {
                OrdersChatsModel::where('id', $id)->delete();
                return ['result' => 'success'];
            }
            return ['result' => 'error', 'msg' => 'No ID provided'];

        }
    }
}
