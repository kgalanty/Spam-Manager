<?php

namespace WHMCS\Module\Addon\SpamManager\app\Consts;

class AdminGroupsConsts
{
    public const AGENT = [2,22];
    public const ADMIN = [1, 11];

    /*
    Below is the list of Admin IDs disallowed from access the module
    */
    public const AGENT_DISALLOWED = [272];
    public const CRON_ADMIN = 1;
}
