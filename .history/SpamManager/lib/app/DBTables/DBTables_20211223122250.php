<?php

namespace WHMCS\Module\Addon\ChatManager\app\DBTables;

class DBTables
{
    //const EntriesCatTable = 'feed_entries_cat';
    const Prefix = 'chat_';
    const CompletedOrders = self::Prefix.'completedorders';
    const Customers = self::Prefix.'customers';
    const FollowUp = self::Prefix.'followup';
    const Logs = self::Prefix.'logs';
    const ReviewOrders = self::Prefix.'revieworders';
    const ReviewDuplicatedOrders = self::Prefix.'reviewduplicatedorders';
    const ReviewThreads = self::Prefix.'reviewthreads';
    const TagHistory = self::Prefix.'taghistory';
    const Tags = self::Prefix.'tags';
    const Threads = self::Prefix.'threads';
    const OrdersChats = self::Prefix.'completedorders';
    const StaffOnline = self::Prefix.'staffonline';
    const ManualPoints = self::Prefix.'manualpoints';
}