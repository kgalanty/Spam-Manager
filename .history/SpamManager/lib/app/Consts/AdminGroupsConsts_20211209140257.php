<?php

namespace WHMCS\Module\Addon\ChatManager\app\Consts;

class AdminGroupsConsts
{
    public const AGENT = [2,22];
    public const ADMIN = [1, 11];

    /*
    Below is the list of Admin IDs disallowed from access the module
    */
    public const AGENT_DISALLOWED = [272];
    public const CRON_ADMIN = 1;
    /*
    * * Admin ID => Tag
    * Used to display other operator's tags for operator whose tag is present - without edition permission
    */
    public const TAGSAGENTMAP = [153 => 'georgistatev',
     216 => 'Elvira', 
     238=>'deni', 
     230=>'deni', 
     213=>'Ivaylo', 
     263=>'AlexP', 
     267=>'Emily',
    ];
}
