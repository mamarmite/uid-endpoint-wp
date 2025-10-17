<?php

namespace Mamarmite\UIDEndpoint\Adapters;
use Mamarmite\UIDEndpoint\Adapters\AbstractSchemaAdapter;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class CreativeWorkAdapter
 */
class CreativeWorkAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'CreativeWork';

    public function transform(\WP_Post $post): array
    {
        $schema = $this->buildBaseSchema($post);

        $this->addIfNotEmpty($schema, 'alternateName', $this->getField($post->ID, 'alternate_name'));
        $this->addIfNotEmpty($schema, 'description', $this->getField($post->ID, 'description', $post->post_content));
        $this->addIfNotEmpty($schema, 'url', $this->getField($post->ID, 'url', get_permalink($post->ID)));
        $this->addIfNotEmpty($schema, 'image', $this->getField($post->ID, 'image'));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($post->ID, 'additional_type'));
        $this->addIfNotEmpty($schema, 'disambiguatingDescription', $this->getField($post->ID, 'disambiguating_description'));
        $this->addIfNotEmpty($schema, 'mainEntityOfPage', $this->getField($post->ID, 'main_entity_of_page'));

        // Creators
        $creators = $this->buildCreators($post->ID);
        if (!empty($creators)) {
            $schema['creator'] = $creators;
        }

        return $schema;
    }

    protected function buildCreators(int $post_id): array
    {
        $creators = [];
        $creatorIds = $this->getField($post_id, 'creators', []);

        if (is_array($creatorIds)) {
            foreach ($creatorIds as $creatorId) {
                $creator = get_post($creatorId);
                if ($creator) {
                    $artistAdapter = new ArtistAdapter();
                    $creators[] = $artistAdapter->transform($creator);
                }
            }
        }

        return $creators;
    }
}
