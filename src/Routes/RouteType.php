<?php
namespace Mamarmite\UIDEndpoint\Routes;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

enum RouteType {
    case DYNAMIC;
    case STATIC_QUERY_VAR_VALUE;
}
