<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

class DateTimeHelper
{
    //moment().format('Z')
    public static function NowUTC()
    {
        return gmdate('Y-m-d H:i:s');
    }
    public static function subDate(string $timezone = '+03:00', \DateInterval $interval = null): \DateTime
    {
        if ($interval === null) {
            $interval = new \DateInterval('PT1H');
        }
        $now = new \DateTime($timezone);
        return $now->sub($interval);
    }
    public static function convertDateToUTC(string $currentTz, string $datetime, string $format = "Y-m-d H:i:s")
    {
        return (new \DateTime($datetime, new \DateTimeZone($currentTz)))
            ->setTimezone(new \DateTimeZone('UTC'))
            ->format($format);
    }
    public static function convertFromUTCToTZ(string $timezone, string $datetime, string $format = 'Y-m-d H:i:s')
    {
        return (new \DateTime($datetime, new \DateTimeZone('UTC')))
            ->setTimezone(new \DateTimeZone($timezone))
            ->format($format);
    }
    public static function setFormat($datetime, $format)
    {
        return (new \DateTime($datetime))->format($format);
    }
}
