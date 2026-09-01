<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class PlaceBlueprint extends Blueprint {
    function __construct($fields = []) {
        $this->default_fields = [
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "address" => true,
            "sameAs" => true,
            "image" => true
        ];

        if (!empty($fields) && $fields !== ["all"]) {
            return parent::__construct($fields);
        }
        parent::__construct($this->default_fields);
    }
}
