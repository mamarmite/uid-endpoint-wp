<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class MediaBlueprint extends Blueprint {
    function __construct($fields = []) {
        $this->default_fields = [
            "url" => true,
            "usageInfo" => true,
            "disambiguatingDescription" => true,
            "description" => true,
            "sdDatePublished" => true,
            "inLanguage" => true,
        ];

        if (!empty($fields) && $fields !== ["all"]) {
            return parent::__construct($fields);
        }
        parent::__construct($this->default_fields);
    }
}
