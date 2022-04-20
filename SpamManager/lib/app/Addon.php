<?php

namespace WHMCS\Module\Addon\SpamManager\app;

use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\SpamManager\app\Consts\moduleVersion;

class Addon
{
    public static function config()
    {
        return [
            // Display name for your module
            'name' => 'Spam Manager',
            // Description displayed within the admin interface
            'description' => '',
            // Module author name
            'author' => 'TMD',
            'version' => moduleVersion::VERSION,
        ];
    }
    public static function activate()
    {
        DB::statement("
      	
        CREATE TABLE  IF NOT EXISTS `spam_emailqueue` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`tplname` TEXT NOT NULL COLLATE 'utf8_unicode_ci',
	`hid` INT(10) NOT NULL,
	`sent` CHAR(1) NOT NULL DEFAULT '0' COLLATE 'utf8_unicode_ci',
	`adminid` INT(10) NULL DEFAULT NULL,
	`date` DATETIME NULL DEFAULT NULL,
	`error` TEXT NULL DEFAULT NULL COLLATE 'utf8_unicode_ci',
	PRIMARY KEY (`id`) USING BTREE,
	INDEX `hid` (`hid`) USING BTREE
    )
    COLLATE='utf8_unicode_ci'
    ENGINE=InnoDB
    AUTO_INCREMENT=73
    ;

       ");
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
