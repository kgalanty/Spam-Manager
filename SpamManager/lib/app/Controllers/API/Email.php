<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\SpamManager\app\Classes\Logs;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;
use WHMCS\Module\Addon\SpamManager\app\Models\Service;
use WHMCS\Module\Addon\SpamManager\app\Models\EmailTemplates;
use WHMCS\Module\Addon\SpamManager\app\Models\QueuesList as QueuesListModel;

class Email extends API
{
    public function get()
    {
    }
    public function post()
    {

        $tid = $this->input['template_id'];
        $serversID = $this->input['servers'];
        $productsID = $this->input['products'];

        $hostingStatuses = $this->input['statuses'];

        $services = $this->input['services'];

        if ($services) {
            $recipients = Service::whereIn('id', $services);
            $config = ['servers' => $serversID, 'products' => $productsID];
        } else {

            $recipients = Service::whereIn('domainstatus', $hostingStatuses);
            if (count($serversID)) {
                $recipients->whereIn('server', $serversID);
            } else {
                $serversID = [];
            }
            if (count($productsID)) {
                $recipients->whereIn('packageid', $productsID);
            } else {
                $productsID = [];
            }
            $config = ['servers' => $serversID, 'products' => $productsID];
        }


        $recipients = $recipients->get(['tblhosting.id']);
        $list = QueuesListModel::create(
            [
                'templateid' => $tid,
                'adminid' => $_SESSION['adminid'],
                'servers' => '',
                'config' => json_encode($config),
                'statuses' => implode(',', $hostingStatuses),
                'date' => gmdate('Y-m-d H:i:s')
            ]
        );

        $rows = [];
        foreach ($recipients as $r) {
            $rows[] = ['id' => '', 'list' => $list->id, 'hid' => $r->id, 'sent' => '0', 'date_sent' => '', 'error' => ''];
        }
        EmailQueue::insert($rows);
        return ['response' => 'success'];
        //var_dump($this->input, $template, $recipients);die;
        //\sendMessage($template->name, 89913);
        //     $template = new \WHMCS\Mail\Template();
        //     $template->type = 'spammanager_' . $group;
        //     $template->name = $name;
        //     $template->custom = true;
        //     try {
        //         $template->save();
        //         \logAdminActivity("Email Template Created: '" . $name . "' - Template ID: " . $template->id);
        //         return ['response' => 'success', 'templateid' => $template->id];
        //     } catch (\WHMCS\Exception\Model\UniqueConstraint $e) {
        //         return ['response' => 'error', 'msg' => 'Template name already exists'];
        //     } catch (\Exception $e) {
        //         return ['response' => 'error', 'msg' => $e->getMessage()];
        //     }
        // } elseif ($_GET['a'] === 'DeleteTemplate') {
        //     if ($this->input['group'] == '') {
        //         return ['response' => 'error', 'msg' => 'Group cannot be empty'];
        //     }
        //     $id = $this->input['group'];

        //     try {
        //         $template = EmailTemplates::find($id);
        //         $templateName = $template->name;
        //         if ($template) {
        //             $template->delete();
        //             \logAdminActivity("Email Template Deleted: '" . $templateName . "' - Template ID: " . $id);
        //         }

        //         return ['response' => 'success'];
        //     } catch (\Exception $e) {
        //         return ['response' => 'error', 'msg' => $e->getMessage()];
        //     }
        // } elseif ($_GET['a'] === 'CloneTemplate') {
        //     if ($this->input['tplid'] == '') {
        //         return ['response' => 'error', 'msg' => 'Template ID cannot be empty'];
        //     }
        //     if ($this->input['name'] == '') {
        //         return ['response' => 'error', 'msg' => 'Template name cannot be empty'];
        //     }
        //     $id = $this->input['tplid'];
        //     $name = $this->input['name'];

        //     try {
        //         $template = EmailTemplates::find($id);
        //         $template->name = trim($name);

        //         $newTpl = new \WHMCS\Mail\Template();
        //         $newTpl->type = $template->type;
        //         $newTpl->name = $name;
        //         $newTpl->custom = true;

        //         $newTpl->save();
        //         \logAdminActivity("Email Template Cloned: '" . $name . "' - Template ID: " . $newTpl->id);
        //         return ['response' => 'success', 'templateid' => $newTpl->id];
        //     } catch (\WHMCS\Exception\Model\UniqueConstraint $e) {
        //         return ['response' => 'error', 'msg' => 'Template name already exists'];
        //     } catch (\Exception $e) {
        //         return ['response' => 'error', 'msg' => $e->getMessage()];
        //     }
        //}
    }
}
