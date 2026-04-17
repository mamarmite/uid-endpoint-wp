<?php
namespace Mamarmite\UIDEndpoint\Routes;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

//Routes indexes values

$MAIN_INDEX_ROOT_VALUE = "__uids_base_endpoint__";

class AbstractRoute {

    public $name;
    public $endpoint;
    public $query_var_base_endpoint;
    public $query_var_base_endpoint_value;
    public $query_var_base_endpoint_value_type;


    public $regex;
    public $query;
    public $position;

    public $_query_vars = [];

    public function __construct($endpointParams) {
        $this->name = $endpointParams->name ?? "AbstractRouteName";
        $this->endpoint = $endpointParams->endpoint;

        $this->query_var_base_endpoint = $endpointParams->queryVarBaseEndpoint;
        $this->query_var_base_endpoint_value = $endpointParams->queryVarBaseEndpointValue;
        $this->query_var_base_endpoint_value_type = $endpointParams->queryVarBaseEndpointValueType;

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

    public function on_resolve_handler($context=[]) {
        echo "resolving this handler ".$this->name;
    }
}
