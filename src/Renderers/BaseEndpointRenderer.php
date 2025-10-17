<?php

namespace Mamarmite\UIDEndpoint\Renderers;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}


function render_base_endpoint_template() {

    \load_template(\plugin_dir_path( __FILE__ )."../templates/BaseEndpointTemplate.php");
    exit;
}
