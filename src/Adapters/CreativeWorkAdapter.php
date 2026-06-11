<?php

namespace Mamarmite\UIDEndpoint\Adapters;

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

    function __construct(\WP_Post $post, $schema_allow_list=[])
    {
        $this->default_allow_list = [
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "inLanguage" => true,
            "additionalType" => true,
            "disambiguatingDescription" => true,
            "mainEntityOfPage" => true,
            "creator" => [
                "alternateName" => true,
                //"url" => true,
                //"additionalType" => true,
                "sameAs" => true,
            ],
            "image" => [//all except the usageInfo
                "url" => true,
                "disambiguatingDescription" => true,
                "sdDatePublished" => true,
            ]
        ];
        parent::__construct($post, $schema_allow_list);
    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $context = parent::transform($isSchemaRoot);
        $schema = array_merge($context, $this->build_base_schema($this->post, $isSchemaRoot));

        $this->add_to_schema($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_to_schema($schema, 'description', $this->get_field($this->post->ID, 'description', \get_the_excerpt($this->post->ID)));
        $this->add_to_schema($schema, 'url', get_permalink($this->post->ID));
        $this->add_to_schema($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));
        $this->add_to_schema($schema, 'inLanguage', $this->current_language);
        $this->add_to_schema($schema, 'disambiguatingDescription', $this->get_field($this->post->ID, 'disambiguating_description'));
        $this->add_to_schema($schema, 'mainEntityOfPage', $this->get_field($this->post->ID, 'main_entity_of_page'));

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

        if (is_array($creatorPosts)) {
            foreach ($creatorPosts as $creator) {
                if ($creator) {
                    $override_allow_list = is_array($this->allow_list["creator"]) ? $this->allow_list["creator"] : [];
                    $artistAdapter = new ArtistAdapter($creator, $override_allow_list);
                    $creators[] = $artistAdapter->transform();
                }
            }
        }

        return $creators;
    }
}
