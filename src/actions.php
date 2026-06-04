<?php
namespace Mamarmite\UIDEndpoint;

use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * WP action callback added to wp_head.
 * check if post type is supported, create the adapter for it and print the json prettified with the json+ld structure.
 * @return void
 */
function print_schema_jsonld_head()
{
    global $post;
    if ($post) {
        $is_supported = AdapterFactory::is_supported($post);
        if ($is_supported) {
            $entity = AdapterFactory::create($post);
            if ($entity) {
                ?>
                <script type="application/ld+json" class="unique-id-endpoint">
                    <?php echo \wp_json_encode($entity->transform(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
                </script>
                <?php
            }
        }
    }
}

\add_action('wp_head', __NAMESPACE__ . '\\print_schema_jsonld_head');



/**
 * Action function called on edit_form_after_title action
 * Add link into single edit post page under the title, to preview the target entity.
 * @param $post
 * @return void
 */
function render_uid_single_post_edit($post) {
    $is_supported = AdapterFactory::is_supported($post);
    if ($is_supported) {
        $entity = AdapterFactory::create($post);
        $url = get_home_url().'/'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/preview?uid='.$entity->uid->full();
        $uid = '<strong>UID : </strong><a href="'.$url.'" title="'.$url.'" target="_blank">'.$url.'</a>';
        echo '<div style="padding: 0 10px; margin-top:5px; min-height: 25px;">'.$uid.'</div>';
    }
}
\add_action("edit_form_after_title", __NAMESPACE__."\\render_uid_single_post_edit", 10, 5);
