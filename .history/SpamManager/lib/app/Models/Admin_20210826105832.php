<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Admin extends Model
{
    public $timestamps = false;
    protected $table = 'tbladmins';
    //protected $fillable = ['name','ip'];
    protected $visible = ['id','firstname', 'lastname', 'email', 'username'];
    public function role()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\ChatManager\app\Models\AdminRoles', 'roleid', 'id');
    }
}