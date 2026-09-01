<?php

namespace Mamarmite\UIDEndpoint\Blueprints;

use const Mamarmite\UIDEndpoint\CLIENT_CONTEXT_DEFAULT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Blueprint {

    public string $current_context;

    protected $fields;
    protected $default_fields;

    function __construct($fields = []) {
        $this->current_context = CLIENT_CONTEXT_DEFAULT;

        $this->fields = [$this->current_context => $fields];
    }

    public function set_context(string $context = CLIENT_CONTEXT_DEFAULT, array $fields = []): void {
        $this->current_context = $context;
        //overrides the context if set for the same string value.
        $this->fields[$context] = $fields;
    }

    public function allow_list(string $context = CLIENT_CONTEXT_DEFAULT): array {
        return $this->fields[$context] ?? [];
    }

    public function get_embed_fields(string $sub_entity, string $context = CLIENT_CONTEXT_DEFAULT, ): array {
        return $this->fields[$context][$sub_entity] ?? [];
    }

}
