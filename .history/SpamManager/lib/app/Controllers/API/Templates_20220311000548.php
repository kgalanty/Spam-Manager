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

        foreach ($templates as $tpl) {

            $group = explode('_', $tpl->name);
            if (!$out[$group[1]]) {
                $out[$group[1]] = ['name' => $group[1], 'tpls' => []];
            }
            $out[$group[1]]['tpls'][] = ['id' => $tpl->id, 'name' => $group[2], 'disabled' => $tpl->disabled, 'subject' => $tpl->subject];
        }
        return ['templates' => array_values($out)];
    }
    public function post()
    {
        if ($_GET['a'] === 'AddNewTemplate') {
            if ($this->input['group'] == '') {
                return ['response' => 'error', 'msg' => 'Group name cannot be empty'];
            }
            if ($this->input['name'] == '') {
                return ['response' => 'error', 'msg' => 'Template name cannot be empty'];
            }
            $group = trim(str_replace('_', ' ', $this->input['group']));
            $name = str_replace('_', ' ', $this->input['name']);
            $template = new \WHMCS\Mail\Template();
            // $template->type = 'spammanager_' . $group;
            // $template->name = $name;
            $template->type = 'product';
            $template->name = 'spammanager_' . $group . '_' . $name;
            $template->custom = true;
            try {
                $template->save();
                \logAdminActivity("Email Template Created: '" . $name . "' - Template ID: " . $template->id);
                return ['response' => 'success', 'templateid' => $template->id];
            } catch (\WHMCS\Exception\Model\UniqueConstraint $e) {
                return ['response' => 'error', 'msg' => 'Template name already exists'];
            } catch (\Exception $e) {
                return ['response' => 'error', 'msg' => $e->getMessage()];
            }
        } elseif ($_GET['a'] === 'DeleteTemplate') {
            if ($this->input['group'] == '') {
                return ['response' => 'error', 'msg' => 'Group cannot be empty'];
            }
            $id = $this->input['group'];

            try {
                $template = EmailTemplates::find($id);
                $templateName = $template->name;
                if ($template) {
                    $template->delete();
                    \logAdminActivity("Email Template Deleted: '" . $templateName . "' - Template ID: " . $id);
                }

                return ['response' => 'success'];
            } catch (\Exception $e) {
                return ['response' => 'error', 'msg' => $e->getMessage()];
            }
        } elseif ($_GET['a'] === 'CloneTemplate') {
            if ($this->input['tplid'] == '') {
                return ['response' => 'error', 'msg' => 'Template ID cannot be empty'];
            }
            if ($this->input['name'] == '') {
                return ['response' => 'error', 'msg' => 'Template name cannot be empty'];
            }
            $id = $this->input['tplid'];
            $name = $this->input['name'];

            try {
                $template = EmailTemplates::find($id);
                //$template->name = trim($name);
                list(,$groupname,) = explode('_', $template->name, 3);
               var_dump($template->attachments);die;
                $newTpl = new \WHMCS\Mail\Template();
                $newTpl->type = 'product';
                $newTpl->name = 'spammanager_' .  $groupname . '_' . $name;
                $newTpl->subject = $template->subject;
                $newTpl->message = $template->message;
                $newTpl->attachments = $template->attachments;
                $newTpl->fromname = $template->fromname;
                $newTpl->copyto = $template->copyto;
                $newTpl->blind_copy_to = $template->blind_copy_to;
                $newTpl->custom = true;
                var_dump($newTpl);die;
                $newTpl->save();
                \logAdminActivity("Email Template Cloned: '" . $name . "' - Template ID: " . $newTpl->id);
                return ['response' => 'success', 'templateid' => $newTpl->id];
            } catch (\WHMCS\Exception\Model\UniqueConstraint $e) {
                return ['response' => 'error', 'msg' => 'Template name already exists'];
            } catch (\Exception $e) {
                return ['response' => 'error', 'msg' => $e->getMessage()];
            }
        }
    }
}
