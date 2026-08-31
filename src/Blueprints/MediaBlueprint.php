<?php
namespace Mamarmite\UIDEndpoint\Blueprints;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class MediaBlueprint extends Blueprint {
    function __construct($fields = []) {
        parent::__construct([
            "url" => true,
            "usageInfo" => true,
            "disambiguatingDescription" => true,
            "description" => true,
            "sdDatePublished" => true,
            "inLanguage" => true,
        ]);
    }
}
