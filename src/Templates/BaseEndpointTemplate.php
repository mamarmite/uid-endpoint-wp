<?php

namespace Mamarmite\UIDEndpoint\Templates;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}


class BaseEndpointTemplate extends AbstractTemplate
{
    function __contruct(\WP_Post $post)
    {

    }

    public function render_head() {
        return "";
    }

    public function render_content() {
        ?>
        <h1><?php echo \get_bloginfo('name'); ?> Unique identifiers base endpoint</h1>
        <section>
            <h2>Entities</h2>
            <ul>
                <li>Event</li>
                <li>Artist / agent</li>
                <li>Organisation</li>
                <li>Place</li>
                <li>CreativeWork</li>
            </ul>
        </section>
        <?php
    }

    public function render_footer() {
        return "";
    }
}
?>
