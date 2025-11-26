<?php
namespace Mamarmite\UIDEndpoint;

use Mamarmite\UIDEndpoint\Bridges\PostTypeToPrefixBridge;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class UID {

    static $_pattern = '/^([aepco])([1-9][0-9]*)$/';
    protected $_post_id;
    protected $_post_type;

    public $_post_type_prefix;

    /**
     * @param int $post
     */
    function __construct(\WP_Post $post) {
        $this->_post_id = $post->ID;
        $this->_post_type = $post->post_type;

        $bridge = new PostTypeToPrefixBridge();
        $this->_post_type_prefix = $bridge->from($post->post_type);
    }

    function full() {
        return (MAMARMITE_UID_ADD_PROTOCOLE ? MAMARMITE_UID_PROTOCOLE : "").MAMARMITE_UID_DOMAIN.'/'.MAMARMITE_UID_BASE_ENDPOINT.'/'.$this->_post_type_prefix.$this->_post_id;
    }

    function relative($prepend_slash=true) {
        return ($prepend_slash ? "/" : "").MAMARMITE_UID_BASE_ENDPOINT.'/'.$this->_post_type_prefix.$this->_post_id;
    }

    public static function validate_uid(\WP_Post $post, array $parsed_uid): bool {
        $bridge = new PostTypeToPrefixBridge();
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
