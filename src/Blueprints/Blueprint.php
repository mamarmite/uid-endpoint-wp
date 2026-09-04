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

    public function context_exists(string $context): bool {
        return array_key_exists($context, $this->fields) && is_array($this->fields[$context]);
    }

    public function field_exists(string $field, string $context = CLIENT_CONTEXT_DEFAULT): bool {
        return array_key_exists($field, $this->fields[$context]);
    }

    public function field_exists_in_context(string $field, string $context = CLIENT_CONTEXT_DEFAULT): bool {
        return $this->context_exists($context) && $this->field_exists($field, $context);
    }

    public function is_allowed(string $field, string $context = CLIENT_CONTEXT_DEFAULT): bool {
        if ($this->field_exists_in_context($field, $context)) {
            $field_value = $this->fields[$context][$field];
            return $field_value === true
                || (is_array($field_value) && !empty($field_value));
            //$field_value can be equal to ["all"] or to a sub array with subEntity properties.
        }
        return false;
    }

}
