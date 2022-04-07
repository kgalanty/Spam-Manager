<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;

class Notes implements IThreadEditField
{
    public static function handle($field, $threaddata)
    {
        if($field == null) return;

        if($field != $threaddata->notes)
        {
            $oldnotes = strlen($field) == 0 ? '""' : $field;
            return 'Notes: '. $oldnotes.'=>'.$field;
        }
    }
}