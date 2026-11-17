<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Blueprints\Blueprint;
use Mamarmite\UIDEndpoint\Blueprints\PlaceBlueprint;
use const Mamarmite\UIDEndpoint\CLIENT_CONTEXT_DEFAULT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class PlaceAdapter
 */
class PlaceAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'Place';
    protected string $schemaGroupKey = 'group_schema_place';
    protected string $prefix = "p";

    function __construct(\WP_Post $post, Blueprint $blueprint = null)
    {
        parent::__construct($post, $blueprint ?? new PlaceBlueprint());
    }
    /**
     * Build base schema structure
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function build_base_schema(\WP_Post $post, bool $isSchemaRoot = false): array
    {
        $name = $this->get_field($this->post->ID, 'name', \wp_strip_all_tags($this->post->post_title));
        $type = $this->get_field($this->post->ID, 'type', $this->schemaType);
        return [
            '@type' => $type,
            '@id' => $this->uid->full(),
            'name' => $name,
        ];
    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $context = parent::transform($isSchemaRoot);
        $schema = array_merge($context, $this->build_base_schema($this->post, $isSchemaRoot));

        $this->add_to_schema($schema, 'description', \get_the_excerpt($this->post->ID));

        // Address
        $address = $this->build_address($this->post->ID);
        if ($address) {
            $schema['address'] = $address;
        }

        // SameAs
        $sameAs = $this->build_same_as($this->post->ID);
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        $image = $this->build_image();

        if (!empty($image)) {
            $schema['image'] = $image;
        }

        return $schema;
    }
}
