<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;

class ClientEmail implements IThreadEditField
{
    public static function handle($field, $threaddata)
    {
       // if($field == null) return;

        if($field != $threaddata->email)
        {
            $oldemail = strlen($threaddata->email) == 0 ? '""' : $threaddata->email;
            $field = strlen($field) == 0 ? '""' : $field;
            return 'Client E-mail: '. $oldemail.'=>'.$field;
        }

    }
}