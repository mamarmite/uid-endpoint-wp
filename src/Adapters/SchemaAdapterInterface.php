<?php
namespace Mamarmite\UIDEndpoint\Adapters;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

/**
 * Interface SchemaAdapterInterface
 * Defines the contract for all schema adapters
 */
interface SchemaAdapterInterface
{
    /**
     * Transform WordPress post data to schema.org format
     *
     * @return array The schema.org formatted data
     */
    public function transform(): array;

    /**
     * Get the schema.org type
     *
     * @return string
     */
    public function get_schema_type(): string;

    /**
     * Validate the schema data
     *
     * @param array $data The schema data to validate
     * @return bool
     */
    public function validate(array $data): bool;
}
