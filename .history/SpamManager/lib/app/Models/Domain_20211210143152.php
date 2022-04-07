<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Domain extends Model
{
    public $timestamps = false;
    protected $table = 'tbldomains';
    protected $visible = ['id','userid', 'orderid', 'registrationdate',  'domain', 'status'];
    public function client()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\ChatManager\app\Models\Client', 'userid', 'id');
    }

}