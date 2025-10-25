<?php

namespace Mamarmite\UIDEndpoint\Templates;

use Mamarmite\UIDEndpoint\Adapters\SchemaAdapterInterface;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class DefaultTemplate extends AbstractTemplate
{

    public SchemaAdapterInterface $entity;
    function __contruct(\WP_Post $post)
    {
        parent::__contruct($post);
    }

    public function render_head() {
        return "";
    }

    public function render_content() {
        ?>
        <h3><span class="schema-type"><?php echo $this->entity->post_type->labels->singular_name; ?></span></h3>
        <h2><?php echo $this->entity->uid->full(); ?></h2>
        <h1><?php echo \get_the_title(); ?></h1>
        <section>
            <div class="schema-container">
                <code>
                    <pre><?php echo json_encode($this->entity->transform(), JSON_PRETTY_PRINT); ?></pre>
                </code>
            </div>
        </section>
        <?php
    }

    public function render_footer() {
        return "";
    }
}
?>
