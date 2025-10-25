<?php

namespace Mamarmite\UIDEndpoint\Renderers;


if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}


function render_base_endpoint_template() {

    $baseTemplate = new BaseEndpointTemplate();
    $baseTemplate->render(null);
    exit;
}
