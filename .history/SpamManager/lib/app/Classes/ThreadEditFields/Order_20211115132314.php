<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;

class Order implements IThreadEditField
{
    public static function handle($field, $threaddata)
    {
      //  if($field == null) return;
     
        if($field != $threaddata->orderid)
        {
            $oldorderid = strlen($threaddata->orderid) == 0 || $threaddata->orderid == null ? '""' : $threaddata->orderid;
            $newfield = $field == null || strlen(trim($field)) == 0 ? '""' : $field;
            return 'Order: '. $oldorderid.'=>'.$newfield;
        }
    }
}