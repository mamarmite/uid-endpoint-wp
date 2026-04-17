<?php
namespace Mamarmite\UIDEndpoint;


use Mamarmite\UIDEndpoint\Routes\AbstractRoute;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function init_routes() {

    $main_index_params = [
        "endpoint" => MAMARMITE_UID_BASE_ENDPOINT,
        "defaultValueEndpoint" => "",
        "regex" => '^'.MAMARMITE_UID_BASE_ENDPOINT.'/?$',
        "query" => 'index.php?r_id=__uids_base_endpoint__',
        "position" => "top",
        "query_vars" => [""],
    ];
    $index_route = new Routes\AbstractRoute($main_index_params);
    $index_route->init();
}
//\add_action('init', __NAMESPACE__.'\\init_routes');
