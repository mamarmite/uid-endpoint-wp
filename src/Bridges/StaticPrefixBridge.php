<?php

namespace Mamarmite\UIDEndpoint\Bridges;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class StaticPrefixBridge extends AbstractBridge
{
    protected $_prefix = MAMARMITE_UID_PREFIX;
    protected array $from = [];
    protected array $to = [];

    function __construct() {
        $this->from = [
            'organization' => $this->_prefix,
            'artiste' => $this->_prefix,
            'place' => $this->_prefix,
            'event' => $this->_prefix,
            'programmation' => $this->_prefix,
            'creative_work' => $this->_prefix,
            'production' => $this->_prefix,
        ];
        parent::__construct();
    }

}
