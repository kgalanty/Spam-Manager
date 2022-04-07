<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Classes\StatsHelper;
use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\DateTimeHelper;
use WHMCS\Module\Addon\ChatManager\app\Classes\DownloadfileService;
use WHMCS\Module\Addon\ChatManager\app\Classes\statsPDFWrapper;
class Export extends API
{
    public function get()
    {
        $dateFrom = $_GET['datefrom'] != '' ? $_GET['datefrom'] : gmdate('Y-m-' . (date('j') < 16 ? 1 : 16) . '\T00:00:00.000000\Z');
        $dateTo = $_GET['dateto'] != '' ? $_GET['dateto'] : gmdate('Y-m-' . (date('j') < 16 ? 15 : 't') . '\T23:59:59.000000\Z');
        $tz = $_GET['tz'] ? trim($_GET['tz']) : 'Europe/Sofia';
        $threads = StatsHelper::getStats(['datefrom' => $dateFrom, 'dateto' => $dateTo, 'op' => $_GET['op']]);
        $cm_stayed_requests = StatsHelper::getPointsFromCancellations(['datefrom' => $dateFrom, 'dateto' => $dateTo, 'op' => $_GET['op']]);
        //calculate how many points per agent have to be substracted, as 'upgrade' tag should count as 1 in one thread 
        // even among other pointgiving tags.
        //This is returned and substracted on frontend. Raw query for speed gain
       // $threads_upgrade_points = StatsHelper::getDecrementPoints(['datefrom' => $dateFrom, 'dateto' => $dateTo, 'op' => $_GET['op']]);
        $o = StatsHelper::CreateResult($threads, $cm_stayed_requests, ['datefrom' => $dateFrom, 'dateto' => $dateTo, 'op' => $_GET['op'], 'tz'=>$_GET['tz']]);

        if (count($o) > 0 && AuthControl::isAdmin()) {
            $sum = [];
            foreach ($o as $item) {
                foreach ($item as $prop => $v) {
                    if ($prop == 'data') {
                        $sum['data']['cm_points'] = $sum['data']['cm_points'] ? $sum['data']['cm_points'] + $v['cm_points'] : $v['cm_points'];
                    } else {
                        $sum[$prop] = $sum[$prop] ? $sum[$prop] + $v : $v;
                    }
                }
            }
            $sum['data']['agent_name'] = 'TEAM';
            $o[] = $sum;
        }
        // echo('<pre>'); var_dump($o);die;
        $rows = StatsHelper::AsColumns($o);

      
        $widths = [20, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 17, 15, 15];
        $config = ['datefrom' => DateTimeHelper::convertFromUTCToTZ($tz, $dateFrom, 'd-m-Y'), 'dateto' => DateTimeHelper::convertDateToUTC($tz, $dateTo, 'd-m-Y'), 'widths' => $widths, 'data' => $rows];
        $columns = ['Agent', 'Can Offer', 'Cannot Offer', "Total Sales\nChats", 'Direct Sales', 'Converted Sales', 'Upgrades', 'Total Sales', 'Upsell', 'Cycle', 'Stayed', 'VPS/DS', 'External Points', 'Total Points', 'Conversion w/o Points', 'Conversion with Points'];

        $path = (new statsPDFWrapper($columns, $config))->releasePDF();
       
        if(file_exists($path)){
           (new DownloadfileService($path))->setHeaders()->downloadInit();
            exit;
        }
        return ['data' => $path];
    }
    public function post()
    {
    }
}



