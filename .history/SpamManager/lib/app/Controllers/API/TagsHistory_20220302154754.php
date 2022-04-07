<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Models\Tags as Tag;
use WHMCS\Module\Addon\SpamManager\app\Models\TagHistory;
use WHMCS\Module\Addon\SpamManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\SpamManager\app\Classes\TagsLog;

class TagsHistory extends API
{
    public function get()
    {
        if ($_GET['a'] == 'GetTagsLog') {
            if (AuthControl::isAdmin()) {
                $t_id = (int)trim($_GET['threadid']);
                $results = TagHistory::with('doer')->where('thread_id', $t_id)->orderBy('id', 'DESC')->get();
                return ['data' => $results];
            }
            return 'No permission';
        }
    }
    public function post()
    {
    }
}
