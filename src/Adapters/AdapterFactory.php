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
        'programmation' => EventAdapter::class,

        'creative_work' => CreativeWorkAdapter::class,
        'production' => CreativeWorkAdapter::class,
    ];

    /**
     * Create adapter for given post type
     *
     * @param string $postType
     * @return SchemaAdapterInterface
     */
    public static function create(\WP_Post $post): SchemaAdapterInterface|null
    {
        $postType = strtolower($post->post_type);
        if (self::is_supported($post)) {
            $adapterClass = self::$adapters[$postType];
            return new $adapterClass($post);
        }
        return null;
    }

    public static function transfrom(\WP_Post $post): array
    {
        $schemaAdapter = self::create($post);
        return $schemaAdapter->transform();
    }

    /**
     * Check if target post is supported in current implementation.
     * @param \WP_Post $post
     * @return bool
     */
    public static function is_supported(\WP_Post $post): bool {
        if ($post AND $post->post_type) {
            return array_key_exists($post->post_type, static::$adapters);
        }
        return false;
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
