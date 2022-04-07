<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;
class EmailTemplates
{
    public $timestamps = false;
    protected $table = 'tblemailtemplates';
    //protected $visible = ['id','type', 'name', 'subject', 'message', 'regdate', 'domain', 'product'];
    public function scopeSpamManager($query)
    {
        return $query->where('type', 'LIKE', 'spam_%');
    }
    
}