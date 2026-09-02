<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Blueprints\Blueprint;
use Mamarmite\UIDEndpoint\Blueprints\OrganisationBlueprint;
use const Mamarmite\UIDEndpoint\CLIENT_CONTEXT_DEFAULT;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class OrganizationAdapter
 */
class OrganizationAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'Organization';
    protected string $schemaGroupKey = 'group_schema_organisation';
    protected string $prefix = "o";

    function __construct(\WP_Post $post, Blueprint $blueprint = null)
    {
        parent::__construct($post, $blueprint ?? new OrganisationBlueprint());
    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $context = parent::transform($isSchemaRoot);
        $schema = array_merge($context, $this->build_base_schema($this->post, $isSchemaRoot));

        $this->add_to_schema($schema, 'url', $this->get_field($this->post->ID, 'url'));//mainEntityOfPage : get_permalink($this->post->ID)
        $this->add_to_schema($schema, 'description', \get_the_excerpt($this->post->ID));
        $this->add_to_schema($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));

        //sameAs
        if (array_key_exists('sameAs', $this->allow_list)) {
            $sameAs = $this->build_same_as($this->post->ID);
            if (!empty($sameAs)) {
                $schema['sameAs'] = $sameAs;
            }
        }

        //MediaObject
        if (array_key_exists('image', $this->allow_list)) {
            $image = $this->build_image();
            if (!empty($image)) {
                $schema['image'] = $image;
            }
        }

        return $schema;
    }
}
