<?php

namespace Mamarmite\UIDEndpoint\Adapters;
use Mamarmite\UIDEndpoint\Adapters\AbstractSchemaAdapter;

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

    function __construct(\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);

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
