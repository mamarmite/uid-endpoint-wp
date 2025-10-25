<?php
/**
 * Plugin Name: Unique ID endpoint
 * Description: Add unique id (UID) endpoint to a wordpress installation.
 * Plugin URI: https://mamarmite.com
 * Version: 0.0.1
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
define("MAMARMITE_UID_DOMAIN", "topo.art");
define("MAMARMITE_UID_BASE_ENDPOINT", "r");

require_once \plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
/*
require_once \plugin_dir_path( __FILE__ ) . 'src/Templates/BaseEndpointTemplate.php';
require_once \plugin_dir_path( __FILE__ ) . 'src/Templates/DefaultTemplate.php';
require_once \plugin_dir_path( __FILE__ ) . 'src/Renderers/DefaultRenderer.php';
require_once \plugin_dir_path( __FILE__ ) . 'src/Handlers/BaseUIDHandler.php';
require_once \plugin_dir_path( __FILE__ ) . 'src/Handlers/Entity.php';

require_once \plugin_dir_path( __FILE__ ) . 'src/handlers.php';
require_once \plugin_dir_path( __FILE__ ) . 'src/rewrite.php';*/

/**
 * @todo : Add default field value from ACF and/or Objects in the adapter pattern.
 * @todo : Loop through object to
 */
