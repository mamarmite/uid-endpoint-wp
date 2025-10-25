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
    protected string $prefix = "o";

    function __construct(string $postType, \WP_Post $post = null)
    {
        parent::__construct($postType, $post);
    }

    public function transform(): array
    {
        $schema = $this->buildBaseSchema($this->post);

        $this->addIfNotEmpty($schema, 'url', $this->getField($this->post->ID, 'url', get_permalink($this->post->ID)));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($this->post->ID, 'additional_type'));

        $sameAs = $this->buildSameAs($this->post->ID);
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }
}
