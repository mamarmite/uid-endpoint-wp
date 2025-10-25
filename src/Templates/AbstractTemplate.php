<?php

namespace Mamarmite\UIDEndpoint\Templates;

use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;
use Mamarmite\UIDEndpoint\Adapters\SchemaAdapterInterface;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

abstract class AbstractTemplate
{
    public \WP_Post $post;
    public SchemaAdapterInterface $entity;

    function __contruct(\WP_Post $post)
    {
        $this->post = $post;
        $this->entity = $this->get_adapter($post);
    }

    abstract function render_content();
    abstract function render_head();
    abstract function render_footer();

    public function render(\WP_Post $post)
    {
        ?>
        <html>
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title>/<?php echo MAMARMITE_UID_BASE_ENDPOINT; ?>/ <?php echo \get_the_title() . ' | ' . \get_bloginfo('name'); ?></title>
                <link href="<?php echo MAMARMITE_UID_ENDPOINT_BASE_URL."assets/styles/"; ?>uid-main.css" rel="stylesheet" />
                <?php $this->render_head(); ?>
            </head>
            <body>
                <main>
                    <?php $this->render_content($this->post); ?>
                </main>
                <footer>
                    <?php echo MAMARMITE_UID_DOMAIN ."/".  MAMARMITE_UID_BASE_ENDPOINT; ?>
                </footer>
                <?php $this->render_footer(); ?>
            </body>
        </html>
        <?php
    }

    public function get_adapter():SchemaAdapterInterface {
        return AdapterFactory::create($this->post);
    }

    public function create_schema():array {
        return AdapterFactory::transfrom($this->post);
    }
}

?>
