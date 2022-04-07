<?php

namespace WHMCS\Module\Addon\SpamManager\app\Classes\Cron;

use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;

class MailingCron
{
    public function run()
    {
        $queue = EmailQueue::notsent()->get();

        foreach ($queue as $queueItem) {
            $this->processItem($queueItem);
        }
    }
    private function processItem($item)
    {
        echo('<pre>'); var_dump($item); die;
        $result = \sendMessage($item->tplname, $item->hid);
        if ($result === true) {
            $item->sent = 1;
            $item->save();
        }
        elseif($result != '' && $result !== false)
        {
            $item->error = $result;
            $item->sent = 0;
            $item->save();
        }
    }
}
