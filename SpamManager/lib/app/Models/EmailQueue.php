<?php

namespace WHMCS\Module\Addon\SpamManager\app\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{
    public $timestamps = false;
    protected $table = 'spam_emailqueue';
    //protected $visible = ['id','type', 'name', 'subject', 'message', 'regdate', 'domain', 'product'];
    public function setAsSent(array $where = [])
    {
        if ($where) {
            self::where($where)->update(['sent' => '1']);
        }
    }

    public function scopeNotsent($query)
    {
        return $query->where('sent', '0');
    }

    public function scopeFilterList($query, $val)
    {
        return $query->where('list', $val);
    }

    public function service()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\Service', 'hid', 'id');
    }
    public function lists()
    {
        return $this->belongsTo('\WHMCS\Module\Addon\SpamManager\app\Models\QueuesList', 'list', 'id');
    }
}
