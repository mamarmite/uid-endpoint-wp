<?php

namespace Mamarmite\UIDEndpoint\Adapters;
use Mamarmite\UIDEndpoint\Adapters\AbstractSchemaAdapter;

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

    function __construct(\WP_Post $post, $schema_allow_list=[])
    {
        $this->default_allow_list = [
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "sameAs" => true,
            "image" => true
        ];
        parent::__construct($post, $schema_allow_list);
    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);

        $this->add_to_schema($schema, 'url', get_permalink($this->post->ID));
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
