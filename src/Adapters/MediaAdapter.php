<?php

namespace Mamarmite\UIDEndpoint\Adapters;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class MediaAdapter
 */
class MediaAdapter implements SchemaAdapterInterface
{
    protected string $schemaType = 'Image';
    protected string $prefix = "";
    public \WP_Post $post;
    public \WP_Post_Type $post_type;

    function __construct(\WP_Post $post) {
        $this->post = $post;
        $this->post_type = get_post_type_object($post->post_type);
    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);
        $this->add_if_not_empty($schema, 'title', $this->post->post_title);
        $this->add_if_not_empty($schema, 'description', $this->post->post_content);
        $this->add_if_not_empty($schema, 'caption', $this->post->post_content);
        $this->add_if_not_empty($schema, 'alt_text', $this->post->post_content);
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
        //media https://developer.wordpress.org/rest-api/reference/media/
        //title
        //caption
        //alt_text
        //description


        $has_post_thumbnail = \has_post_thumbnail($post);
        if($has_post_thumbnail){
            $thumb = \wp_get_attachment_image_src( \get_post_thumbnail_id($post->ID), 'large' );
            $picture = $thumb['0'];
        }
        var_dump($has_post_thumbnail, $thumb);
        return [
            '@type' => $this->schemaType,
            'contentUrl' => get_the_permalink($post->ID),
        ];
    }

    /**
     * Add optional field if it exists
     *
     * @param array $schema
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function add_if_not_empty(array &$schema, string $key, $value): void
    {
        if (!empty($value)) {
            $schema[$key] = $value;
        }
    }

    public function get_schema_type(): string {
        return $this->schemaType;
    }

    public function validate(array $data): bool
    {
        return isset($data['@type']) && isset($data['name']);
    }
}
