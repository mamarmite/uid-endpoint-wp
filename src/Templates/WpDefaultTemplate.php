<?php

namespace Mamarmite\UIDEndpoint\Templates;


if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class WpDefaultTemplate extends AbstractTemplate
{
    function __contruct(\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function render_head()
    {
        return "";
    }

    public function render():void {
        // Load the appropriate template
        $template = \locate_template(array(
            'single-' . $this->post->post_type . '.php',
            'single.php',
            'index.php'
        ));

        if ($template) {
            \load_template($template);
            exit;
        }
    }

    public function render_content():void {
        //handling by wp template.
    }

    public function render_footer():void
    {
    }
}

?>
