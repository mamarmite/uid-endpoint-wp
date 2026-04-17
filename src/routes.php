<?php
namespace Mamarmite\UIDEndpoint;


use Mamarmite\UIDEndpoint\Routes\AbstractRoute;
use Mamarmite\UIDEndpoint\Routes\RouteType;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

function init_routes() {

    /**
     * $this->name = $endpointParams->name ?? "AbstractRouteName";
     * $this->endpoint = $endpointParams->endpoint;
     *
     *
     * $this->query_var_base_endpoint = $endpointParams->queryVarBaseEndpoint;
     * $this->query_var_base_endpoint_value = $endpointParams->queryVarBaseEndpointValue;
     * $this->regex = $endpointParams->regex;
     * $this->query = $endpointParams->query;
     * $this->position = $endpointParams->position;
     * $this->_query_vars = $endpointParams->query_vars;
     */
    $main_index_params = [
        "name" => "MainIndexUID",
        "endpoint" => MAMARMITE_UID_BASE_ENDPOINT,
        "queryVarBaseEndpoint" => MAMARMITE_UID_BASE_ENDPOINT,
        "queryVarBaseEndpointValue" => MAMARMITE_UID_BASE_ENDPOINT,
        "queryVarBaseEndpointValueType" => RouteType::STATIC_QUERY_VAR_VALUE,
        "defaultValueEndpoint" => MAMARMITE_UID_BASE_QUERYVARS_ENDPOINT,
        "regex" => '^'.MAMARMITE_UID_BASE_ENDPOINT.'/?$',
        "query" => 'index.php?r_id='.MAMARMITE_UID_BASE_QUERYVARS_ENDPOINT,
        "position" => "top",
        "query_vars" => [""],//after the base.
    ];
    $index_route = new Routes\AbstractRoute($main_index_params);
    $index_route->init();
}
//\add_action('init', __NAMESPACE__.'\\init_routes');
