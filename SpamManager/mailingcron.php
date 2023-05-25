<?php
require_once(__DIR__.'/../../../init.php');
// if(file_exists(__DIR__.'/vendor/autoload.php') === true)
// {
//     require_once(__DIR__.'/vendor/autoload.php');
// }
if(file_exists(__DIR__.'/cron/cronrunning'))
{
	exit;
}
use WHMCS\Module\Addon\SpamManager\app\Classes\Cron\MailingCron;
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
$cron = new MailingCron();
$cron->run();
