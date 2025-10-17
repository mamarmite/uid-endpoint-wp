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
     * @param \WP_Post $post The WordPress post object
     * @return array The schema.org formatted data
     */
    public function transform(\WP_Post $post): array;

    /**
     * Get the schema.org type
     *
     * @return string
     */
    public function getSchemaType(): string;

    /**
     * Validate the schema data
     *
     * @param array $data The schema data to validate
     * @return bool
     */
    public function validate(array $data): bool;
}
