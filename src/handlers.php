<?php
namespace Mamarmite\UIDEndpoint;

use Mamarmite\UIDEndpoint\Templates\ArchiveJsonTemplate;
use Mamarmite\UIDEndpoint\Templates\ArchiveTemplate;
use Mamarmite\UIDEndpoint\Templates\DefaultTemplate;
use Mamarmite\UIDEndpoint\Templates\BaseEndpointTemplate;
use Mamarmite\UIDEndpoint\Templates\JsonTemplate;

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

        // List endpoint
        if ($plugin_endpoint === MAMARMITE_UID_LIST_QUERYVARS_ENDPOINT) {
            //render target entity
            $archiveTemplate = new ArchiveTemplate(null);
            $archiveTemplate->render();
            exit;
        }

        // List endpoint
        if ($plugin_endpoint === MAMARMITE_UID_LISTJSON_QUERYVARS_ENDPOINT) {
            //render target entity
            $archiveJsonTemplate = new ArchiveJsonTemplate(null);
            $archiveJsonTemplate->change_headers();
            $archiveJsonTemplate->render();
            exit;
        }

        // PREVIEW
        if ($plugin_endpoint === MAMARMITE_UID_PREVIEW_QUERYVARS_ENDPOINT) {

            $uid_to_preview = \sanitize_text_field(\get_query_var('uid'));

            if (!empty($uid_to_preview)) {

                $uid = UID::parse($uid_to_preview);
                global $post;
                global $wp_query;
                $valid_post = prepare_post_from_query($uid, $post, $wp_query);

                if ($valid_post) {
                    //render target entity
                    $defaultTemplate = new DefaultTemplate($valid_post);
                    $defaultTemplate->render();
                    exit;
                }
            }
        }

        // LDJSON
        if ($plugin_endpoint === MAMARMITE_UID_LDJSON_QUERYVARS_ENDPOINT) {

            $uid_to_preview = \sanitize_text_field(\get_query_var('uid'));

            if (!empty($uid_to_preview)) {

                $uid = UID::parse($uid_to_preview);
                global $post;
                global $wp_query;
                $valid_post = prepare_post_from_query($uid, $post, $wp_query);

                //\prepare_post_from_query($uid, $post, $wp_query);
                if ($valid_post) {

                    //render target entity
                    $json_template = new JsonTemplate($post);
                    $json_template->change_headers();
                    $json_template->render();
                    exit;
                }
            }
        }
        load_404_template();
    }
}

function prepare_post_from_query( $uid, &$post, &$query ) {
    if (count($uid) === 3 && isset($uid["post_id"])) {
        $post = \get_post($uid["post_id"]);
    }

    if ($post && UID::validate_uid($post, $uid) && $post->post_status === 'publish') {
        $query->is_single = true;
        $query->is_singular = true;
        $query->is_404 = false;
        $query->found_posts = 1;
        $query->post_count = 1;
        $query->posts = array($post);
        $query->post = $post;
        $query->queried_object = $post;
        $query->queried_object_id = $post->ID;

        // Set up post data for template functions
        \setup_postdata($post);
        return $post;
    }

    return null;
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
