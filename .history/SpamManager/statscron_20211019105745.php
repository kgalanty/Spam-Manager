<?php
require_once(__DIR__.'/../../../init.php');
if(file_exists(__DIR__.'/vendor/autoload.php') === true)
{
    require_once(__DIR__.'/vendor/autoload.php');
}

use WHMCS\Module\Addon\ChatManager\app\Classes\LiveChatHelper;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$livechat = new LiveChatHelper();
$livechat->readRecentChats(['tags' => ['values'=> ['sales']]]);
logActivity('Chat manager inserted '.$_SESSION['cmcount'].' rows');
unset($_SESSION['cmcount']);