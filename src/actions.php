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
    $is_supported = AdapterFactory::is_supported($post);
    if ($is_supported) {
        $entity = AdapterFactory::create($post);
        ?>
            <script type="application/ld+json" class="unique-id-endpoint">
                <?php echo json_encode($entity->transform(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>
            </script>
        <?php
    }
}

add_action('wp_head', __NAMESPACE__ . '\\print_schema_jsonld_head');
