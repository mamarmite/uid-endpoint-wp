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
        //ajotuer le type ?
        $type = "Tous";
        ?>
        <h1>Liste <code class="schema-type"><?php echo $type; ?></code></h1>
        <section>
            <ul>
                <li>Item</li>
            </ul>
        </section>
        <?php
    }

    public function render_footer():void {

    }
}
?>
