<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class OrganisationBlueprint extends Blueprint {
    function __construct($fields = []) {
        parent::__construct([
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "sameAs" => true,
            "image" => true
        ]);
    }
}
