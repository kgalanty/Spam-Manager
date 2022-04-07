<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Invoice extends Model
{
    public $timestamps = false;
    protected $table = 'tblinvoices';
    //protected $visible = ['id','userid', 'orderid', 'packageid', 'server', 'regdate', 'domain'];
    // public function invoice()
    // {
    //     return $this->belongsTo('\WHMCS\Module\Addon\ChatManager\app\Models\ProductGroup', 'gid', 'id');
    // }

}