<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Adapters\SchemaAdapterInterface;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class AbstractSchemaAdapter
 * Base adapter with common functionality
 */
abstract class AbstractSchemaAdapter implements SchemaAdapterInterface
{
    protected string $schemaType;
    protected string $context = 'http://schema.org';

    /**
     * Get ACF field value with fallback
     *
     * @param int $post_id
     * @param string $field_name
     * @param mixed $default
     * @return mixed
     */
    protected function getField(int $post_id, string $field_name, $default = '')
    {
        if (function_exists('get_field')) {
            $value = get_field($field_name, $post_id);
            return $value !== false ? $value : $default;
        }
        return $default;
    }

    /**
     * Build base schema structure
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function buildBaseSchema(\WP_Post $post): array
    {
        return [
            '@type' => $this->schemaType,
            '@id' => $this->getField($post->ID, 'schema_id', get_permalink($post->ID) . '#' . strtolower($this->schemaType)),
            'name' => $post->post_title,
        ];
    }

    /**
     * Add optional field if it exists
     *
     * @param array $schema
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function addIfNotEmpty(array &$schema, string $key, $value): void
    {
        if (!empty($value)) {
            $schema[$key] = $value;
        }
    }

    /**
     * Build address schema
     *
     * @param int $post_id
     * @param string $field_prefix
     * @return array|null
     */
    protected function buildAddress(int $post_id, string $field_prefix = 'address'): ?array
    {
        $street = $this->getField($post_id, $field_prefix . '_street');
        $locality = $this->getField($post_id, $field_prefix . '_locality');

        if (empty($street) && empty($locality)) {
            return null;
        }

        $address = [
            '@type' => 'PostalAddress',
        ];

        $this->addIfNotEmpty($address, 'streetAddress', $street);
        $this->addIfNotEmpty($address, 'addressLocality', $locality);
        $this->addIfNotEmpty($address, 'addressRegion', $this->getField($post_id, $field_prefix . '_region'));
        $this->addIfNotEmpty($address, 'postalCode', $this->getField($post_id, $field_prefix . '_postal_code'));
        $this->addIfNotEmpty($address, 'addressCountry', $this->getField($post_id, $field_prefix . '_country'));

        return $address;
    }

    /**
     * Build sameAs array from repeater or array field
     *
     * @param int $post_id
     * @param string $field_name
     * @return array
     */
    protected function buildSameAs(int $post_id, string $field_name = 'same_as'): array
    {
        $sameAs = [];
        $values = $this->getField($post_id, $field_name, []);

        if (is_array($values)) {
            foreach ($values as $value) {
                if (is_array($value) && isset($value['url'])) {
                    $sameAs[] = $value['url'];
                } elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                    $sameAs[] = $value;
                }
            }
        }

        return $sameAs;
    }

    public function getSchemaType(): string
    {
        return $this->schemaType;
    }

    public function validate(array $data): bool
    {
        return isset($data['@type']) && isset($data['name']);
    }
}
