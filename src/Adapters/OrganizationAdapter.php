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

    function __construct(\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);

        $this->add_if_not_empty($schema, 'url', get_permalink($this->post->ID));
        $this->add_if_not_empty($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));

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
