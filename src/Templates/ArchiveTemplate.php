<?php

namespace Mamarmite\UIDEndpoint\Templates;


use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;
use Mamarmite\UIDEndpoint\UID;

if (!defined('ABSPATH')) {
    die('Invalid request.');
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

    public function render_head(): void
    {

    }


    public function render_content(): void
    {

        $allowed_post_types = AdapterFactory::get_supported_post_type();

        $paged = max(15, get_query_var('paged') ?: get_query_var('page'));

        /**
         *  If we want only the entity that have a schema data setup ?
         */

//            'meta_query'     => array(
//                array(
//                    'key'     => 'alternate_name',
//                    'value'   => '',
//                    'compare' => '!=',
//                ),
//            ),
//
//        'posts_per_page' => 25,
        $args = array(
            'post_type' => $allowed_post_types,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );

        $query = new \WP_Query($args);

        $base_link = get_home_url() . '/' . MAMARMITE_UID_PLUGIN_BASE_ENDPOINT . '/';
        $base_preview = $base_link . MAMARMITE_UID_PLUGIN_PREVIEW_ENDPOINT . '?uid=';
        $base_json = $base_link . MAMARMITE_UID_PLUGIN_LDJSON_ENDPOINT . '?uid=';
        ?>
        <h1>Liste</h1>
        <p><code class="schema-type"><?php echo implode("</code>, <code class='schema-type'>", $allowed_post_types); ?></code></p>
        <hr>
        <section>
            <ul class="list-no-style">
                <?php
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        global $post;

                        if (AdapterFactory::is_supported($post)) {
                            $entity = AdapterFactory::create($post);
                            if ($entity) {
                            ?>
                            <li class="entity-data">
                                <small><code class="fixed text-center">@<?php echo $entity->get_schema_type(); ?></code></small>
                                <small><code class="fixed text-center"><?php echo $post->post_type; ?></code></small>
                                <a href="<?php echo $base_preview . $entity->uid->full(); ?>" title="<?php echo $entity->uid->full(); ?>" class="btn" target="_self"><?php echo $entity->uid->full(); ?></a>
                                <a href="<?php echo $base_json . $entity->uid->full(); ?>" title="<?php echo $entity->uid->full(); ?>" class="btn" target="_self">json</a>
<script type="application/ld+json" class="unique-id-endpoint" id="<?php echo $entity->uid->full(); ?>">
<?php echo \wp_json_encode($entity->transform(true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
</script>
                            </li>
                            <?php
                            }
                        }
                    }
                    wp_reset_postdata();
                }
                ?>
            </ul>
        </section>
        <?php
    }

    public function render_footer(): void
    {

    }
}

?>
