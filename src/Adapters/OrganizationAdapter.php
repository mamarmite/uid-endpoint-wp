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

    public function transform(\WP_Post $post): array
    {
        $schema = $this->buildBaseSchema($post);

        $this->addIfNotEmpty($schema, 'url', $this->getField($post->ID, 'url', get_permalink($post->ID)));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($post->ID, 'additional_type'));

        $sameAs = $this->buildSameAs($post->ID);
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }
}
