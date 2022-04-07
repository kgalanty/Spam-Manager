<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Clientgroup extends Model
{
    //public $timestamps = false;
    protected $table = 'tblclientgroups';
    protected $visible = ['id','groupname'];
}