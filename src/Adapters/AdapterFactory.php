<?php
namespace Mamarmite\UIDEndpoint\Adapters;


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
        'artiste' => ArtistAdapter::class,
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
    public static function create(\WP_Post $post): SchemaAdapterInterface
    {
        $postType = strtolower($post->post_type);
        $adapterClass = self::$adapters[$postType];
        return new $adapterClass($postType, $post);
    }

    public static function transfrom(\WP_Post $post): array
    {
        $schemaAdapter = self::create($post);
        return $schemaAdapter->transform();
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
