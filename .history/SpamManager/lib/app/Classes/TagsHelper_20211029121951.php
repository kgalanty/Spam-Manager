<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Models\Tags as Tag;
class TagsHelper
{
    /**
     * string $newtag Tag Name
     * int $threadid Thread id to assign new tag with
     * bool $forceadd if it should not check auth (for cron)
     */
    public static function addTag(string $newtag, int $threadid, bool $forceadd = false, int $doer = 0)
    {
        if (Tag::where('t_id', $threadid)->where('tag', $newtag)->count() == 0) {
            $tag = Tag::create([
                't_id' => $threadid,
                'tag' => $newtag,
                'approved' => AuthControl::isAdmin() || $forceadd ? 1 : 0
            ]);
            if ($tag) {
                //fix admin id - cron choice
                if (AuthControl::isAdmin() || $forceadd) {
                    Logs::AddTag($threadid, $doer > 0 ? $doer : $_SESSION['adminid'], $tag->tag);
                    if(!$forceadd) TagsLog::Add($threadid, $tag->tag);
                } else {
                    if(!$forceadd) TagsLog::ProposeNewTag($threadid, $tag->tag);
                    Logs::ProposeNewTag($threadid, $doer > 0 ? $doer : $_SESSION['adminid'], $tag->tag);
                }
                return 'success';
            }
        } else {
            return 'This tag is already there.';
        }
    }
}