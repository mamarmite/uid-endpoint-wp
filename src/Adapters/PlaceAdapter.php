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
    protected string $prefix = "p";

    function __construct(string $postType, \WP_Post $post = null)
    {
        parent::__construct($postType, $post);
    }

    public function transform(): array
    {
        $schema = $this->buildBaseSchema($this->post);

        // Address
        $address = $this->buildAddress($this->post->ID);
        if ($address) {
            $schema['address'] = $address;
        }

        // SameAs
        $sameAs = $this->buildSameAs($this->post->ID);
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }
}
