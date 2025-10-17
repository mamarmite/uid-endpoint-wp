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

    public function transform(\WP_Post $post): array
    {
        $schema = $this->buildBaseSchema($post);

        // Address
        $address = $this->buildAddress($post->ID);
        if ($address) {
            $schema['address'] = $address;
        }

        // SameAs
        $sameAs = $this->buildSameAs($post->ID);
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }
}
