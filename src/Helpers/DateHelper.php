<?php

namespace Mamarmite\UIDEndpoint\Helpers;

use DateInterval;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class DateHelper {

    static function interval_to_ISO8601(DateInterval $date_interval): string {
        $result = 'P';

        // Date part
        if ($date_interval->y) $result .= $date_interval->y . 'Y';
        if ($date_interval->m) $result .= $date_interval->m . 'M';
        if ($date_interval->d) $result .= $date_interval->d . 'D';

        // Time part
        $timePart = '';
        if ($date_interval->h) $timePart .= $date_interval->h . 'H';
        if ($date_interval->i) $timePart .= $date_interval->i . 'M';
        if ($date_interval->s) $timePart .= $date_interval->s . 'S';

        if ($timePart) {
            $result .= 'T' . $timePart;
        }

        // If everything is zero, return the minimum
        if ($result === 'P') {
            $result = 'PT0S';
        }

        return $result;
    }
}
