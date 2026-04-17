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
    // Handle /r/
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_BASE_ENDPOINT.'/?$',
        'index.php?r_id=__uids_base_endpoint__', // Use a special value to detect empty case
        'top'
    );

    // target entity by r_id (@id).
    \add_rewrite_rule(
        '^'.MAMARMITE_UID_BASE_ENDPOINT.'/([^/]+)/?$',
        'index.php?r_id=$matches[1]',
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
    $vars[] = 'r_id';
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
