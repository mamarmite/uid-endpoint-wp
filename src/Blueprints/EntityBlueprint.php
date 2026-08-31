<?php

namespace Mamarmite\UIDEndpoint\Blueprints;

use const Mamarmite\UIDEndpoint\CLIENT_CONTEXT_DEFAULT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class EntityBlueprint {

    public $current_context;

    public $fields = [];

    function __construct($fields = []) {
        $this->current_context = CLIENT_CONTEXT_DEFAULT;
        $this->fields = [$this->current_context => $fields];
    }

    public function set_context(string $context = CLIENT_CONTEXT_DEFAULT, array $fields = []): void {
        $this->current_context = $context;
        //overrides the context if set for the same string value.
        $this->fields[$context] = $fields;
    }

    public function allow_list(): array {
        return [];
    }

}
