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
    protected string $prefix = "c";

    function __construct(string $postType, \WP_Post $post = null)
    {
        parent::__construct($postType, $post);
    }

    public function transform(): array
    {
        $schema = $this->buildBaseSchema($this->post);

        $this->addIfNotEmpty($schema, 'alternateName', $this->getField($this->post->ID, 'alternate_name'));
        $this->addIfNotEmpty($schema, 'description', $this->getField($this->post->ID, 'description', $this->post->post_content));
        $this->addIfNotEmpty($schema, 'url', $this->getField($this->post->ID, 'url', get_permalink($this->post->ID)));
        $this->addIfNotEmpty($schema, 'image', $this->getField($this->post->ID, 'image'));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($this->post->ID, 'additional_type'));
        $this->addIfNotEmpty($schema, 'disambiguatingDescription', $this->getField($this->post->ID, 'disambiguating_description'));
        $this->addIfNotEmpty($schema, 'mainEntityOfPage', $this->getField($this->post->ID, 'main_entity_of_page'));

        // Creators
        $creators = $this->buildCreators($this->post->ID);
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
                    $artistAdapter = new ArtistAdapter($creator);
                    $creators[] = $artistAdapter->transform($creator);
                }
            }
        }

        return $creators;
    }
}
