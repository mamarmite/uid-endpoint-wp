<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class ArtistBlueprint extends Blueprint {
    function __construct($fields = []) {
        $this->default_fields = [
            "alternateName" => true,
            "url" => true,
            "additionalType" => true,
            "inLanguage" => true,
            "disambiguatingDescription" => true,
            "mainEntityOfPage" => true,
            "sameAs" => true,
            "hasOccupation" => true,
            "address" => true,
            "image" => true
        ];

        if (!empty($fields) && $fields !== ["all"]) {
            return parent::__construct($fields);
        }
        return parent::__construct($this->default_fields);
    }
}
