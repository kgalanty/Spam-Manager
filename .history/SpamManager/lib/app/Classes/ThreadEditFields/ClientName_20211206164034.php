<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;

class ClientName implements IThreadEditField
{
    public static function handle($field, $threaddata)
    {
       // if($field == null) return;

        if(($field != $threaddata->name && $field != $threaddata->customer->name) || $field == '')
        {
            $field = strlen($field) == 0 ? '""' : $field;
            $name = !is_null($threaddata->name) ? $threaddata->name : $threaddata->customer->name;
            $name = strlen($name) == 0 ?  '""' : $name;
            return 'Client: '. $name.'=>'.$field;
        }

    }
}