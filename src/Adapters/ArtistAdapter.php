<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Adapters\AbstractSchemaAdapter;

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

    public function transform(\WP_Post $post): array
    {
        $schema = $this->buildBaseSchema($post);

        $this->addIfNotEmpty($schema, 'alternateName', $this->getField($post->ID, 'alternate_name'));
        $this->addIfNotEmpty($schema, 'url', $this->getField($post->ID, 'url', get_permalink($post->ID)));
        $this->addIfNotEmpty($schema, 'image', $this->getField($post->ID, 'image'));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($post->ID, 'additional_type'));
        $this->addIfNotEmpty($schema, 'disambiguatingDescription', $this->getField($post->ID, 'disambiguating_description'));

        // Address
        $address = $this->buildAddress($post->ID);
        if ($address) {
            $schema['address'] = $address;
        }

        // Identifier
        $identifier = $this->buildIdentifier($post->ID);
        if ($identifier) {
            $schema['identifier'] = $identifier;
        }

        // Occupation
        $occupation = $this->buildOccupation($post->ID);
        if (!empty($occupation)) {
            $schema['hasOccupation'] = $occupation;
        }

        // SameAs
        $sameAs = $this->buildSameAs($post->ID);
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
