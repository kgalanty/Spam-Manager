<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;

class Invoice implements IThreadEditField
{
    public static function handle($field, $threaddata)
    {
      //  if($field == null) return;
     
        if($field != $threaddata->invoiceid)
        {
            $oldorderid = strlen($threaddata->invoiceid) == 0 || $threaddata->invoiceid == null ? '""' : $threaddata->invoiceid;
            $newfield = $field == null || strlen(trim($field)) == 0 ? '""' : $field;
            return 'Invoice: '. $oldorderid.'=>'.$newfield;
        }
    }
}