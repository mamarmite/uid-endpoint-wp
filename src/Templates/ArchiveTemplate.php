<?php

namespace Mamarmite\UIDEndpoint\Templates;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

class ArchiveTemplate extends AbstractTemplate
{
    /**
     * @param \WP_Post|null $post
     */
    function __construct(?\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function render_head():void {

    }

    public function render_content():void {
        ?>
        <h1><?php echo \get_bloginfo('name'); ?> &mdash; Base du plugiciels UID</h1>
        <section>
            <h2>Entitées supportées et structurées pour l'ontologie d'Artsdata</h2>
            <ul>
                <li><code class="schema-type">Event</code></li>
                <li><code class="schema-type">Artist/ Agent</code></li>
                <li><code class="schema-type">Organisation</code></li>
                <li><code class="schema-type">Place</code></li>
                <li><code class="schema-type">CreativeWork</code></li>
            </ul>
        </section>
        <?php
    }

    public function render_footer():void {

    }
}
?>
