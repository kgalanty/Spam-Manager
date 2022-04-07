<?php

namespace WHMCS\Module\Addon\SpamManager\app\Controllers\API;

use WHMCS\Module\Addon\SpamManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use \Illuminate\Database\Eloquent\Builder;
//use WHMCS\Module\Addon\SpamManager\app\Models\Request as RequestModel;
use WHMCS\Module\Addon\SpamManager\app\Models\Request as RequestModel;
use WHMCS\Module\Addon\SpamManager\app\Models\CancelRequest;
use WHMCS\Module\Addon\SpamManager\app\Models\Clientgroup;
use WHMCS\Module\Addon\SpamManager\app\Models\Admin;
use WHMCS\Module\Addon\SpamManager\app\Models\InvoiceProlong;
use WHMCS\Module\Addon\SpamManager\app\Models\Service;
use WHMCS\Module\Addon\SpamManager\app\Classes\EmailTemplatesConsts;
use WHMCS\Module\Addon\SpamManager\app\Classes\SupervisorsConsts;
use WHMCS\Module\Addon\SpamManager\app\Classes\EmailHelper;
use WHMCS\Module\Addon\SpamManager\app\Classes\Invoices as InvoicesClass;

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
