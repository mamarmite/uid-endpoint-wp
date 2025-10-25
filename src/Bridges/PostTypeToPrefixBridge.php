<?php

namespace Mamarmite\UIDEndpoint\Bridges;

use Mamarmite\UIDEndpoint\Bridges\AbstractBridge;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class PostTypeToPrefixBridge extends AbstractBridge
{
    protected array $from = [
        'organization' => "o",
        'artiste' => "a",
        'place' => "p",
        'event' => "e",
        'creative_work' => "c",
    ];

    protected array $to = [];

    function __construct() {
        parent::__construct();
    }

}
