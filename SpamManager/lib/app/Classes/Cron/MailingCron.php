<?php

namespace WHMCS\Module\Addon\SpamManager\app\Classes\Cron;

use WHMCS\Module\Addon\SpamManager\app\Models\EmailQueue;

class MailingCron
{
    public function setRunningFile()
    {
        file_put_contents(__DIR__ . '/../../../../cron/cronrunning', 'cron ran at ' . date('Y-m-d H:i:s'));
    }
    public function delRunningFile()
    {
        @unlink(__DIR__ . '/../../../../cron/cronrunning');
    }
    public function run()
    {
        $this->setRunningFile();
        $queue = EmailQueue::with(['lists.template'])->notsent()->take(500)->get();
        try {
            foreach ($queue as $queueItem) {
                $this->processItem($queueItem);
            }
        } catch (\Exception $e) {

            \logActivity('Spam Manager cron error: ' . $e->getMessage());
        } finally {
            $this->delRunningFile();
        }
    }
    private function processItem($item)
    {
        $result = \sendMessage($item->lists->template->name, $item->hid);
        if ($result === true) {
            $item->sent = 1;
            $item->date_sent = gmdate('Y-m-d H:i:s');
            $item->save();
        } elseif ($result != '' && $result !== false) {
            $item->error = $result;
            $item->sent = 0;
            $item->save();
        }
    }
}
