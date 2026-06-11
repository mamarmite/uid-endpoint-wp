<?php

namespace Mamarmite\UIDEndpoint\Templates;

use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;
use Mamarmite\UIDEndpoint\Adapters\SchemaAdapterInterface;
use Mamarmite\UIDEndpoint\UID;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

abstract class AbstractTemplate
{
    public \WP_Post $post;
    public SchemaAdapterInterface $entity;

    /**
     * @param \WP_Post $post
     * @return void
     */
    function __construct(?\WP_Post $post)
    {
        if ($post !== null) {
            $this->post = $post;
            $this->entity = AdapterFactory::create($this->post);
        }

    }

    abstract function render_content();
    abstract function render_head();
    abstract function render_footer();

    public function change_headers() {
        //keep it default almost always.
        //not abstract because it's not need
    }

    public function render():void
    {
        ?>
        <html lang="fr">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title><?php echo MAMARMITE_UID_PLUGIN_NAME; ?> <?php echo \get_the_title() . ' | ' . \get_bloginfo('name'); ?></title>
                <link href="<?php echo MAMARMITE_UID_ENDPOINT_BASE_URL."assets/styles/"; ?>uid-main.css" rel="stylesheet" />
                <?php $this->render_head(); ?>
            </head>
            <body>
                <main>
                    <?php $this->render_content(); ?>
                </main>
                <footer>
                    <?php echo MAMARMITE_UID_PREFIX; ?>&Nopf;
                </footer>
                <?php $this->render_footer(); ?>
            </body>
        </html>
        <?php
    }

    public function get_adapter():?SchemaAdapterInterface {
        if ($this->post !== null) {
            return AdapterFactory::create($this->post);
        }
        return null;
    }

    public function create_schema():array {
        if ($this->post !== null) {
            return AdapterFactory::transfrom($this->post);
        }
        return [];
    }
}

?>
