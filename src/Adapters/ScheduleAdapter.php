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
        $this->add_to_schema($schedule, 'startTime', $this->get_field($post_id, $schedule_prefix.'start_time'));
        $this->add_to_schema($schedule, 'endTime', $this->get_field($post_id, $schedule_prefix.'end_time'));
        $this->add_to_schema($schedule, 'repeatFrequency', DateHelper::interval_to_ISO8601($date_delta));
        $this->add_to_schema($schedule, 'scheduleTimezone', $this->get_field($post_id, $schedule_prefix.'schedule_timezone'));

        $byDay = $this->get_field($post_id, $schedule_prefix.'by_day', []);
        if (!empty($byDay) && is_array($byDay)) {
            $this->add_to_schema($schedule, 'byDay', $byDay);
        }

        return $schedule;
    }
}
