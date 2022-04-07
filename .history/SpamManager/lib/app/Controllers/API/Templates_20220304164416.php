<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\SpamManager\app\Classes\Logs;
use WHMCS\Module\Addon\SpamManager\app\Models\Threads as ThreadsModel;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailTemplates;

class Templates extends API
{
    public function get()
    {
        EmailTemplates::spamManager()->get();
    }
    public function post()
    {
        if($_GET['a'] === 'AddNewTemplate')
        {
            $groupid = $this->input['groupid'];
            $name = $this->input['name'];

        }

    }
}
