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
    protected string $schemaGroupKey = 'group_schema_creative_work';
    protected string $prefix = "c";

    function __construct(\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);

        $this->add_if_not_empty($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_if_not_empty($schema, 'description', $this->get_field($this->post->ID, 'description', $this->post->post_content));
        $this->add_if_not_empty($schema, 'url', get_permalink($this->post->ID));
        $this->add_if_not_empty($schema, 'image', $this->get_field($this->post->ID, 'image'));
        $this->add_if_not_empty($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));
        $this->add_if_not_empty($schema, 'disambiguatingDescription', $this->get_field($this->post->ID, 'disambiguating_description'));
        $this->add_if_not_empty($schema, 'mainEntityOfPage', $this->get_field($this->post->ID, 'main_entity_of_page'));


        // Creators
        $creators = $this->build_creators($this->post->ID);
        if (!empty($creators)) {
            $schema['creator'] = $creators;
        }


        $image = $this->build_image();

        if (!empty($image)) {
            $schema['image'] = $image;
        }

        return $schema;
    }

    protected function build_creators(int $post_id): array
    {
        $creators = [];
        $creatorPosts = $this->get_field($post_id, 'creators', []);

        if (is_array($creatorPosts)) {
            foreach ($creatorPosts as $creator) {
                if ($creator) {
                    $artistAdapter = new ArtistAdapter($creator);
                    $creators[] = $artistAdapter->transform();
                }
            }
        }

        return $creators;
    }
}
