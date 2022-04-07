<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use \Illuminate\Database\Eloquent\Builder;
//use WHMCS\Module\Addon\ChatManager\app\Models\Request as RequestModel;
use WHMCS\Module\Addon\ChatManager\app\Models\Request as RequestModel;
use WHMCS\Module\Addon\ChatManager\app\Models\CancelRequest;
use WHMCS\Module\Addon\ChatManager\app\Models\Clientgroup;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Models\InvoiceProlong;
use WHMCS\Module\Addon\ChatManager\app\Models\Service;
use WHMCS\Module\Addon\ChatManager\app\Classes\EmailTemplatesConsts;
use WHMCS\Module\Addon\ChatManager\app\Classes\SupervisorsConsts;
use WHMCS\Module\Addon\ChatManager\app\Classes\EmailHelper;
use WHMCS\Module\Addon\ChatManager\app\Classes\Invoices as InvoicesClass;

class Supervisors extends API
{
    public function get()
    {
        $supervisors = Admin::whereHas('role', function (Builder $query) {
            $query->whereIn('id', SupervisorsConsts::SV_ROLES);
        })->orderBy('firstname', 'ASC')->orderBy('lastname', 'ASC')->get();

        return ['data' => $supervisors];
    }
    public function post()
    {
    }
}
