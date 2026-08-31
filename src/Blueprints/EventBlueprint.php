<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class EventBlueprint extends Blueprint {
    function __construct($fields = []) {
        parent::__construct([
            "startDate" => true,
            "endDate" => true,
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "keywords" => true,
            "eventStatus" => true,
            "inLanguage" => true,
            "eventAttendanceMode" => true,
            "isAccessibleForFree" => true,
            "mainEntityOfPage" => true,
            "additionalType" => true,
            "eventSchedule" => [
                "all"
            ],
            "location" => [
                "all"
            ],
            "organizer" => [
                "all"
            ],
            "workFeatured" => [
                "all"
            ],
            "image" => [
                "all"
            ],
            "performer" => [
                "alternateName" => true,
                "sameAs" => true,
            ],
            "contributor" => [
                "alternateName" => true,
                "sameAs" => true,
            ]
        ]);
    }
}
