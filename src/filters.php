<?php
namespace Mamarmite\UIDEndpoint;

use Mamarmite\UIDEndpoint\Adapters\AdapterFactory;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function render_uid_side_of_permalink($return, $post_id, $new_title, $new_slug, $post) {

    $is_supported = AdapterFactory::is_supported($post);

    if ($is_supported) {
        $entity = AdapterFactory::create($post);
        $uid = '<strong style="margin-left:1rem;">UID : </strong><a href="'.get_home_url().$entity->uid->full().'" title="'.$entity->uid->full().'" target="_blank">'.$entity->uid->full().'</a>';
        return $return . $uid;
    }
    if (!$is_supported) {
        $uid = '<strong style="margin-left:1rem;">Entity non supporté pour les UID</strong>';
        return $return . $uid;
    }
    return $return;
}
//\add_filter('get_sample_permalink_html', __NAMESPACE__."\\render_uid_side_of_permalink", 10, 5);


function render_uid_single_post_edit($post) {
    $is_supported = AdapterFactory::is_supported($post);
    if ($is_supported) {
        $entity = AdapterFactory::create($post);
        $url = get_home_url()."/r/preview?uid=".$entity->uid->full();
        $uid = '<strong>UID : </strong><a href="'.$url.'" title="'.$url.'" target="_blank">'.$url.'</a>';

        echo '<div style="padding: 0 10px; margin-top:5px; min-height: 25px;">'.$uid.'</div>';
    }
}
\add_action("edit_form_after_title", __NAMESPACE__."\\render_uid_single_post_edit", 10, 5);
