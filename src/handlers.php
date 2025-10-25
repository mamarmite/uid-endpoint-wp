<?php
namespace Mamarmite\UIDEndpoint;


use Mamarmite\UIDEndpoint\Templates\DefaultTemplate;
use Mamarmite\UIDEndpoint\Templates\BaseEndpointTemplate;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function handle_test_request() {
    global $wp_query;

    $r_id = \get_query_var('r_id');

    if (!empty($r_id)) {

        die();
    }
}

// Handle the custom endpoint request
function handle_entity_endpoint_request(): void
{
    global $wp_query;

    $r_id = \get_query_var('r_id');

    if (!empty($r_id)) {

        // BASE endpoint
        if ($r_id === '__base_endpoint__') {
            //render target entity
            $baseTemplate = new BaseEndpointTemplate();
            $baseTemplate->render(null);
            exit;
        } else {
            global $post;
            $post = \get_post($r_id);


            if ($post && $post->post_status === 'publish') {
                // Set up the global post data
                global $wp_query;
                $wp_query->is_single = true;
                $wp_query->is_singular = true;
                $wp_query->is_404 = false;
                $wp_query->found_posts = 1;
                $wp_query->post_count = 1;
                $wp_query->posts = array($post);
                $wp_query->post = $post;
                $wp_query->queried_object = $post;
                $wp_query->queried_object_id = $post->ID;

                // Set up post data for template functions
                \setup_postdata($post);

                //render target entity
                $defaultTemplate = new DefaultTemplate($post);
                $defaultTemplate->render();
                exit;
            } else {
                // Post not found, show 404
                global $wp_query;
                $wp_query->set_404();
                \status_header(404);

                $template = \locate_template(array('404.php', 'index.php'));
                if ($template) {
                    \load_template($template);
                    exit;
                }
            }
        }
    }
}
\add_action('template_redirect', __NAMESPACE__.'\\handle_entity_endpoint_request');
