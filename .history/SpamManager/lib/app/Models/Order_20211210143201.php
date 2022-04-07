<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;
use WHMCS\Module\Addon\ChatManager\app\Models\Service;
use Illuminate\Database\Eloquent\Model;
use WHMCS\Module\Addon\ChatManager\app\Models\Client;
class Order extends Model
{
    public $timestamps = false;
    protected $table = 'tblorders';
    //protected $visible = ['id','userid', 'orderid', 'packageid', 'server', 'regdate', 'domain'];
    public function invoice()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\ChatManager\app\Models\Invoice', 'invoiceid', 'id');
    }
    public function service()
    {
        return $this->hasMany(Service::class, 'orderid', 'id');
    }
    public function domain()
    {
        return $this->hasMany(Domain::class, 'orderid', 'id');
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'userid', 'id');
    }
}