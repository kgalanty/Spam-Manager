<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\DBTables\DBTables;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Module\Addon\ChatManager\app\Classes\DateTimeHelper;

class StatsHelper
{
    public static function getTagsFrequency(array $params)
    {
        $threads = DB::table(DBTables::Tags . ' as tg')
            ->join(DBTables::Threads . ' as t', 'tg.t_id', '=', 't.id')
            ->whereBetween('t.date', [$params['datefrom'], $params['dateto']])
            ->where('tg.approved', 1)
            ->where('t.agent', intval(trim($params['op'])))
            ->groupBy('tg.tag')
            ->selectRaw('t.agent, tg.tag, count(tg.id) as count, t.id')
            ->get();
        $data = [];

        if (is_array($threads)) {
            foreach ($threads as $t) {
                $data[$t->agent][] = $t;
            }
            return $data[$params['op']];
        }
        return $data;
    }
    public static function getStats($params)
    {
        $threads = DB::table(DBTables::Threads . ' as t')
            ->join(DBTables::Tags . ' as tg', 'tg.t_id', '=', 't.id')
            ->join('tbladmins as a', 'a.id', '=', 't.agent')
            ->leftJoin('tblorders as o', 'o.id', '=', 't.orderid')
            ->leftJoin('tblinvoices as inv', 'inv.id', '=', 'o.invoiceid')
            ->leftJoin('tblinvoices as inv2', 'inv2.id', '=', 't.invoiceid')
            ->whereBetween('t.date', [$params['datefrom'], $params['dateto']])
            ->where('tg.approved', 1)
            ->where(function ($q) {
                $q->where(function ($query) {
                    $query->whereIn('tg.tag', ['directsale', 'upsell', 'cycle', 'vps/ds', 'convertedsale'])
                        ->whereNotExists(function ($q2) {
                            $q2->select(DB::raw(1))
                                ->from(DBTables::Tags)
                                ->whereRaw('t_id=t.id')
                                ->whereRaw('tag="upgrade"');
                        })
                        ->where(function ($q2) {
                            $q2->where('inv.status', '=', 'Paid')->orWhere('inv2.status', '=', 'Paid');
                        });
                });
                $q->orWhere(function ($query) {
                    $query->whereNotIn('tg.tag', ['directsale', 'upsell', 'cycle', 'vps/ds', 'convertedsale', 'upgrade']);
                });
                $q->orWhere(function ($query) {
                    $query->where('tg.tag', 'upgrade')
                        ->where(function ($q2) {
                            $q2->where('inv.status', 'Paid')->orWhere('inv2.status', 'Paid');
                        });
                });
            });

        if (AuthControl::isAgent()) {
            $threads = $threads->where('t.agent', $_SESSION['adminid']);
        } elseif (AuthControl::isAdmin() && $params['op'] != '') {
            $threads = $threads->where('t.agent', intval(trim($params['op'])));
        }
        if (AuthControl::isAdmin()) {
            $threads = $threads->whereNotIn('t.agent', AdminGroupsConsts::AGENT_DISALLOWED);
        }
        $threads = $threads
            ->groupBy('t.agent')
            ->groupBy('tg.tag')
            ->selectRaw('t.agent, a.firstname, a.lastname, a.id as adminid, tg.tag, count(t.id) as count');
        if ($_SESSION['adminid'] == 230) {
            //var_dump($threads->toSql());die;
        }
        return $threads->get();
    }
    public static function getPointsFromCancellations(?array $params)
    {
        $query = DB::table('kg_cancelrequests as cr')
            ->whereBetween('date', [$params['datefrom'], $params['dateto']]);
        if (AuthControl::isAgent()) {
            $query = $query->where('cr.agent', $_SESSION['adminid']);
        } elseif (AuthControl::isAdmin() && $params['op'] != '') {
            $query = $query->where('cr.agent', intval(trim($params['op'])));
        }
        $query = $query->where('cr.action', 'stayed')
            ->groupBy('cr.agent')->selectRaw('cr.agent, count(*) as stayed')
            ->get();
        return $query;
    }
    public static function getDecrementPoints($params)
    {
        $q2 = ' select t.id,
        t.agent, count(t.id) as c from `' . DBTables::Threads . '` t 
        join `' . DBTables::Tags . '` as tg ON tg.t_id = t.id
        join `tbladmins` as ad ON ad.id = t.agent
        left join tblorders as o ON o.id = t.orderid
        left join tblinvoices as inv ON inv.id = o.invoiceid
        left join tblinvoices as inv2 ON inv.id = t.invoiceid
        where 
        (inv2.status = "Paid" or inv.status = "Paid") and 
        tg.tag in ("upgrade", "cycle", "upsell", "directsale", "convertedsale", "vps/ds")
        and exists (select id from ' . DBTables::Tags . ' as tg2 where tg2.tag = "upgrade" and tg2.t_id = t.id and tg2.approved = 1)
        and t.date between ? and ?
        ';
        $q = 'select sum(x.c) as s, agent FROM ( select t.id,
            t.agent, count(t.id) as c from `' . DBTables::Threads . '` t 
            join `' . DBTables::Tags . '` as tg ON tg.t_id = t.id
            join `tbladmins` as ad ON ad.id = t.agent
            left join tblorders as o ON o.id = t.orderid
            left join tblinvoices as inv ON inv.id = o.invoiceid
            left join tblinvoices as inv2 ON inv.id = t.invoiceid
            where 
            (inv2.status = "Paid" or inv.status = "Paid") and 
            tg.tag = "upgrade"
            and exists (select id from ' . DBTables::Tags . ' as tg2 where tg2.tag IN ("cycle", "upsell", "directsale", "convertedsale", "vps/ds") and tg2.t_id = t.id and tg2.approved = 1)
            and t.date between ? and ?
            ';
        if (AuthControl::isAgent()) {
            $q .= 'and agent = ' . $_SESSION['adminid'];
        } elseif (AuthControl::isAdmin() && $params['op'] != '') {
            $q .= ' and ad.id = ' . intval(trim($params['op']));
        }
        $q .= ' group by t.id, t.agent having c > 1) as x
            group by agent';

        $threads_upgrade_points = collect(DB::select($q, [$params['datefrom'], $params['dateto']]))->keyBy('agent');
        if ($_SESSION['adminid'] == 230) {
            //  var_dump(DB::select($q2, [$params['datefrom'], $params['dateto']]));die;
        }
        return $threads_upgrade_points;
    }
    private static function getManualPoints(array $filters)
    {
        $external = DB::table(DBTables::ManualPoints)
            ->whereBetween('date', [DateTimeHelper::convertFromUTCToTZ($filters['tz'], $filters['datefrom'], 'Y-m-d'), DateTimeHelper::convertFromUTCToTZ($filters['tz'], $filters['dateto'], 'Y-m-d')]);
        if ($filters['op'] != '' && AuthControl::isAdmin()) {
            $external = $external->where('userid', (int)$filters['op']);
        }
        if(AuthControl::isAgent())
        {
            $external = $external->where('userid', (int)$_SESSION['adminid']);
        }
        $external = $external->join('tbladmins as a', 'a.id', '=', 'userid')
            ->selectRaw('SUM(points) as points, userid, a.firstname, a.lastname')
            ->groupBy('userid')
            ->get();
        return $external;
    }
    public static function CreateResult($threads, $cm_stayed_requests, array $filters)
    {
        $r = [];
        $tags = [
            'canoffer' => 0,
            'cannotoffer' => 0,
            'totalsales' => 0,
            'directsale' => 0,
            'convertedsale' => 0,
            'upsell' => 0,
            'cycle' => 0,
            'wcb' => 0,
            'sales' => 0,
            'stayed' => 0,
            "vps/ds" => 0,
            'upgrade' => 0,
            'manualpoints' => 0
        ];
        if ($_SESSION['adminid'] == 230) {
            //echo('<pre>'); var_dump($threads); die;
        }

        // $agents = [];
        //rearrange data from query to one unified array as query returns scattered data across rows
        foreach ($threads as $t) {
            // if (!in_array($t->adminid, $agents)) {
            //     $agents[] = $t->adminid;
            // }
            $r[$t->agent] = array_merge($tags, $r[$t->agent]);
            $r[$t->agent]['data']['agent'] = $t->agent;
            $r[$t->agent]['data']['agent_name'] = $t->firstname . ' ' . $t->lastname;
            $r[$t->agent]['data']['agent_id'] = $t->adminid;
            $r[$t->agent][str_replace([' '], [''], $t->tag)] = $t->count;
            $r[$t->agent]['data']['cm_points'] = 0;
        }
        $external = self::getManualPoints($filters);

        foreach ($external as $extItem) {
            if (!$r[$extItem->userid]) {
                $r[$extItem->userid] = array_merge($tags, [
                    'manualpoints' => (int)$extItem->points, 'data' =>
                    [
                        'cm_points' => 0,
                        'agent' => $extItem->userid,
                        'agent_name' => $extItem->firstname . ' ' . $extItem->lastname,
                        'agent_id' => $extItem->userid
                    ]
                ]);
            } else {
                $r[$extItem->userid]['manualpoints'] = (int)$extItem->points ? $r[$extItem->userid]['manualpoints']+$extItem->points : 0;
            }
        }
        $o = [];
        $cm_points = collect($cm_stayed_requests)->keyBy('agent');
        foreach ($cm_points as $agent_id => $points) {

            if (!array_key_exists($agent_id, $r)) {
                $r[$agent_id] = $tags;
                $agent_data = Admin::where('id', $agent_id)->first(['id', 'firstname', 'lastname']);
                $r[$agent_id]['data']['agent_name'] = trim($agent_data->firstname) . ' ' . trim($agent_data->lastname);
                $r[$agent_id]['data']['agent_id'] = $agent_data->id;
            }
            $r[$agent_id]['data']['cm_points'] = $points->stayed;
        }

        //add points to decrement to final array
        foreach ($r as $agent_email => $rr) {
           // $searchForManualPoints = array_search($rr['data']['agent'], array_column($external, 'userid'));
            //$rr['decrementpoints'] = $threads_upgrade_points[$agent_email] ? (int)$threads_upgrade_points[$agent_email]->s - 1 : 0;
            //$rr['manualpoints'] = $searchForManualPoints !== false ? (int)$external[$searchForManualPoints]->points : 0;
            $rr['decrementpoints'] = 0;
            $rr['cm_points'] = $cm_points[$agent_email] ? $cm_points[$agent_email]->stayed : 0;
            $o[] = $rr;
        }

        return $o;
    }
    public static function AsColumns(array $result)
    {
        $rows = [];
        foreach ($result as $item) {
            $rows[] = [
                $item['data']['agent_name'],
                $item['directsale'] + $item['wcb'] + $item['manualpoints'],
                $item['cannotoffer'] . ' (' . ($item['directsale'] + $item['wcb'] + $item['cannotoffer'] == 0 ? 0 : round($item['cannotoffer'] / ($item['directsale'] + $item['wcb'] + $item['cannotoffer']) * 100)) . '%)',
                $item['directsale'] + $item['wcb'] + $item['cannotoffer'],
                $item['directsale'],
                $item['convertedsale'],
                $item['upgrade'],
                $item['directsale'] + $item['convertedsale'] + $item['upgrade'],
                $item['upsell'],
                $item['cycle'],
                $item['data']['cm_points'],
                $item['vps/ds'] ?? 0,
                $item['manualpoints'] ?? 0,
                $item['directsale'] + $item['convertedsale'] + $item['upsell'] + $item['cycle'] + $item['vps/ds'] + $item['data']['cm_points'] + $item['upgrade'] + $item['manualpoints'],
                ($item['directsale'] + $item['wcb'] + $item['manualpoints'] > 0 ? round((($item['directsale'] + $item['convertedsale'] + $item['upgrade'] + $item['manualpoints']) * 100) / ($item['directsale'] + $item['wcb'] + $item['manualpoints']), 2) : 0) . '%',
                ($item['directsale'] + $item['wcb'] > 0 ? round(($item['directsale'] + $item['convertedsale'] + $item['upgrade'] + $item['upsell'] + $item['cycle'] + $item['data']['cm_points'] + $item['vps/ds']) * 100 / ($item['directsale'] + $item['wcb']), 2) : 0) . '%',
            ];
        }
        return $rows;
    }
    public static function Details(array $params): array
    {
        var_dump($params, $_GET);
        die;
        return [];
    }
    // private static function log($thread_id, $tag, $action)
    // {
    //     TagHistory::create(
    //         [
    //             'thread_id' => $thread_id,
    //             'tag' => $tag,
    //             'doer' => $_SESSION['adminid'],
    //             'action' => $action,
    //             'created_at' => gmdate('Y-m-d H:i:s')
    //         ]
    //     );
    // }
    // public static function ProposeDeletion($threadid, $tag)
    // {
    //     return self::log($threadid, $tag, 'Propose Deletion');
    // }
    // public static function Delete($threadid, $tag)
    // {
    //     return self::log($threadid, $tag, 'Delete');
    // }
    // public static function Approve($threadid, $tag)
    // {
    //     return self::log($threadid, $tag, 'Approve');
    // }
}
