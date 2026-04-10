<?php
namespace Mamarmite\UIDEndpoint;

use Mamarmite\UIDEndpoint\Bridges\AbstractBridge;
use Mamarmite\UIDEndpoint\Bridges\PostTypeToPrefixBridge;
use Mamarmite\UIDEndpoint\Bridges\StaticPrefixBridge;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class UID {

    static $_pattern_v0 = '/^([aepco])([1-9][0-9]*)$/';
    static $_pattern = '/^([t])([1-9][0-9]*)$/';
    protected $_post_id;
    protected $_post_type;

    public $_uid_prefix;

    protected AbstractBridge $_bridge;
    static string $bridge_class = StaticPrefixBridge::class;

    /**
     * @param int $post
     */
    function __construct(\WP_Post $post) {
        $this->_post_id = $post->ID;
        $this->_post_type = $post->post_type;

        $this->_bridge = new UID::$bridge_class();
        $this->_uid_prefix = $this->_bridge->from($post->post_type);
    }

    function full() {
        return (MAMARMITE_UID_ADD_PROTOCOLE ? MAMARMITE_UID_PROTOCOLE : "").MAMARMITE_UID_DOMAIN.'/'.MAMARMITE_UID_BASE_ENDPOINT.'/'.$this->_uid_prefix.$this->_post_id;
    }

    function relative($prepend_slash = true) {
        return ($prepend_slash ? "/" : "").MAMARMITE_UID_BASE_ENDPOINT.'/'.$this->_uid_prefix.$this->_post_id;
    }

    public static function validate_uid(\WP_Post $post, array $parsed_uid): bool {
        $bridge = new UID::$bridge_class();
        return $parsed_uid["prefix"] === $bridge->from($post->post_type);
    }

    public static function parse(string $uid):array {

        if (preg_match(self::$_pattern, $uid, $matches)) {
            return [
                'prefix' => $matches[1],
                'post_id' => $matches[2]
            ];
        }
        return [];
    }
}
