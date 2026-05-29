<?php
namespace Mamarmite\UIDEndpoint;


use Mamarmite\UIDEndpoint\Templates\DefaultTemplate;
use Mamarmite\UIDEndpoint\Templates\BaseEndpointTemplate;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}


// Handle the custom endpoint request
function handle_entity_endpoint_request(): void
{
    global $wp_query;

    $plugin_endpoint = \sanitize_text_field(\get_query_var('uid_plugin_endpoint'));

    if (!empty($plugin_endpoint)) {

        // Index endpoint
        if ($plugin_endpoint === MAMARMITE_UID_BASE_QUERYVARS_ENDPOINT) {
            //render target entity
            $baseTemplate = new BaseEndpointTemplate(null);
            $baseTemplate->render();
            exit;
        }

        // PREVIEW
        if ($plugin_endpoint === MAMARMITE_UID_PREVIEW_QUERYVARS_ENDPOINT) {

            $uid_to_preview = \sanitize_text_field(\get_query_var('uid'));

            if (!empty($uid_to_preview)) {

                $uid = UID::parse($uid_to_preview);

                if (count($uid) === 3 && isset($uid["post_id"])) {
                    global $post;
                    //only set the global when the uid parse is positive.
                    $post = \get_post($uid["post_id"]);
                }

                if ($post && UID::validate_uid($post, $uid) && $post->post_status === 'publish') {
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
                }
            }
        }
        load_404_template();
    }
}

function load_404_template() {
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
\add_action('template_redirect', __NAMESPACE__.'\\handle_entity_endpoint_request');
