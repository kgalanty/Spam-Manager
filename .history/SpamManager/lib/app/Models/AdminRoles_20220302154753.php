<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class AdminRoles extends Model
{
    public $timestamps = false;
    protected $table = 'tbladminroles';
    //protected $fillable = ['name','ip'];
    protected $visible = ['id','name'];
  
}