<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditFields;

interface IThreadEditField
{
    public static function handle($field, $threaddata);
}