<?php

namespace WHMCS\Module\Addon\ChatManager\app\Controllers\API;

use WHMCS\Module\Addon\ChatManager\app\Controllers\API;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Models\Tags as Tag;
use WHMCS\Module\Addon\ChatManager\app\Models\ReviewOrder;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;
use WHMCS\Module\Addon\ChatManager\app\Classes\Logs;

class Orders extends API
{
    public function get()
    {
        if (!$_GET['tid']) {
            return ['result' => 'error', 'msg' => 'Thread id cannot be empty'];
        }
        $reviews = ReviewOrder::with('doer')->where('threadid', (int)trim($_GET['tid']))->get();
        return ['result' => 'success', 'data' => $reviews];
    }
    public function post()
    {
        switch ($this->input['a']) {
            case 'CheckOrderID':
                $id = (int)$this->input['order'];
                $threadid = (int)$this->input['threadid'];
                $order = Threads::where('orderid', $id)->where('id', '<>', $threadid)->first();
                if ($order) {
                    return 'This Order is already assigned to chat ' . $order->threadid;
                } else {
                    return 'success';
                }
                break;
            case 'AcceptSuggestion':
                $id = (int)$this->input['entry'];
                if ($id) {
                    $reviewOrder = ReviewOrder::with('doer')->where('id', $id)->first();
                    if ($reviewOrder) {
                        $fieldToUpdate = $reviewOrder->invoice == 1 ? 'invoiceid' : 'orderid';
                        $fieldToRemove = $reviewOrder->invoice == 1 ? 'orderid' :  'invoiceid';
                        if (Threads::where('id', $reviewOrder->threadid)->update([$fieldToUpdate => $reviewOrder->orderid, $fieldToRemove => 'null'])) {
                            Threads::where('id', $reviewOrder->threadid)->update([$fieldToRemove => NULL]);
                            ReviewOrder::where('threadid', $reviewOrder->threadid)->delete();
                            if ($reviewOrder->invoice == 1) {
                                Logs::ApproveInvoiceReview($reviewOrder, $_SESSION['adminid']);
                            } else {
                                Logs::ApproveOrderReview($reviewOrder, $_SESSION['adminid']);
                            }
                            return ['result' => 'success', 'orderid' => $reviewOrder->orderid];
                        } else {
                            return 'Something went wrong when updating order id';
                        }
                    } else {
                        return 'Cannot find this order suggestion';
                    }
                }
                break;
            case 'DeclineSuggestion':
                $id = (int)$this->input['entry'];
                if ($id) {
                    $reviewOrder = ReviewOrder::with('doer')->where('id', $id)->first();
                    if ($reviewOrder) {
                        if ($reviewOrder->invoice == 1) {
                            Logs::DeclineInvoiceReview($reviewOrder, $_SESSION['adminid']);
                        } else {
                            Logs::DeclineOrderReview($reviewOrder, $_SESSION['adminid']);
                        }
                        ReviewOrder::where('id', $id)->delete();
                        return 'success';
                    }
                    return 'Cannot find this order suggestion';
                }
                return 'Incorrect or expired id';
                break;
        }
    }
}
