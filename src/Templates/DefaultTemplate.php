<?php

namespace Mamarmite\UIDEndpoint\Templates;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class DefaultTemplate extends AbstractTemplate
{
    /**
     * @param \WP_Post $post
     * @return void
     */
    function __construct(?\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function render_head():void {
       ?><?php
    }

    public function render_content():void {
        ?>
        <h3><span class="schema-type"><?php echo $this->entity->post_type->labels->singular_name; ?></span></h3>
        <h2><?php echo $this->entity->uid->full(); ?></h2>
        <h1><?php echo \get_the_title(); ?></h1>
        <section>
            <div class="schema-container">
                <pre><code><?php echo json_encode($this->entity->transform(), JSON_PRETTY_PRINT); ?></code></pre>
            </div>
        </section>
        <?php
    }

    public function render_footer():void {
        ?><?php
    }
}
?>
