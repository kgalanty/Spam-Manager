<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Module\Addon\ChatManager\app\Models\TagHistory;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
class TagsLog
{
    public static function Add($threadid, $tag)
    {
       return self::log($threadid, $tag, 'Add');
    }
    public static function ProposeNewTag($threadid, $tag)
    {
       return self::log($threadid, $tag, 'Proposition');
    }
    private static function log($thread_id, $tag, $action, int $doer=0)
    {
        TagHistory::create(
            [
                'thread_id' => $thread_id,
                'tag' => $tag,
                'doer' => $doer>0 ? $doer : ($_SESSION['adminid'] ? $_SESSION['adminid'] : AdminGroupsConsts::CRON_ADMIN),
                'action' => $action,
                'created_at' => gmdate('Y-m-d H:i:s')
            ]
        );
    }
    public static function ProposeDeletion($threadid, $tag)
    {
        return self::log($threadid, $tag, 'Propose Deletion');
    }
    public static function Delete($threadid, $tag)
    {
        return self::log($threadid, $tag, 'Delete');
    }
    public static function Approve($threadid, $tag)
    {
        return self::log($threadid, $tag, 'Approve');
    }
    public static function DeclineProposeDeletion($threadid, $tag)
    {
        return self::log($threadid, $tag, 'Declined deleting tag');
    }
    public static function AddedByCron($threadid, $tag)
    {
        return self::log($threadid, $tag, 'Added by cron', 1);
    }
}