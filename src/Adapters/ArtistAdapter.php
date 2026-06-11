<?php

namespace Mamarmite\UIDEndpoint\Adapters;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class ArtistAdapter
 * Maps to schema.org Person type
 */
class ArtistAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'Person';
    protected string $schemaGroupKey = 'group_schema_artist';

    protected string $prefix = "a";//artist-agent

    protected string $schema_type_label = 'Artiste';

    function __construct(\WP_Post $post, $schema_allow_list=[])
    {
        $this->default_allow_list = [
            "alternateName" => true,
            "url" => true,
            "additionalType" => true,
            "inLanguage" => true,
            "disambiguatingDescription" => true,
            "mainEntityOfPage" => true,
            "sameAs" => true,
            "hasOccupation" => true,
            "address" => true,
            "image" => true
        ];
        parent::__construct($post, $schema_allow_list);
    }


    public function transform(): array
    {
        parent::transform();
        $schema = $this->build_base_schema($this->post);

        $this->add_to_schema($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_to_schema($schema, 'description', \get_the_excerpt($this->post));
        $this->add_to_schema($schema, 'url', get_permalink($this->post->ID));
        //$this->add_to_schema($schema, 'image', $this->get_field($this->post->ID, 'image'));
        $this->add_to_schema($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));
        $this->add_to_schema($schema, 'inLanguage', $this->current_language);
        $this->add_to_schema($schema, 'disambiguatingDescription', $this->disambiguatingDescription($this->post->ID));

        // Address
        if (array_key_exists('address', $this->allow_list)){
            $address = $this->build_address($this->post->ID);
            if ($address) {
                $schema['address'] = $address;
            }
        }

        // Identifier
        if (array_key_exists('identifier', $this->allow_list)) {
            $identifier = $this->build_identifier($this->post->ID);
            if ($identifier) {
                $schema['identifier'] = $identifier;
            }
        }
        // Occupation
        if (array_key_exists('hasOccupation', $this->allow_list)) {
            $occupation = $this->build_occupation($this->post->ID);
            if (!empty($occupation)) {
                $schema['hasOccupation'] = $occupation;
            }
        }

        // SameAs
        if (array_key_exists('sameAs', $this->allow_list)) {
            $sameAs = $this->build_same_as($this->post->ID);
            if (!empty($sameAs)) {
                $schema['sameAs'] = $sameAs;
            }
        }

        //Media Object
        if (array_key_exists('image', $this->allow_list)) {
            $image = $this->build_image();
            if (!empty($image)) {
                $schema['image'] = $image;
            }
        }

        return $schema;
    }

    protected function build_identifier(int $post_id): ?array
    {
        $propertyId = $this->get_field($post_id, 'identifier_property_id');
        $value = $this->get_field($post_id, 'identifier_value');

        if (empty($propertyId) || empty($value)) {
            return null;
        }

        return [
            '@type' => 'PropertyValue',
            'propertyID' => $propertyId,
            'value' => $value,
        ];
    }

    protected function build_occupation(int $post_id): array
    {
        $occupations = [];
        $occupationRepeater = $this->get_field($post_id, 'occupations', []);

        if (is_array($occupationRepeater)) {
            foreach ($occupationRepeater as $occ) {
                if (isset($occ['name'])) {
                    $occupation = ['name' => $occ['name']];
                    if (!empty($occ['description'])) {
                        $occupation['description'] = $occ['description'];
                    }
                    $occupations[] = $occupation;
                }
            }
        }

        return $occupations;
    }

    protected function disambiguatingDescription(int $post_id):string {

        $field_prefix = "address";

        $based_at_prefix = " basé à ";
        $based_at = "";
        $this->concatenate_if_not_empty($based_at, $this->get_field($post_id, $field_prefix . '_address_locality'));
        $this->concatenate_if_not_empty($based_at, $this->get_field($post_id, $field_prefix . '_address_region'), ["prefix"=> ", "]);
        $this->concatenate_if_not_empty($based_at, $this->get_field($post_id, $field_prefix . '_address_country'), ["prefix"=> " (", "suffix"=> ")"]);
        //artiste basé à
        return !empty($based_at) ? $this->schema_type_label.$based_at_prefix.$based_at : "";
    }

    protected function concatenate_if_not_empty(string|bool &$str, string $target_value, array $params = []): void {
        $default_params = ["prefix"=>"", "suffix"=>""];
        $params = array_merge($default_params, $params);

        if (!empty($target_value)) {
            $str .= $params["prefix"].$target_value.$params["suffix"];
        }
    }
}
