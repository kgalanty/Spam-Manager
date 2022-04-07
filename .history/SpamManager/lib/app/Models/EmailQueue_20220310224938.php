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
        if($where)
        {
            self::where($where)->update(['sent' => '1']);
        }
    }
    
}