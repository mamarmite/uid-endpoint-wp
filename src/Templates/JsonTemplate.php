<?php

namespace Mamarmite\UIDEndpoint\Templates;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class JsonTemplate extends AbstractTemplate
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
        echo \wp_json_encode($this->entity->transform(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function render_head():void {
    }

    public function render_content():void {
    }

    public function render_footer():void {
    }
}
?>
