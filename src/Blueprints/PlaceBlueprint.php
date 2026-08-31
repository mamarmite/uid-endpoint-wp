<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class PlaceBlueprint extends Blueprint {
    function __construct($fields = []) {
        parent::__construct([
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "address" => true,
            "sameAs" => true,
            "image" => true
        ]);
    }
}
