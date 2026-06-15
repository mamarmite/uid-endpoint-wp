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
