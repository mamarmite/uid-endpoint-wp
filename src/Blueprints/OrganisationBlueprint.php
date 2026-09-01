<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class OrganisationBlueprint extends Blueprint {
    function __construct($fields = []) {
        $this->default_fields = [
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "sameAs" => true,
            "image" => true
        ];

        if (!empty($fields) && $fields !== ["all"]) {
            return parent::__construct($fields);
        }
        return parent::__construct($this->default_fields);
    }
}
