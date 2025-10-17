<?php

namespace Mamarmite\UIDEndpoint\Renderers;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}


function render_default_template($post) {
    \load_template(\plugin_dir_path( __FILE__ )."../templates/DefaultTemplate.php", true, ['post' => $post]);
    exit;
}

function render_default_wp_template($post) {

    // Load the appropriate template
    $template = \locate_template(array(
        'single-' . $post->post_type . '.php',
        'single.php',
        'index.php'
    ));

    if ($template) {
        \load_template($template);
        exit;
    }
}
