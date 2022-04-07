<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Tags as Tag;
use WHMCS\Module\Addon\ChatManager\app\Models\TagHistory;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Classes\TagsHelper;
use WHMCS\Module\Addon\ChatManager\app\Classes\TagsLog;
use WHMCS\Module\Addon\ChatManager\app\Models\Manualpoints;

class Points extends API
{
    public function get()
    {
        if ($_GET['a'] == 'GetSingleAgentPoints') {
            // `agentid=${this.agentid}`,
            // `datefrom=${this.createUTCDatetime(this.dates.datefrom)}`,
            // `dateto=${this.createUTCDatetime(this.dates.dateTo)}`,
            // `page=${this.page}`,
            $page = $_GET['page'] == 1 ? 0 : ($_GET['page'] - 1) * $_GET['perpage'];
            $perpage = $_GET['perpage'] ? (int)$_GET['perpage'] : 25;
            try
            {
            $result = Manualpoints::with(['author', 'agent'])
                ->whereBetween('date', [$_GET['datefrom'], $_GET['dateto']])
                ->where('userid', $_GET['agentid']);

            $total = $result->count();
            $result = $result->skip($page)->take($perpage)->orderBy('id', 'DESC')
                ->get();
                return ['data' => $result, 'total' => $total, 'result' => 'success'];
            }
            catch(\Exception $e)
            {
                return ['result' => $e->getMessage()];
            }
            
        }
    }
    public function post()
    {
        if ($this->input['a'] == 'Add') {
            $points = (int)$this->input['points'];
            $operator = (int)$this->input['operator'];
            $author = (int)$_SESSION['adminid'];
            $comment = trim($this->input['comment']);
            $date = trim($this->input['date']);
            if ($points && $operator && $author) {
                $model = Manualpoints::create([
                    'userid' => $operator,
                    'author' => $author,
                    'points' => $points,
                    'comment' => $comment,
                    'date' => $date,
                    'created_at' => new \DateTime('now', new \DateTimeZone('UTC')),
                ]);
                Logs::AddManualPoints($author, $operator, $points, $comment, $date);
                return ['result' => 'success', 'data' => $model];
            }
        }
    }
}
