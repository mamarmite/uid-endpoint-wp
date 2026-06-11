<?php

namespace Mamarmite\UIDEndpoint\Templates;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

class BaseEndpointTemplate extends AbstractTemplate
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
        <h1>UID</h1>
        <section>
            <h2>Entitées supportées et structurées pour l'ontologie d'Artsdata</h2>
            <ul class="list-schema-type">
                <li><code class="schema-type">Event</code></li>
                <li><code class="schema-type">Artist/ Agent</code></li>
                <li><code class="schema-type">Organisation</code></li>
                <li><code class="schema-type">Place</code></li>
                <li><code class="schema-type">CreativeWork</code></li>
                <li><a href="<? echo get_home_url().'/'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/'.MAMARMITE_UID_PLUGIN_LIST_ENDPOINT; ?>" class="btn" target="_self">Lister toutes les entités</a></li>
            </ul>
        </section>
        <?php
    }

    public function render_footer():void {

    }
}
?>
