<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
class Agent implements IThreadEditField
{
    public static function handle($newfieldvalue, $threaddata)
    {
        if($newfieldvalue == null || $newfieldvalue == '')
        return;

        if((int)$newfieldvalue != $threaddata->agent)
        {
            $oldvalueDoer = $threaddata->agent > 0 ? Admin::find($threaddata->agent) : '""';
            $oldvalue = is_object($oldvalueDoer) ? $oldvalueDoer->firstname.' '.$oldvalueDoer->lastname : $oldvalueDoer;

            $newvalueDoer = Admin::find($newfieldvalue);
            $updateitem = $newvalueDoer->firstname.' '.$newvalueDoer->lastname;

            return 'Agent: '.$oldvalue.'=>'.$updateitem;
        }
    }
}