<?php

namespace WHMCS\Module\Addon\SpamManager\app\Classes\ThreadEditFields;

interface IThreadEditField
{
    public static function handle($field, $threaddata);
}