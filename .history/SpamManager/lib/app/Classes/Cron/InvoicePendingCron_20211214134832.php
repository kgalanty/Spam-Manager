<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes\Cron;

use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Module\Addon\ChatManager\app\Models\Tags;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;
use WHMCS\Module\Addon\ChatManager\app\Classes\TagsLog;

class InvoicePendingCron
{
    public $toAdd;
    public function __construct()
    {
        $this->toAdd = [];
    }
    private function getThreads()
    {
        $t = Threads::whereHas('order.invoice', function ($q) {
            $q->where('status', 'Paid');
        })
            ->whereHas('tags', function ($q) {
                $q->whereIn('tag', ['wcb']);
            })
            ->whereDoesntHave('tags', function ($q) {
                $q->whereIn('tag', ['convertedsale']);
            })

            ->whereNotNull('orderid')->get();
        return $t;
    }
    private function processThreads($threads = [])
    {
        foreach ($threads as $thread) {
            $this->singleThreadHandler($thread);
        }
    }
    public function run()
    {
        $threads = $this->getThreads();
        if ($threads) {
            $this->processThreads($threads);
        }
        if ($this->toAdd) {
            Tags::insert($this->toAdd);
        }
        //$unpaidThreads = $this->getUnpaidThreads();
    }
    private function singleThreadHandler($thread)
    {
        $currentEntry = ['t_id' => $thread->id, 'tag'=>'convertedsale', 'approved' => 1, 'proposed_deletion' => 0];
        $this->toAdd[] = $currentEntry;
        $pendingTag = Tags::thread($thread->id)->tag('pending')->first();
        if($pendingTag)
        {
            Tags::thread($thread->id)->tag('pending')->delete();
            Logs::DelTag($thread->id, 1, 'pending');
            TagsLog::Delete($pendingTag->t_id, 'pending');
        }
        
        Logs::LogTagsAdd($thread->id, 1, [$currentEntry]);
        TagsLog::AddedByCron($thread->id, 'convertedsale');


    }
}
