<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\QueuesList as QueuesListModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Product as ProductsModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Servers;

class QueuesList extends API
{
    public function get()
    {
        if ($_GET['list']) {
            $list = QueuesListModel::with(['admin', 'template'])->where('id', $_GET['list'])->first();
            $config = $list->config ? json_decode($list->config, 1) : ['servers' => [], 'products' => []];

            $serversList = $config['servers'] ? $config['servers'] : explode(',', $list->servers);
            $servers = Servers::whereIn('id', $serversList)->get();
            $products =  ProductsModel::with(['group'])->whereIn('id', $config['products'])->orderBy('id', 'ASC')->get();
            $list->statuses_array = $list->statuses ? explode(',', $list->statuses) : [];

            return ['list' => $list, 'servers' => $servers, 'products' => $products];
        }

        $page = $_GET['page'] == 1 ? 0 : ($_GET['page'] - 1) * $_GET['perpage'];
        $perpage = $_GET['perpage'] ? $_GET['perpage'] : 20;
        return [
            'total' => QueuesListModel::count(),
            'queueslist' => QueuesListModel::with(['admin', 'template'])->withCount('emails')->skip($page)->take($perpage)->orderBy('id', 'DESC')->get()
        ];
    }
    public function post()
    {
    }
}
