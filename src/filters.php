<?php
namespace Mamarmite\UIDEndpoint;

use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Action function called on edit_form_after_title
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
