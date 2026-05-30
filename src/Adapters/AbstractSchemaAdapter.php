<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\UID;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class AbstractSchemaAdapter
 * Base adapter with common functionality
 */
abstract class AbstractSchemaAdapter implements SchemaAdapterInterface
{
    protected string $schemaType;
    protected string $schemaGroupKey;//acf
    protected string $prefix = "";
    protected string $context = 'http://schema.org';

    protected array $default_allow_list = [];
    protected array $allow_list;

    protected string $current_language;

    public $uid;
    public \WP_Post $post;
    public \WP_Post_Type $post_type;

    function __construct(\WP_Post $post, $schema_allow_list=[]) {
        $this->post = $post;
        $this->post_type = get_post_type_object($post->post_type);
        $this->uid = new UID($post);

        $current_language = preg_split('/_/', get_locale());
        $this->current_language = $current_language ? $current_language[0] : get_locale();

        $this->allow_list = !empty($schema_allow_list) ? $schema_allow_list : $this->default_allow_list ;
    }

    public function transform(): array {
        return [];
    }

    public function sub_entity_transform():array {
        return $this->transform();//default as if we need to be full for subentity.
    }

    /**
     * Get ACF field value with fallback
     *
     * @param int $post_id
     * @param string $field_name
     * @param mixed $default
     * @return mixed
     */
    protected function get_field(int $post_id, string $field_name, $default = ''):mixed
    {
        if (function_exists('get_field')) {
            $value = \get_field( $field_name, $post_id);
            return $value !== false && $value !== null ? $value : $default;
        }
        return $default;
    }

    /**
     * Get ACF field by group Key. Not for sub field
     *
     * @param int $post_id
     * @param string $field_name
     * @param mixed $default
     * @return mixed
     */
    protected function get_first_level_field(int $post_id, string $field_name, $default = ''):mixed
    {
        $value = $this->get_field_by_group($post_id, $field_name, $default);
        return $value !== false && $value !== null ? $value : $default;
    }

    /**
     * Get target field from groups field key sets in Adapter.
     *
     * @param $post_id
     * @param $field_name
     * @param $default
     * @return false|mixed
     */
    function get_field_by_group( $post_id, $field_name, $default = '' ) {
        if ( ! function_exists( 'acf_get_fields' ) ) {
            return false;
        }

        // Get all fields from the specific group
        $fields = \acf_get_fields( $this->schemaGroupKey );

        if ( ! $fields ) {
            return false;
        }

        if (function_exists('get_field')) {
            // Find the field by name and get its key
            foreach ( $fields as $field ) {
                if ( $field['name'] === $field_name ) {
                    // Use the field key to get the value (this ensures we get the right field)
                    return $field;
                }
            }
        }

        return false;
    }
    /**
     * Get target field from groups field key sets in Adapter.
     *
     * @param $post_id
     * @param $field_name
     * @param $default
     * @return false|mixed
     */
    function get_field_value_by_group( $post_id, $field_name, $default = '' ) {
        if ( ! function_exists( 'acf_get_fields' ) ) {
            return false;
        }

        // Get all fields from the specific group
        $fields = \acf_get_fields( $this->schemaGroupKey );

        if ( ! $fields ) {
            return false;
        }

        if (function_exists('get_field')) {
            // Find the field by name and get its key
            foreach ( $fields as $field ) {
                if ( $field['name'] === $field_name ) {
                    // Use the field key to get the value (this ensures we get the right field)
                    return \get_field($post_id, $field['key'], $default );
                }
            }
        }

        return false;
    }

    /**
     * Build base schema structure
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function build_base_schema(\WP_Post $post): array
    {
        $name = $this->get_field($this->post->ID, 'name', $this->post->post_title);
        return [
            '@type' => $this->schemaType,
            '@id' => $this->uid->full(),
            'name' => $name,
        ];
    }

    /**
     * Add optional field if it exists
     *
     * @param array $schema
     * @param string $key
     * @param mixed $value
     * @return void
     */
    protected function add_to_schema(array &$schema, string $key, $value): void
    {
        if (!empty($value) && array_key_exists($key, $this->allow_list)) {
            $schema[$key] = $value;
        }
    }

    /**
     * Build address schema
     *
     * @param int $post_id
     * @param string $field_prefix
     * @return array|null
     */
    protected function build_address(int $post_id, string $field_prefix = 'address'): ?array
    {
        $street = $this->get_field($post_id, $field_prefix . '_street_address');
        $locality = $this->get_field($post_id, $field_prefix . '_address_locality');

        if (empty($street) && empty($locality)) {
            return null;
        }

        $address = [];//'@type' => 'PostalAddress',

        $this->add_to_schema($address, '@type', $this->get_field($post_id, $field_prefix . '_type'));
        $this->add_to_schema($address, 'streetAddress', $street);
        $this->add_to_schema($address, 'addressLocality', $locality);
        $this->add_to_schema($address, 'addressRegion', $this->get_field($post_id, $field_prefix . '_address_region'));
        $this->add_to_schema($address, 'postalCode', $this->get_field($post_id, $field_prefix . '_postal_code'));
        $this->add_to_schema($address, 'addressCountry', $this->get_field($post_id, $field_prefix . '_address_country'));

        return $address;
    }

    /**
     * Build image array from the featured_image post
     *
     * @return array
     */
    protected function build_image($allow_list=[]): array
    {
        $img = [];
        $has_post_thumbnail = \has_post_thumbnail($this->post);
        if($has_post_thumbnail){
            $mediaAdapter = new MediaAdapter($this->post, $allow_list);
            $img = $mediaAdapter->transform();
        }
        return $img;
    }

    /**
     * Build sameAs array from repeater or array field
     *
     * @param int $post_id
     * @param string $field_name
     * @return array
     */
    protected function build_same_as(int $post_id, string $field_name = 'same_as'): array
    {
        $sameAs = [];
        $values = $this->get_field($post_id, $field_name, []);

        if (is_array($values)) {
            foreach ($values as $value) {
                if (is_array($value) && isset($value['url'])) {
                    $sameAs[] = $value['url'];
                } elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
                    $sameAs[] = $value;
                }
            }
        }

        return $sameAs;
    }

    public function get_schema_type(): string
    {
        return $this->schemaType;
    }

    public function validate(array $data): bool
    {
        return isset($data['@type']) && isset($data['name']);
    }

    public function override_allow_list($allow_list): array {
        $this->allow_list = $allow_list;
        return $this->allow_list;
    }

    public function remove_from_allow_list(array $deny_list): array {
        return $this->diff_allow_list($this->allow_list, $deny_list);
    }

    private function diff_allow_list(array $allow, array $deny): array {
        foreach ($deny as $key => $deny_value) {
            if (!array_key_exists($key, $allow)) {
                continue;
            }

            if (is_array($deny_value) && is_array($allow[$key])) {
                // both are subtrees → recurse
                $allow[$key] = $this->diff_allow_list($allow[$key], $deny_value);

                // prune the parent
                if (empty($allow[$key])) {
                    unset($allow[$key]);
                }
            } else {
                // deny is a leaf (true) → remove the whole entry
                unset($allow[$key]);
            }
        }
        return $allow;
    }
}
