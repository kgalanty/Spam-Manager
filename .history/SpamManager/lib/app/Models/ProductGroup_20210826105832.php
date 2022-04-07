<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class ProductGroup extends Model
{
    public $timestamps = false;
    protected $table = 'tblproductgroups';
    //protected $visible = ['id','userid', 'orderid', 'packageid', 'server', 'regdate', 'domain'];

}