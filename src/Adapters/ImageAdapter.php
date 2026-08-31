<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Blueprints\Blueprint;
use Mamarmite\UIDEndpoint\Blueprints\MediaBlueprint;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class ImageAdapter
 */
class ImageAdapter extends MediaAdapter
{
    protected string $schemaType = 'ImageObject';
    function __construct(\WP_Post $post, Blueprint $blueprint = null) {
        parent::__construct($post, $blueprint ?? new MediaBlueprint());
    }
}
//ImageObject
