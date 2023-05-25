<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;

class QueuesList extends Model
{
    public $timestamps = false;
    protected $table = 'spam_queueslist';
    //protected $visible = ['id','type', 'name', 'subject', 'message', 'regdate', 'domain', 'product'];
    protected $fillable = ['templateid', 'adminid', 'servers', 'statuses', 'date', 'config'];

    public function scopeNotsent($query)
    {
        return $query->where('sent', '0');
    }
    public function admin()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\Admin', 'adminid', 'id');
    }
    public function template()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\EmailTemplates', 'templateid', 'id');
    }
    public function emails()
    {
        return $this->hasMany('\WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue', 'list', 'id');
    }
}
