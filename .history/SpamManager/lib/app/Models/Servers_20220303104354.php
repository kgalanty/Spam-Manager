<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Service extends \WHMCS\Service\Service
{
    public $timestamps = false;
    protected $table = 'tblhosting';
    protected $visible = ['id','userid', 'orderid', 'packageid', 'server', 'regdate', 'domain', 'product'];
    public function client()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\Client', 'userid', 'id');
    }
    public function product()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\Product', 'packageid', 'id');
    }
    
}