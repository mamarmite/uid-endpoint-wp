<?php

namespace Mamarmite\UIDEndpoint;


use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}
function print_schema_jsonld_head()
{
    //check if post type is supported
    //create the adapter for it
    //print the json prettified

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
