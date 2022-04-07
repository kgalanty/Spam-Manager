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
            if($this->input['group'] == '')
            {
                return ['response'=> 'error', 'msg' => 'Group name cannot be empty'];
            }
            if($this->input['name'] == '')
            {
                return ['response'=> 'error', 'msg' => 'Template name cannot be empty'];
            }
            $group = $this->input['group'];
            $name = $this->input['name'];
            $template = new \WHMCS\Mail\Template();
            $template->type = 'spammanager_' .$group;
            $template->name = $name;
            $template->custom = true;
            try {
                $template->save();
                \logAdminActivity("Email Template Created: '" . $name . "' - Template ID: " . $template->id);
                return ['response' => 'success', 'templateid' => $template->id];
            } catch (\WHMCS\Exception\Model\UniqueConstraint $e) {

            } catch (\Exception $e) {
            }
        }

    }
}
