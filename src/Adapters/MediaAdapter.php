<?php

namespace Mamarmite\UIDEndpoint\Adapters;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class MediaAdapter
 */
class MediaAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'mediaObject';
    protected string $prefix = "";
    public \WP_Post $post;
    public \WP_Post_Type $post_type;

    public $default_usage_info_url = "https://kg.artsdata.ca/doc/image-policy";

    function __construct(\WP_Post $post, $schema_allow_list=[]) {
        $this->default_allow_list = [
            "url" => true,
            "usageInfo" => true,
            "disambiguatingDescription" => true,
            "description" => true,
            "sdDatePublished" => true,
            "inLanguage" => true,
        ];
        parent::__construct($post, $schema_allow_list);
        $this->post = $post;
        $this->post_type = get_post_type_object($post->post_type);

    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);
        $has_post_thumbnail = \has_post_thumbnail($this->post);
        if($has_post_thumbnail) {
            $featured_image_id = \get_post_thumbnail_id($this->post->ID);
            $featured_image_src = \wp_get_attachment_image_src($featured_image_id, 'original');
            $featured_image = get_post($featured_image_id);

            $media_date_published = new \DateTimeImmutable($featured_image->post_date_gmt);

            $this->add_to_schema($schema, 'url', $featured_image_src[0]);
            $this->add_to_schema($schema, 'usageInfo', $this->default_usage_info_url);
            //$this->add_to_schema($schema, 'disambiguatingDescription', \get_the_excerpt($featured_image_id));
            $this->add_to_schema($schema, 'description', \get_the_excerpt($featured_image_id));
            $this->add_to_schema($schema, 'sdDatePublished', $media_date_published->format('c'));
            $this->add_to_schema($schema, 'inLanguage', $this->current_language);
        }
        //'about' => $post->description,
        return $schema;
    }

    /**
     * Build base schema structure
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function build_base_schema(\WP_Post $post): array
    {
        return [
            '@type' => $this->schemaType,
        ];
    }

}
