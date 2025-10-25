<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use \Mamarmite\UIDEndpoint\Adapters\AbstractSchemaAdapter;

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
    protected string $prefix = "a";//artist-agent

    function __construct(string $postType, \WP_Post $post = null)
    {
        parent::__construct($postType, $post);
    }


    public function transform(): array
    {
        parent::transform();
        $schema = $this->buildBaseSchema($this->post);

        $this->addIfNotEmpty($schema, 'alternateName', $this->getField($this->post->ID, 'alternate_name'));
        $this->addIfNotEmpty($schema, 'url', $this->getField($this->post->ID, 'url', get_permalink($this->post->ID)));
        $this->addIfNotEmpty($schema, 'image', $this->getField($this->post->ID, 'image'));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($this->post->ID, 'additionalType'));
        $this->addIfNotEmpty($schema, 'inLanguage', $this->getField($this->post->ID, 'inLanguage'));
        $this->addIfNotEmpty($schema, 'disambiguatingDescription', $this->getField($this->post->ID, 'disambiguatingDescription'));

        // Address
        $address = $this->buildAddress($this->post->ID);
        if ($address) {
            $schema['address'] = $address;
        }

        // Identifier
        $identifier = $this->buildIdentifier($this->post->ID);
        if ($identifier) {
            $schema['identifier'] = $identifier;
        }

        // Occupation
        $occupation = $this->buildOccupation($this->post->ID);
        if (!empty($occupation)) {
            $schema['hasOccupation'] = $occupation;
        }

        // SameAs
        $sameAs = $this->buildSameAs($this->post->ID);
        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        return $schema;
    }

    protected function buildIdentifier(int $post_id): ?array
    {
        $propertyId = $this->getField($post_id, 'identifier_property_id');
        $value = $this->getField($post_id, 'identifier_value');

        if (empty($propertyId) || empty($value)) {
            return null;
        }

        return [
            '@type' => 'PropertyValue',
            'propertyID' => $propertyId,
            'value' => $value,
        ];
    }

    protected function buildOccupation(int $post_id): array
    {
        $occupations = [];
        $occupationRepeater = $this->getField($post_id, 'occupations', []);

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
}
