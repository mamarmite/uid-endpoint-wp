<?php
namespace Mamarmite\UIDEndpoint;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function add_uids_to_acf_load_point( $paths ) {
    //we keep all other load point, but add ours.
    // Append the new path and return it.
    if (is_array($paths)) {
        $paths[] = MAMARMITE_UID_ENDPOINT_BASE_PATH . 'acf-json';
    }
    return $paths;
}
add_filter( 'acf/settings/load_json', __NAMESPACE__.'\\add_uids_to_acf_load_point' );


function add_uids_to_acf_save_point($path) {
    // Save inside a folder named 'acf-json' in your plugin root
    return MAMARMITE_UID_ENDPOINT_BASE_PATH . 'acf-json';
}
//Used only when changes are done for the group. Avoid adding it, it will save all of acf json here and we don't want that.
//add_filter('acf/settings/save_json', __NAMESPACE__.'\\add_uids_to_acf_save_point');
