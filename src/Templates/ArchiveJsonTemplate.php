<?php

namespace Mamarmite\UIDEndpoint\Templates;

use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class ArchiveJsonTemplate extends AbstractTemplate
{
    /**
     * @param \WP_Post $post
     * @return void
     */
    function __construct(?\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function change_headers():void {
        if ( ! \headers_sent() ) {
            \status_header( 200 );
            \header( 'Content-Type: application/json; charset=' . \get_option( 'blog_charset' ) );
            \nocache_headers();
        }
    }

    public function render():void {
        $allowed_post_types = AdapterFactory::get_supported_post_type();
        $args = array(
            'post_type' => $allowed_post_types,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        $query = new \WP_Query($args);

        $json_raw = [
            '@context' => MAMARMITE_UID_CONTEXT,
        ];

        $graph_list = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                global $post;

                if (AdapterFactory::is_supported($post)) {
                    $entity = AdapterFactory::create($post);
                    if ($entity) {
                        array_push($graph_list, $entity->transform());
                    }
                }
            }
            $json_raw["@graph"] = $graph_list;
        }

        echo \wp_json_encode($json_raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function render_head():void {
    }

    public function render_content():void {
    }

    public function render_footer():void {
    }
}
?>
