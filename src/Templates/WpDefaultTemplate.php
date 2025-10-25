<?php

namespace Mamarmite\UIDEndpoint\Templates;


if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class WpDefaultTemplate extends AbstractTemplate
{
    function __contruct(\WP_Post $post)
    {
        parent::__contruct($post);
    }

    public function render_head()
    {
        return "";
    }

    public function render($post) {
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

    public function render_content() {
        //handling by wp template.
    }

    public function render_footer()
    {
        return "";
    }
}

?>
