<?php
namespace Mamarmite\UIDEndpoint\Routes;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

class MainIndexRoute extends AbstractRoute {

    public function __construct($endpointParams)
    {
        parent::__construct($endpointParams);
    }

    public function on_resolve_handler($context=[]) {

    }
}
