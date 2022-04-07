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

class Tags extends API
{
    public function get()
    {
        if ($_GET['a'] == 'GetTagsSingleThread') {

            $t_id = (int)trim($_GET['threadid']);
            $results = Tag::where('t_id', $t_id)->get();
            return ['data' => $results];
        } elseif ($_GET['a'] == 'GetDistinctTags') {
            $tags = [];
            $results = Tag::distinct()->get(['tag']);
            for ($i = 0; $i < count($results); $i++) $tags[] = strtolower($results[$i]->tag);
            return ['result' => 'success', 'data' => $tags];
        }
        //     $query= $_GET['q'];

        //     if($query)
        //     {
        //         $result = Client::where('firstname', 'LIKE', '%'.$query.'%')
        //             ->orWhere('lastname', 'LIKE', '%'.$query.'%')
        //             ->orWhere('email', 'LIKE', '%'.$query.'%')
        //             ->take(10)
        //             ->get();
        //     }
        //     else
        //     {
        //         $result = Client::take(10)
        //         ->get();
        //     }

        //    // $total = $result->count();
        //     return ['data' => $result];
        //     $results = DB::table('tbladmins as a')
        //    ->orderBy('a.firstname', 'ASC')
        //    ->get(['a.id', 'a.firstname', 'a.lastname']);
        //     return ['data' => $results];
    }
    public function post()
    {
        if ($this->input['action'] == 'Add') {
            $id = (int)$this->input['tid'];
            $tag = trim($this->input['tag']);
            if ($id && $tag) {
                return TagsHelper::addTag($tag, $id);
                // if (Tag::where('t_id', $id)->where('tag', $tag)->count() == 0) {
                //     $tag = Tag::create([
                //         't_id' => $id,
                //         'tag' => $tag,
                //         'approved' => AuthControl::isAdmin() ? 1 : 0
                //     ]);
                //     if ($tag) {
                        
                //         if (AuthControl::isAdmin()) {
                //             Logs::AddTag($id, $_SESSION['adminid'], $tag->tag);
                //             TagsLog::Add($id, $tag->tag);
                //         } else {
                //             TagsLog::ProposeNewTag($id, $tag->tag);
                //             Logs::ProposeNewTag($id, $_SESSION['adminid'], $tag->tag);
                //         }
                //         return 'success';
                //     }
                // } else {
                //     return 'This tag is already there.';
                // }
            }
        }
        if ($this->input['action'] == 'ApproveTag') {
            $tag_id = (int)trim($this->input['tag']);
            if (AuthControl::isAdmin()) {
                if ($tag_id) {
                    Tag::where('id', $tag_id)->update(['approved' => 1]);
                    $tag = Tag::where('id', $tag_id)->first();
                    TagsLog::Approve($tag->t_id, $tag->tag);
                    Logs::AddTag($tag->t_id, $_SESSION['adminid'], $tag->tag);
                    return ['data' => 'success'];
                }
                return ['data' => 'Invalid Tag ID'];
            }
            return ['data' => 'No permission to perform this operation'];
        }
        if ($this->input['action'] == 'undoProposeDeletion') {
            $tag_id = (int)trim($this->input['tag']);
            if (AuthControl::isAdmin()) {
                if ($tag_id) {
                    Tag::where('id', $tag_id)->update(['proposed_deletion' => 0]);
                    $tag = Tag::where('id', $tag_id)->first();
                    TagsLog::DeclineProposeDeletion($tag->t_id, $tag->tag);
                    Logs::DeclineProposeTagDeletion( $tag->t_id, $_SESSION['adminid'], $tag->tag);
                    return ['data' => 'success'];
                }
                return ['data' => 'Invalid Tag ID'];
            }
            return ['data' => 'No permission to perform this operation'];
        }
        if ($this->input['action'] == 'DeleteTag') {
            $tag_id = (int)trim($this->input['tag']);
            $tag = Tag::where('id', $tag_id)->first();
            if (AuthControl::isAdmin()) {
                if ($tag_id) {
                    TagsLog::Delete($tag->t_id, $tag->tag);
                    Tag::where('id', $tag_id)->delete();
                    Logs::DelTag($tag->t_id, $_SESSION['adminid'], $tag->tag);
                    return ['data' => 'success'];
                }
                return ['data' => 'Invalid Tag ID'];
            }
            if (AuthControl::isAgent()) {
                if ($tag_id) {
                    TagsLog::ProposeDeletion($tag->t_id, $tag->tag);
                    Logs::ProposeDelTag($tag->t_id, $_SESSION['adminid'], $tag->tag);
                    Tag::where('id', $tag_id)->update(['proposed_deletion' => 1]);
                    return ['data' => 'success'];
                }
                return ['data' => 'Invalid Tag ID'];
            }
            return ['data' => 'No permission to perform this operation'];
        }
    }
}
