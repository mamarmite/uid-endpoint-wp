<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class ScheduleBlueprint extends Blueprint {
    function __construct($fields = []) {
        $this->default_fields = [
            "startDate" => true,
            "endDate" => true,
            "startTime" => true,
            "endTime" => true,
            "byDay" => true,
            "repeatFrequency" => true,
            "scheduleTimezone" => true,
            "duration" => true,
            "byMonthWeek" => true,
            "byMonthDay" => true,
            "byMonth" => true,
            "repeatCount" => true,
            "exceptDate" => true,
        ];

        if (!empty($fields) && $fields !== ["all"]) {
            return parent::__construct($fields);
        }
        return parent::__construct($this->default_fields);
    }
}
