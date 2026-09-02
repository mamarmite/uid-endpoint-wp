<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class AddressBlueprint extends Blueprint {
    function __construct($fields = []) {
        $this->default_fields = [
            "streetAddress" => true,
            "addressLocality" => true,
            "addressRegion" => true,
            "postalCode" => true,
            "addressCountry" => true
        ];

        if (!empty($fields) && $fields !== ["all"]) {
            return parent::__construct($fields);
        }
        return parent::__construct($this->default_fields);
    }
}
