<?php
namespace Mamarmite\UIDEndpoint;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Base rewrite with /r/
 * @todo add alert of a page or another entity already have this slug.
 * @return void
 */
function add_uid_endpoint()
{
    // Index endpoint
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'?$',
        'index.php?uid_plugin_endpoint='.MAMARMITE_UID_BASE_QUERYVARS_ENDPOINT,
        'top'
    );

    // PREVIEW endpoint
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/'.MAMARMITE_UID_PLUGIN_PREVIEW_ENDPOINT.'?$',//'?([^/]+)/$'
        'index.php?uid_plugin_endpoint='.MAMARMITE_UID_PREVIEW_QUERYVARS_ENDPOINT,
        'top'
    );

    // LDJSON endpoint
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/'.MAMARMITE_UID_PLUGIN_LDJSON_ENDPOINT.'?$',//'?([^/]+)/$'
        'index.php?uid_plugin_endpoint='.MAMARMITE_UID_LDJSON_QUERYVARS_ENDPOINT,
        'top'
    );

    // LIST endpoint
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/'.MAMARMITE_UID_PLUGIN_LIST_ENDPOINT.'?$',//'?([^/]+)/$'
        'index.php?uid_plugin_endpoint='.MAMARMITE_UID_LIST_QUERYVARS_ENDPOINT,
        'top'
    );

    // LIST endpoint
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_PLUGIN_BASE_ENDPOINT.'/'.MAMARMITE_UID_PLUGIN_LISTJSON_ENDPOINT.'?$',//'?([^/]+)/$'
        'index.php?uid_plugin_endpoint='.MAMARMITE_UID_LISTJSON_QUERYVARS_ENDPOINT,
        'top'
    );
}
\add_action('init', __NAMESPACE__.'\\add_uid_endpoint');

/**
 * Filter to add our query vars into uri params.
 * @param $vars
 * @return mixed
 */
function add_entity_id_query_vars($vars) {
    $vars[] = 'uid_plugin_endpoint';
    $vars[] = 'r_id';
    $vars[] = 'uid';
    //$vars[] = 'r_preview';
    //$vars[] = 'r_data';
    return $vars;
}
\add_filter('query_vars',  __NAMESPACE__.'\\add_entity_id_query_vars');

/**
 * On plugin activation, we flush the rewrite rule to apply ours.
 * @return void
 */
function flush_rewrite_rules_on_activation() {
    add_uid_endpoint();
    \flush_rewrite_rules();
}
\register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');
