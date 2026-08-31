<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class ArtistBlueprint extends Blueprint {
    function __construct($fields = []) {
        parent::__construct([
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
        ]);
    }
}
