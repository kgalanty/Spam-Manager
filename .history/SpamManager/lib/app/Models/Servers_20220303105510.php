<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Servers extends Model
{
    public $timestamps = false;
    protected $table = 'tblservers';
    protected $visible = ['id','name', 'ipaddress'];
}