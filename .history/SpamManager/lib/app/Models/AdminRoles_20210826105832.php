<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class AdminRoles extends Model
{
    public $timestamps = false;
    protected $table = 'tbladminroles';
    //protected $fillable = ['name','ip'];
    protected $visible = ['id','name'];
  
}