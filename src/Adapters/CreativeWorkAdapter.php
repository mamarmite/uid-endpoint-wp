<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Blueprints\ArtistBlueprint;
use Mamarmite\UIDEndpoint\Blueprints\Blueprint;
use Mamarmite\UIDEndpoint\Blueprints\CreativeWorkBlueprint;
use const Mamarmite\UIDEndpoint\CLIENT_CONTEXT_DEFAULT;

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


    function __construct(\WP_Post $post, Blueprint $blueprint = null)
    {
        parent::__construct($post, $blueprint ?? new CreativeWorkBlueprint());
    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $context = parent::transform($isSchemaRoot);
        $schema = array_merge($context, $this->build_base_schema($this->post, $isSchemaRoot));

        $this->add_to_schema($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_to_schema($schema, 'description', $this->get_field($this->post->ID, 'description', \get_the_excerpt($this->post->ID)));
        $this->add_to_schema($schema, 'url', $this->get_field($this->post->ID, 'url'));
        $this->add_to_schema($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));
        $this->add_to_schema($schema, 'inLanguage', $this->current_language);
        $this->add_to_schema($schema, 'disambiguatingDescription', $this->get_field($this->post->ID, 'disambiguating_description'));
        $this->add_to_schema($schema, 'mainEntityOfPage', get_permalink($this->post->ID));

        // Creators
        if (array_key_exists('creator', $this->allow_list)) {
            $creators = $this->build_creators($this->post->ID);
            if (!empty($creators)) {
                $schema['creator'] = $creators;
            }
        }

        //MediaObject
        if (array_key_exists('image', $this->allow_list)) {
            $image = $this->build_image($this->allow_list['image']);
            if (!empty($image)) {
                $schema['image'] = $image;
            }
        }

        return $schema;
    }

    protected function build_creators(int $post_id): array
    {
        $creators = [];
        $creatorPosts = $this->get_field($post_id, 'creators', []);
        $context = CLIENT_CONTEXT_DEFAULT;
        if (is_array($creatorPosts)) {
            foreach ($creatorPosts as $creator) {
                if ($creator) {
                    $sub_entity_fields = $this->blueprint->get_embed_fields("creator", $context);
                    $artistAdapter = new ArtistAdapter($creator, new ArtistBlueprint($sub_entity_fields));

                    $creators[] = $artistAdapter->transform();
                }
            }
        }

        return $creators;
    }
}
