<?php
namespace Mamarmite\UIDEndpoint\Routes;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

//Routes indexes values

$MAIN_INDEX_ROOT_VALUE = "__uids_base_endpoint__";

class AbstractRoute {

    public $endpoint;
    public $query_var_base_endpoint;
    public $query_var_base_endpoint_value;


    public $regex;
    public $query;
    public $position;

    public $_query_vars = [];

    public function __construct($endpointParams) {
        $this->endpoint = $endpointParams->endpoint;

        $this->query_var_base_endpoint = $endpointParams->query_var_base_endpoint;
        $this->query_var_base_endpoint_value = $endpointParams->query_var_base_endpoint_value;

        $this->regex = $endpointParams->regex;
        $this->query = $endpointParams->query;
        $this->position = $endpointParams->position;
        $this->_query_vars = $endpointParams->query_vars;
    }

    public function init() {
        $this->register_actions();
        $this->register_filters();
    }

    /**
     * Register the route into
     * @return void
     */
    public function register_actions() {
        \add_rewrite_rule($this->regex, $this->query, $this->position);
    }

    /**
     * Register the route into
     * @return void
     */
    public function register_filters() {
        \add_filter('query_vars',  [$this, '_filter_add_query_vars']);
    }

    public function _filter_add_query_vars($vars) {
        if (!empty($this->_query_vars)) {
            $vars = array_merge($this->_query_vars, $vars);
        }
        return $vars;
    }
}
