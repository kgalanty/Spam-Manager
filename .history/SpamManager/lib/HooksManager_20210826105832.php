<?php
namespace WHMCS\Module\Addon\ChatManager;
use WHMCS\Database\Capsule as DB;

class HooksManager
{
    public static function run()
    {

        foreach(glob(__DIR__.'/app/Hooks/*.php') as $file)
        {

            $hookfile = include($file);

            add_hook(basename($file, '.php'), 3, $hookfile);    
        }
    }
}