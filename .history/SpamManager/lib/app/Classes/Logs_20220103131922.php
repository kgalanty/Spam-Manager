<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Module\Addon\ChatManager\app\Models\TagHistory;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Logs as LogsModel;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewThread as ReviewThreadModel;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewOrder;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads as ThreadsModel;
use WHMCS\Module\Addon\ChatManager\app\Classes\ThreadEditDiffLogs;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
class Logs
{
    private static function log(int $itemid, string $itemclass, int $doer, string $desc = '')
    {
        LogsModel::create(
            [
                'itemid' => $itemid,
                'itemclass' => $itemclass,
                'doer' => $doer,
                'desc' => $desc,
                'created_at' => gmdate('Y-m-d H:i:s')
            ]
        );
    }
    public static function CreatedByID($threadid, $doer)
    {
        self::log($threadid, 'Thread', $doer, 'Created manually');
    }
    public static function SendToReview($itemid, $doer)
    {
        self::log($itemid, 'Thread', $doer, 'Sent new review');
    }
    public static function MarkAsReviewed($itemid, $doer, $reviewitem)
    {
        $reviewitem = ReviewThreadModel::with('doer')->id($reviewitem)->first();
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' has reviewed a comment from ' . $reviewitem->doer->firstname . ' ' . $reviewitem->doer->lastname . ' (' . $reviewitem->comment . ')';
        self::log($itemid, 'Thread', $doer, $desc);
    }
    public static function ProposeNewTag($itemid, $doer, $tag)
    {
        //$reviewitem = ReviewThreadModel::with('doer')->id($reviewitem)->first();
        $admin = Admin::find($doer);
        $thread = ThreadsModel::find($itemid)->chatid;
        $desc = $admin->firstname . ' ' . $admin->lastname . ' propsed to add new tag ' . $tag . ' to chat ' . $thread;
        self::log($itemid, 'Thread', $doer, $desc);
    }
    public static function ProposeDelTag($itemid, $doer, $tag)
    {
        $admin = Admin::find($doer);
        $thread = ThreadsModel::find($itemid)->chatid;
        $desc = $admin->firstname . ' ' . $admin->lastname . ' proposed removal of tag ' . $tag . ' in chat ' . $thread;
        self::log($itemid, 'Thread', $doer, $desc);
    }
    public static function AddTag($itemid, $doer, $tag)
    {
        $admin = Admin::find($doer);
        $thread = ThreadsModel::find($itemid)->chatid;
        $desc = $admin->firstname . ' ' . $admin->lastname . ' approved new tag ' . $tag . ' to chat ' . $thread;
        self::log($itemid, 'Thread', $doer, $desc);
    }
    public static function DeclineProposeTagDeletion($itemid, $doer, $tag)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' declined removal of tag ' . $tag;
        self::log($itemid, 'Thread', $doer, $desc);
    }
    public static function DelTag(int $itemid, int $doer, string $tag)
    {
        $admin = Admin::find($doer);
        $thread = ThreadsModel::find($itemid)->chatid;
        $desc = $admin->firstname . ' ' . $admin->lastname . ' deleted tag ' . $tag . ' in chat ' . $thread;
        self::log($itemid, 'Thread', $doer, $desc);
    }
    public static function updateThread($itemid, $doer, $update, $threaddata)
    {
        $changes = ThreadEditDiffLogs::process($itemid, $doer, $update, $threaddata);

        if ($changes) {
            $admin = Admin::find($doer);
            $desc = $admin->firstname . ' ' . $admin->lastname . ' has updated chat ' . $threaddata->threadid . ': ' . $changes;
            self::log($itemid, 'Thread', $doer, $desc);
        }
    }

    public static function FollowUp($threadid, $doer)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' has marked thread #' . $threadid . ' as followed up.';
        self::log($threadid, 'Thread', $doer, $desc);
    }
    public static function MatchedOrderByCron($thread)
    {
        $desc = 'Cron has matched the thread with order #'.$thread->orderid.' and domain '.$thread->domain;
        self::log($thread->id, 'Thread', 1, $desc);
    }
    public static function ApproveOrderReview( $ApproveOrderReview, int $doer)
    {
        $admin = Admin::find($doer);
        $pendingOrderCount = ReviewOrder::where('threadid', $ApproveOrderReview->threadid)->count() -1;
        $desc = $admin->firstname . ' ' . $admin->lastname . ' approved order id '. $ApproveOrderReview->orderid.' submitted by '.$ApproveOrderReview->doer->firstname.' '.$ApproveOrderReview->doer->lastname.($pendingOrderCount>0 ? ' and declined other '.$pendingOrderCount : '');
        self::log($ApproveOrderReview->threadid, 'Thread', $doer, $desc);
    }
    public static function ApproveInvoiceReview($ApproveInvoiceReview, int $doer)
    {
        $admin = Admin::find($doer);
        $pendingOrderCount = ReviewOrder::where('threadid', $ApproveInvoiceReview->threadid)->count() -1;
        $desc = $admin->firstname . ' ' . $admin->lastname . ' approved invoice id '. $ApproveInvoiceReview->orderid.' submitted by '.$ApproveInvoiceReview->doer->firstname.' '.$ApproveInvoiceReview->doer->lastname.($pendingOrderCount>0 ? ' and declined other '.$pendingOrderCount : '');
        self::log($ApproveInvoiceReview->threadid, 'Thread', $doer, $desc);
 
    }
    public static function submitInvoiceReview(int $invoiceid, int $doer, int $threadid)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' suggested new invoice id: '.$invoiceid.' for the thread.';
        self::log($threadid, 'Thread', $doer, $desc);
    }
    public static function submitOrderReview(int $order, int $doer, int $threadid)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' added new pending order id: '.$order.' for the thread.';
        self::log($threadid, 'Thread', $doer, $desc);
    }
    public static function DeclineOrderReview( $OrderReview, int $doer)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' declined order id '. $OrderReview->orderid.' submitted by '.$OrderReview->doer->firstname.' '.$OrderReview->doer->lastname.'.';
        self::log($OrderReview->threadid, 'Thread', $doer, $desc);
    }
    public static function DeclineInvoiceReview( $OrderReview, int $doer)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' declined invoice id '. $OrderReview->orderid.' submitted by '.$OrderReview->doer->firstname.' '.$OrderReview->doer->lastname.'.';
        self::log($OrderReview->threadid, 'Thread', $doer, $desc);
    }
    public static function AcceptDuplicateOrder(int $threadid, int $doer)
    {
        $admin = Admin::find($doer);
        $desc = $admin->firstname . ' ' . $admin->lastname . ' marked duplicated order id as reviewed.';
        self::log($threadid, 'Thread', $doer, $desc);
    }
    public static function LogTagsAdd(int $threadid, int $doer, array $tags = [])
    {
        $doer = $doer != 0 ? $doer : AdminGroupsConsts::CRON_ADMIN;
        $admin = Admin::find($doer);
        
        foreach($tags as $tag)
        {
            $desc = $doer < 2 ? 'Added `'.$tag['tag'].'` by cron' : $admin->firstname . ' ' . $admin->lastname . ' added the tag "'.$tag['tag'].'" by adding chat manually';
            self::log($threadid, 'Thread', $doer, $desc);
        }
       
    }
    public static function AddThreadByCron(int $threadid)
    {
        $desc = 'Added by cron';
        self::log($threadid, 'Thread', AdminGroupsConsts::CRON_ADMIN, $desc);
    }
    public static function AddOrderInCron(int $order, int $threadid)
    {
        $desc = 'Order ID set by cron: '.$order;
        self::log($threadid, 'Thread', AdminGroupsConsts::CRON_ADMIN, $desc);
    }
    public static function duplicatedPendingOrder(int $threadid, int $orderid)
    {
        $desc = 'Removed duplicated pending order: '.$orderid;
        self::log($threadid, 'Thread', AdminGroupsConsts::CRON_ADMIN, $desc);
    }
    public static function SetClientDataWhenEmpty(int $threadid)
    {
        $desc = 'Set client name and email based on the profile of related order';
        self::log($threadid, 'Thread', AdminGroupsConsts::CRON_ADMIN, $desc);
    }
    public static function AddManualPoints($author, $operator, $points, $comment, $date)
    {
        $operatorAdmin = Admin::find($operator);
        $authorAdmin = Admin::find($author);
        $desc = $authorAdmin->firstname.' '.$authorAdmin->lastname .' applied '. $points.' points to '.$operatorAdmin->firstname.' '.$operatorAdmin->lastname.'`s account with date '. $date.' and comment: '.$comment ;
        self::log($operator, 'Operator', $author, $desc);
    }
}
