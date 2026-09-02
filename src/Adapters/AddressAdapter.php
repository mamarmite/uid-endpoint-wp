<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Blueprints\AddressBlueprint;
use Mamarmite\UIDEndpoint\Blueprints\Blueprint;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class MediaAdapter
 */
class AddressAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'Address';
    protected string $field_prefix = 'address';
    protected string $schemaGroupKey = 'group_schema_place';
    protected string $prefix = "p";

    function __construct(\WP_Post $post, Blueprint $blueprint = null)
    {
        parent::__construct($post, $blueprint ?? new AddressBlueprint());
    }

    /**
     * Build base schema structure
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function build_base_schema(\WP_Post $post, bool $isSchemaRoot = false): array
    {
        $type = $this->get_field($post->ID, $this->field_prefix . '_type');
        return [
            '@type' => $type,
        ];

    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $address = $this->build_base_schema($this->post, $isSchemaRoot);

        $post_id = $this->post->ID;
        $street = $this->get_field($post_id, $this->field_prefix . '_street_address');
        $locality = $this->get_field($post_id, $this->field_prefix . '_address_locality');

        if (empty($street) && empty($locality)) {
            return [];
        }

        $this->add_to_schema($address, 'streetAddress', $street);
        $this->add_to_schema($address, 'addressLocality', $locality);
        $this->add_to_schema($address, 'addressRegion', $this->get_field($post_id, $this->field_prefix . '_address_region'));
        $this->add_to_schema($address, 'postalCode', $this->get_field($post_id, $this->field_prefix . '_postal_code'));
        $this->add_to_schema($address, 'addressCountry', $this->get_field($post_id, $this->field_prefix . '_address_country'));

        //'about' => $post->description,
        return $address;
    }
}
