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

    public $performance_test_start_time;
    public $performance_test_end_time;

    /**
     * @param \WP_Post $post
     * @return void
     */
    function __construct(?\WP_Post $post)
    {
        $this->performance_test_start_time = 0;
        if ($post !== null) {
            $this->post = $post;
            $this->entity = AdapterFactory::create($this->post);
        }

    }

    protected function performance_test_start() {
        $this->performance_test_start_time = microtime( true );
    }
    protected function performance_test_end() {

    }

    protected function render_performance_test($label = "Render from abstract Template") {
        $this->performance_test_end_time = microtime( true );
        $test_value = sprintf( '[%s] %.4f s', $label, microtime( true ) - $this->performance_test_start_time );
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log($test_value);
        }
        echo "<!-- ".$test_value." -->";
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
        $this->performance_test_start();
        ?>
        <html lang="fr">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                <title><?php echo MAMARMITE_UID_PLUGIN_NAME; ?> <?php echo \get_the_title() . ' | ' . \get_bloginfo('name'); ?></title>
                <link href="<?php echo MAMARMITE_UID_ENDPOINT_BASE_URL."assets/styles/"; ?>uid-main.css?v=8" rel="stylesheet" />
                <?php $this->render_head(); ?>
            </head>
            <body>
                <main>
                    <?php $this->render_content(); ?>
                </main>
                <hr>
                <section>
                    <?php $this->render_footer(); ?>
                </section>
                <footer>
                    <a href="<?php echo get_home_url() . '/' . MAMARMITE_UID_PLUGIN_BASE_ENDPOINT . '/'; ?>" title="" target="_self"><?php echo MAMARMITE_UID_PLUGIN_NAME; ?></a> &mdash; <?php echo \get_bloginfo('name'); ?> <code><?php echo MAMARMITE_UID_PREFIX; ?>&Nopf;</code>
                </footer>
            </body>
        </html>
        <?php
        $this->render_performance_test();
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
