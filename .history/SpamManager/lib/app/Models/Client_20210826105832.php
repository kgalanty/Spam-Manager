<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Client extends Model
{
    public $timestamps = false;
    protected $table = 'tblclients';
    protected $visible = ['id','firstname', 'lastname', 'email', 'companyname','groupid'];
    public function clientgroup()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\ChatManager\app\Models\Clientgroup', 'groupid', 'id');
    }

}