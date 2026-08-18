<?php

namespace Mamarmite\UIDEndpoint\Adapters;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class ImageAdapter
 */
class ImageAdapter extends MediaAdapter
{
    protected string $schemaType = 'ImageObject';
    function __construct(\WP_Post $post, $schema_allow_list=[]) {
        parent::__construct($post, $schema_allow_list);
    }
}
//ImageObject
