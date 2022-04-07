<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    public $timestamps = false;
    protected $table = 'tblproducts';
    protected $visible = ['id','type', 'name', 'gid'];
    public function group()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\ProductGroup', 'gid', 'id');
    }
}