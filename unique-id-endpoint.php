<?php
/**
 * Plugin Name: Unique ID endpoint
 * Description: Add unique id (UID) endpoint to a wordpress installation.
 * Plugin URI: https://mamarmite.com
 * Version: 0.3.2
 * Author: Marc-André Martin
 * Author URI: https://mamarmite.com
 * Requires PHP: 8.1
 * Requires at least: 6
 * Text Domain: mamarmite_uid_endpoint
 *
 * @package UIDEndpoint
 * @category Core
 *
 * UID endpoint was build for the first time within a client project with Topo.art and CAPACOA to add linkeddata to a Wordpress site and be compatible with the Artsdata ontologies and knowledge graph.
 */
namespace Mamarmite\UIDEndpoint;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}


// ACF Installed Wall.
\add_action( 'plugins_loaded', __NAMESPACE__.'\\load_uid_files_if_acf_installed' );

function load_uid_files_if_acf_installed() {
    if ( ! class_exists( 'ACF' ) ) {
        \add_action( 'admin_notices', __NAMESPACE__.'\\mamarmite_uid_endpoint_no_acf_notice' );
        \deactivate_plugins( plugin_basename( __FILE__ ) );
        return;
    }

    require_once \plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
}

function mamarmite_uid_endpoint_no_acf_notice() {
    echo '<div class="notice notice-error"><p>';
    echo esc_html__( 'Unique ID endpoint requiert Advanced Custom Fields actif.', 'mamarmite_uid_endpoint' );
    echo '</p></div>';
}

define("MAMARMITE_UID_PLUGIN_NAME", "Identifiant unique et pérenne" );
define("MAMARMITE_UID_ENDPOINT_BASE_PATH", \plugin_dir_path( __FILE__ ) );
define("MAMARMITE_UID_ENDPOINT_BASE_URL", \plugin_dir_url( __FILE__ ) );
define("MAMARMITE_UID_ADD_PROTOCOLE", true);
define("MAMARMITE_UID_PROTOCOLE", "http://");//make that dynamic with wp url ?
define("MAMARMITE_UID_DOMAIN", "topo.art");
define("MAMARMITE_UID_BASE_ENDPOINT", "r");
define("MAMARMITE_UID_PREFIX", "http://topo.art/r/t");

define("MAMARMITE_UID_PLUGIN_BASE_ENDPOINT", "uid");
define("MAMARMITE_UID_PLUGIN_PREVIEW_ENDPOINT", "preview");
define("MAMARMITE_UID_PLUGIN_LDJSON_ENDPOINT", "ldjson");
define("MAMARMITE_UID_PLUGIN_LIST_ENDPOINT", "list");
define("MAMARMITE_UID_PLUGIN_LISTJSON_ENDPOINT", "list.json");

define("MAMARMITE_UID_QUERY_VAR", "uid");

define("MAMARMITE_UID_BASE_QUERYVARS_ENDPOINT", "__uid_endpoint_index__");
define("MAMARMITE_UID_PREVIEW_QUERYVARS_ENDPOINT", "__uid_endpoint_preview__");
define("MAMARMITE_UID_LDJSON_QUERYVARS_ENDPOINT", "__uid_endpoint_json__");
define("MAMARMITE_UID_LIST_QUERYVARS_ENDPOINT", "__uid_endpoint_list__");
define("MAMARMITE_UID_LISTJSON_QUERYVARS_ENDPOINT", "__uid_endpoint_list_json__");

define("MAMARMITE_UID_CONTEXT", "http://schema.org");
