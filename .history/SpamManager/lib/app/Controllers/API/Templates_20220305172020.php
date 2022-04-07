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
        $templates = EmailTemplates::spamManager()->get();
        $out = [];
       
        foreach($templates as $tpl)
        {
            $group = substr($tpl->type, strpos($tpl->type, '_')+1);
            if(!$out[$group])
            {
                $out[$group] = ['name' => $group, 'tpls' => []];
            }
            $out[$group]['tpls'][] = ['id' => $tpl->id, 'name' => $tpl->name, 'disabled' => $tpl->disabled];
        }
        return ['templates' => $out];
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
                return ['response'=> 'error', 'msg' => 'Template name already exists'];
            } catch (\Exception $e) {
                return ['response'=> 'error', 'msg' => $e->getMessage()];
            }
        }

    }
}
