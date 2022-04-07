<?php
namespace WHMCS\Module\Addon\SpamManager\app\Classes\Cron;

use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;

class MailingCron
{
    public function run()
    {
        $queue = EmailQueue::notsent()->get();
    }
}