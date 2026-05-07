<?php
/**
 * Plugin Name: Unique ID endpoint
 * Description: Add unique id (UID) endpoint to a wordpress installation.
 * Plugin URI: https://mamarmite.com
 * Version: 0.2.2
 * Author: Marc-André Martin
 * Author URI: https://mamarmite.com
 * Requires PHP: 8.1
 * Requires at least: 6
 * Text Domain: uidendpoint
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

define("MAMARMITE_UID_ENDPOINT_BASE_PATH", \plugin_dir_path( __FILE__ ) );
define("MAMARMITE_UID_ENDPOINT_BASE_URL", \plugin_dir_url( __FILE__ ) );
define("MAMARMITE_UID_ADD_PROTOCOLE", true);
define("MAMARMITE_UID_PROTOCOLE", "http://");//make that dynamic with wp url ?
define("MAMARMITE_UID_DOMAIN", "topo.art");
define("MAMARMITE_UID_BASE_ENDPOINT", "r");
define("MAMARMITE_PREVIEW_ENDPOINT", "preview");
define("MAMARMITE_LDJSON_ENDPOINT", "ldjson");
define("MAMARMITE_UID_QUERY_VAR", "r_uid");
define("MAMARMITE_PREVIEW_QUERY_VAR", "r_uid_preview");
define("MAMARMITE_UID_BASE_QUERYVARS_ENDPOINT", "__uids_base_endpoint__");

require_once \plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

//preview = pour voir le json comme humain
//jsonld = pour avoir le retour en entête json
//base? avec le redirect 303 qui mène vers le jsonld.
