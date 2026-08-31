<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class CreativeWorkBlueprint extends Blueprint {
    function __construct($fields = []) {
        parent::__construct([
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "disambiguatingDescription" => true,
            "mainEntityOfPage" => true,
            "creator" => [
                "alternateName" => true,
                //"url" => true,
                //"additionalType" => true,
                "sameAs" => true,
            ],
            "image" => [//all except the usageInfo
                "url" => true,
                "disambiguatingDescription" => true,
                "sdDatePublished" => true,
            ]
        ]);
    }
}
