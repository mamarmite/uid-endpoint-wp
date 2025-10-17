<?php
namespace Mamarmite\UIDEndpoint\Adapters;

use Mamarmite\UIDEndpoint\Adapters\ArtistAdapter;
use Mamarmite\UIDEndpoint\Adapters\EventAdapter;
use Mamarmite\UIDEndpoint\Adapters\OrganizationAdapter;
use Mamarmite\UIDEndpoint\Adapters\PlaceAdapter;
use Mamarmite\UIDEndpoint\Adapters\CreativeWorkAdapter;


if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class AdapterFactory
 * Factory to create appropriate adapters
 */
class AdapterFactory
{
    protected static array $adapters = [
        'organization' => OrganizationAdapter::class,
        'artist' => ArtistAdapter::class,
        'place' => PlaceAdapter::class,
        'event' => EventAdapter::class,
        'creative_work' => CreativeWorkAdapter::class,
    ];

    /**
     * Create adapter for given post type
     *
     * @param string $postType
     * @return SchemaAdapterInterface
     * @throws \Exception
     */
    public static function create(string $postType): SchemaAdapterInterface
    {
        $postType = strtolower($postType);

        if (!isset(self::$adapters[$postType])) {
            throw new \Exception("No adapter found for post type: {$postType}");
        }

        $adapterClass = self::$adapters[$postType];
        return new $adapterClass();
    }

    /**
     * Register custom adapter
     *
     * @param string $postType
     * @param string $adapterClass
     */
    public static function register(string $postType, string $adapterClass): void
    {
        self::$adapters[strtolower($postType)] = $adapterClass;
    }
}
