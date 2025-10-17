<?php
namespace Mamarmite\UIDEndpoint\Collections;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

class Collection
{
    public $post_type;
    public string $schema_type;
    public $schema_adapter;
    public $categories;

    function __construct($collectionParams)
    {
        $this->post_type = $collectionParams['post_type'];
        $this->schema_type = $collectionParams['schema_type'];
        $this->schema_adapter = $collectionParams['schema_adapter'];
        $this->categories = $collectionParams['categories'];
    }
}
