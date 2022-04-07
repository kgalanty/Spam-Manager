<?php
namespace WHMCS\Module\Addon\ChatManager\app;
use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Consts\moduleVersion;

class Addon
{
    public static function config()
    {
        return [
            // Display name for your module
            'name' => 'Chat Manager',
            // Description displayed within the admin interface
            'description' => '',
            // Module author name
            'author' => 'TMD',
            'version' => moduleVersion::VERSION,
        ];
    }
    public static function activate()
    {
      DB::statement('
      	
        CREATE TABLE `chat_manualpoints` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `userid` int(11) NOT NULL,
          `author` int(11) NOT NULL,
          `points` int(11) NOT NULL,
          `comment` text COLLATE utf8mb4_unicode_ci,
          `date` date NOT NULL,
          `created_at` datetime NOT NULL,
          PRIMARY KEY (`id`),
          KEY `userid` (`userid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      ');
      DB::statement('
      CREATE TABLE IF NOT EXISTS `chat_completedorders` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `lcvisitorid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        `lcchatid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
        `ordernumber` int(11) NOT NULL,
        `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
        PRIMARY KEY (`id`),
        KEY `lc` (`lcvisitorid`),
        KEY `o` (`ordernumber`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
      ');
        DB::statement('
       CREATE TABLE IF NOT EXISTS `chat_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `geolocation` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
DB::statement("
CREATE TABLE IF NOT EXISTS `chat_followup` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `threadid` int(11) NOT NULL,
    `followupdate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `doer` int(11) NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
  DB::statement(" CREATE TABLE IF NOT EXISTS `chat_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `itemid` int(11) NOT NULL,
    `itemclass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `doer` int(11) NOT NULL,
    `desc` text COLLATE utf8mb4_unicode_ci,
    `created_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
  	
    DB::statement("CREATE TABLE IF NOT EXISTS `chat_reviewduplicatedorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `threadid` int(11) NOT NULL,
  `doer` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `threadid` (`threadid`),
  KEY `doer` (`doer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

	
DB::statement("CREATE TABLE IF NOT EXISTS `chat_revieworders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orderid` int(11) NOT NULL,
  `threadid` int(11) NOT NULL,
  `invoice` char(1) COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `sender` int(11) NOT NULL,
  `created_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `threadid` (`threadid`),
  KEY `sender` (`sender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

DB::statement("CREATE TABLE IF NOT EXISTS `chat_reviewthreads` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `threadid` int(11) NOT NULL,
    `sender` int(11) NOT NULL,
    `comment` text COLLATE utf8mb4_unicode_ci,
    `pending` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `threadid` (`threadid`),
    KEY `sender` (`sender`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
  	
    DB::statement("CREATE TABLE IF NOT EXISTS `chat_staffonline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

DB::statement("CREATE TABLE IF NOT EXISTS `chat_taghistory` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `thread_id` int(11) NOT NULL,
    `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `doer` int(11) NOT NULL,
    `action` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
  DB::statement("CREATE TABLE IF NOT EXISTS `chat_tags` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `t_id` int(11) NOT NULL,
    `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `approved` char(1) COLLATE utf8mb4_unicode_ci DEFAULT '0',
    `proposed_deletion` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `t` (`t_id`),
    KEY `tag` (`tag`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
  DB::statement("CREATE TABLE IF NOT EXISTS `chat_threads` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `chatid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `threadid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `users` longtext COLLATE utf8mb4_unicode_ci,
    `domain` text COLLATE utf8mb4_unicode_ci,
    `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `orderid` int(11) DEFAULT NULL,
    `agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `notes` text COLLATE utf8mb4_unicode_ci,
    `customoffer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    KEY `chatid` (`chatid`),
    KEY `threadid` (`threadid`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ");
//     DB::statement('CREATE TABLE IF NOT EXISTS `kg_invoiceprolong` (
//     `id` int(10) NOT NULL AUTO_INCREMENT,
//     `invoiceid` int(10) NOT NULL,
//     `relid` int(10) DEFAULT NULL,
//     PRIMARY KEY (`id`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci');
// 	DB::statement('CREATE TABLE IF NOT EXISTS `kg_customoffers` (
//     `id` int(10) NOT NULL AUTO_INCREMENT,
//     `offer` text COLLATE utf8_unicode_ci NOT NULL,
//     PRIMARY KEY (`id`)
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

//        ');
            return [
                'status' => 'success', 
                'description' => 'The module has been successfuly activated.',
            ];
        }
        public static function deactivate()
        {
            return [
                'status' => 'success', 
                'description' => 'The module has been successfuly deactivated.',
            ];
        }
        public static function upgrade()
        {
        }
    }