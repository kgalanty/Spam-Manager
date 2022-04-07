<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\DBTables\DBTables;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
class LiveChatParsers
{
    public static function findCloseChatDate($eventsList)
    {
        foreach ($eventsList as $event) {
            if ($event->system_message_type == 'manual_archived_agent' || $event->system_message_type == 'manual_archived_customer') {
                return $event->created_at;
            }
        }
        return $eventsList[key(array_slice($eventsList, -1, 1, true))]->created_at;
    }
    public static function parseArchiveList($list)
    {
        $admins = Admin::whereIn('roleid', array_merge(AdminGroupsConsts::AGENT, AdminGroupsConsts::ADMIN))
        ->whereNotIn('id', AdminGroupsConsts::AGENT_DISALLOWED)->get(['id', 'email'])->keyBy('email');
        foreach ($list as $chatitem) {
            $user = $chatitem->thread->user_ids[count($chatitem->thread->user_ids) - 1];
            
            if (DB::table(DBTables::Threads)->where('threadid', $chatitem->thread->id)->count() == 0) {
                $agent = 0;
                //echo('<pre>'); var_dump($chatitem->thread->user_ids, $admins); die;
                $agent = LiveChatHelper::getAgentByPersonalTags($chatitem, $admins);
                // && isset($admins[$chatitem->thread->user_ids[0]])
                $insertRow = [
                    'chatid' => $chatitem->id,
                    'threadid' => $chatitem->thread->id,
                    'users' => $user,
                    'name' => '',
                    'email' => '',
                    'domain' => '',
                    'orderid' => null,
                    'agent' => $agent,
                    'date' => self::findCloseChatDate($chatitem->thread->events),
                    'created_at' => DB::raw('NOW()')

                ];
                $customer = LiveChatHelper::getUserById($user, $chatitem->users);
                $insertRow = array_merge($insertRow, MatchClient::execute(
                    ['chatitem' => $chatitem, 'customer' => $customer]
                ));

                $_SESSION['cmcount'] += 1;
                $id = DB::table(DBTables::Threads)->insertGetId($insertRow);
                //echo('<pre>'); var_dump($insertRow, $id, $chatitem, count($list)); die;
                Logs::AddThreadByCron($id);
                self::parseTags($id, $chatitem->thread->tags);
            }

            self::parseCustomer($user, $chatitem->users);
        }
    }
    public static function parseTags(int $thread_id, array $tags)
    {
        foreach ($tags as $tag) {
            if (DB::table(DBTables::Tags)->where('t_id', $thread_id)->count() == 0) {
                $rows[] = ['t_id' => $thread_id, 'tag' => $tag, 'approved' => 1, 'proposed_deletion' => 0];
            }
        }
        $doer = $_SESSION['adminid'] && $_SESSION['adminid'] > 0 ? $_SESSION['adminid'] : 0;
        if ($rows) {
            DB::table(DBTables::Tags)->insert($rows);
            Logs::LogTagsAdd($thread_id, $doer, $rows);
        }
    }
    public static function parseCustomer(string $userid, array $users)
    {
        $customer = LiveChatHelper::getUserById($userid,  $users);
        if ($customer) {
            DB::table(DBTables::Customers)
                ->updateOrInsert(
                    [
                        'client_id' => $userid,
                    ],
                    [
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'ip' => $customer->last_visit->ip,
                        'user_agent' => $customer->last_visit->user_agent,
                        'geolocation' => $customer->last_visit->geolocation ? json_encode($customer->last_visit->geolocation) : null

                    ]
                );
        }
    }
}
