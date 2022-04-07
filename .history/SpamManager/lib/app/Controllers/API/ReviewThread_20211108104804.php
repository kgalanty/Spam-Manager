<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewThread as ReviewThreadModel;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
class ReviewThread extends API
{
    public function get()
    {
        if ($_GET['action'] == 'GetReviews') {
            if (AuthControl::isAdmin() || AuthControl::isAgent()) {
                $threadid = (int)$_GET['threadid'];
                $reviews = ReviewThreadModel::with(['doer'])->thread($threadid)->orderBy('id', 'DESC')->get();
                return ['data' => $reviews, 'result' => 'success'];
            }
            return ['msg' => 'No permissions to complete this operation', 'result' => 'error'];
        }
        if ($_GET['action'] == 'GetReviewStatus') {
            $threadid = (int)$_GET['threadid'];
            if ($threadid) {
                if (ReviewThreadModel::isPending()->thread($threadid)->count() > 0) {
                    return ['data' => 1, 'result' => 'success'];
                } else {
                    return ['data' => 0, 'result' => 'success'];
                }
            }
            return ['msg' => 'No valid thread ID given', 'result' => 'error'];
        }
    }
    public function post()
    {
        $threadid = (int)$this->input['threadid'];
        $comment = trim($this->input['comment']);
        $action = trim($this->input['action']);

        if ($action == 'SaveReview') {
            if ($threadid) {
                Logs::SendToReview($threadid, $_SESSION['adminid']);
                ReviewThreadModel::create([
                    'threadid' => $threadid,
                    'sender' => $_SESSION['adminid'],
                    'comment' => $comment,
                    'pending' => 1,
                    'created_at' => gmdate('Y-m-d H:i:s')
                ]);
                return 'success';
            }
            return 'No Thread ID was given';
        }
        elseif($action == 'ReviewComment')
        {
            $id = (int)$this->input['entry'];
            Logs::MarkAsReviewed($threadid, $_SESSION['adminid'], $id);
            ReviewThreadModel::id($id)->update(['pending' => 0]);
            return ['data'=>'success'];
        }
    }
}
