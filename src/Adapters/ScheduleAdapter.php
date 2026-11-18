<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use  Mamarmite\UIDEndpoint\Helpers\DateHelper;
use Mamarmite\UIDEndpoint\Blueprints\Blueprint;
use Mamarmite\UIDEndpoint\Blueprints\ScheduleBlueprint;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

/**
 * Class ScheduleAdapter
 */
class ScheduleAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'Schedule';

    protected string $field_prefix = 'event_schedule';
    protected string $schemaGroupKey = 'group_schema_address';
    protected string $prefix = "";

    function __construct(\WP_Post $post, Blueprint $blueprint = null)
    {
        parent::__construct($post, $blueprint ?? new ScheduleBlueprint());
    }

    /**
     * Build base schema structure
     *
     * @param \WP_Post $post
     * @return array
     */
    protected function build_base_schema(\WP_Post $post, bool $isSchemaRoot = false): array
    {
        return [
            '@type' => $this->schemaType,
        ];

    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $schedule = $this->build_base_schema($this->post, $isSchemaRoot);

        $post_id = $this->post->ID;

        $schedule_prefix = $this->field_prefix."_";

        $start_date = $this->get_field($post_id, $schedule_prefix.'start_date');
        $end_date = $this->get_field($post_id, $schedule_prefix.'end_date');

        $start_time = $this->get_field($post_id, $schedule_prefix.'start_time');
        $end_time = $this->get_field($post_id, $schedule_prefix.'end_time');

        if (empty($start_date) || empty($end_date) || empty($start_time) || empty($end_time)) {
            return [];
        }

        $start_date_obj = new \DateTime($start_date);
        $end_date_obj = new \DateTime($end_date);
        $date_delta = $end_date_obj->diff($start_date_obj);

        $date_interval = 0;

        $this->add_to_schema($schedule, 'startDate', $start_date);
        $this->add_to_schema($schedule, 'endDate', $end_date);
        $this->add_to_schema($schedule, 'startTime', $start_time);
        $this->add_to_schema($schedule, 'endTime', $end_time);
        $this->add_to_schema($schedule, 'duration', $this->schedule_iso_duration($start_time, $end_time));
        $this->add_to_schema($schedule, 'scheduleTimezone', $this->get_field($post_id, $schedule_prefix.'schedule_timezone'));


        $repeat_frequency = $this->get_field($post_id, $schedule_prefix.'repeat_frequency');

        if (in_array($repeat_frequency, ['D', 'W', 'M', 'Y'], true)) {
            $repeat_interval = $this->get_field($post_id, $schedule_prefix.'repeat_interval');
            $interval = max(1, (int)($repeat_interval ?? 1));

            //$this->add_to_schema($schedule, 'repeatFrequency', DateHelper::interval_to_ISO8601($date_delta));
            $this->add_to_schema($schedule, 'repeatFrequency', "P{$interval}{$repeat_frequency}");

            $by_day = $this->get_field($post_id, $schedule_prefix.'by_day');
            if ($repeat_frequency !== 'D' && !empty($by_day) && is_array($by_day)) {
                $this->add_to_schema($schedule, 'byDay', $by_day);
            }

            if (in_array($repeat_frequency, ['M', 'Y'], true)) {
                $this->add_to_schema($schedule, 'byMonthWeek', $this->get_field($post_id, $schedule_prefix.'by_month_week'));
                $this->add_to_schema($schedule, 'byMonthDay', $this->get_field($post_id, $schedule_prefix.'by_month_day'));
                $this->add_to_schema($schedule, 'byMonth', $this->get_field($post_id, $schedule_prefix.'by_month'));
            }
        }

        $this->add_to_schema($schedule, 'repeatCount', intval($this->get_field($post_id, $schedule_prefix.'repeat_count')));

        //need acf pro it's param as a repeater.
        $this->add_to_schema($schedule, 'exceptDate', $this->get_field($post_id, $schedule_prefix.'except_date'));

        return $schedule;
    }

    /** Les checkbox ACF renvoient des chaînes : schema.org attend des Integer. */
    function schedule_ints(array $values): array
    {
        $ints = array_map('intval', $values);
        sort($ints);
        return $ints;
    }

    function schedule_iso_duration(string $start, string $end): string
    {
        $diff = (int)((strtotime($end) - strtotime($start)) / 60);
        if ($diff <= 0) {
            $diff += 1440; // l'occurrence se termine après minuit
        }
        $h = intdiv($diff, 60);
        $m = $diff % 60;
        return 'PT' . ($h ? "{$h}H" : '') . ($m ? "{$m}M" : '');
    }

}
