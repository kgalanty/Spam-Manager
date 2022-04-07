<?php

namespace WHMCS\Module\Addon\ChatManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class Note extends Model
{
    public $timestamps = false;
    protected $table = 'tblnotes';
    //protected $fillable = ['id', 'statsjson', 'created_at'];

}