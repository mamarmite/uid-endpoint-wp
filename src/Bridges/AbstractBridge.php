<?php

namespace Mamarmite\UIDEndpoint\Bridges;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class AbstractBridge
{
    protected array $from;
    protected array $to;

    function __construct() {
        if (!empty($this->from)) {
            $this->to = array_flip($this->from);
        }
    }

    public function to($from) {
        return $this->to[$from];
    }
    public function from($to) {
        return $this->from[$to];
    }
}
