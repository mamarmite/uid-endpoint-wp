<?php

namespace Mamarmite\UIDEndpoint\Adapters;
use DateInterval;
use Mamarmite\UIDEndpoint\Adapters\AbstractSchemaAdapter;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}


/**
 * Class EventAdapter
 */
class EventAdapter extends AbstractSchemaAdapter
{
    protected string $schemaType = 'Event';
    protected string $schemaGroupKey = 'group_schema_event';
    protected string $prefix = "e";

    function __construct(\WP_Post $post)
    {
        parent::__construct($post);
    }

    public function transform(): array
    {
        $schema = $this->build_base_schema($this->post);
        $date_format = "Y-m-d";
        $start_date_str = $this->get_field($this->post->ID, 'start_date');
        $end_date_str = $this->get_field($this->post->ID, 'end_date');
        $timezone_string = $this->get_field($this->post->ID, 'timezone');
        $timezone = null;
        $end_date =  null;
        $start_date =  null;
        $end_date_utc =  null;
        $start_date_utc =  null;

        if (!empty($timezone_string)) {
            $timezone = new \DateTimeZone($timezone_string);
        }

        if (!empty($start_date_str)) {
            $start_date = new \DateTimeImmutable($start_date_str, $timezone);
            $start_date_utc = $start_date->setTimezone(new \DateTimeZone('UTC'));
            $this->add_if_not_empty($schema, 'startDate', $start_date_utc->format("c"));
        }
        if (!empty($end_date_str)) {
            $end_date = new \DateTimeImmutable($end_date_str, $timezone);
            $end_date_utc = $end_date->setTimezone(new \DateTimeZone('UTC'));
            $this->add_if_not_empty($schema, 'endDate', $end_date_utc->format("c"));
        }

        $this->add_if_not_empty($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_if_not_empty($schema, 'description', $this->get_field($this->post->ID, 'description', \get_the_excerpt($this->post->ID)));
        $this->add_if_not_empty($schema, 'url', get_permalink($this->post->ID));
        $this->add_if_not_empty($schema, 'image', $this->get_field($this->post->ID, 'image'));
        $this->add_if_not_empty($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));
        $this->add_if_not_empty($schema, 'keywords', $this->get_field($this->post->ID, 'keywords'));
        $this->add_if_not_empty($schema, 'eventStatus', $this->get_field($this->post->ID, 'event_status', 'https://schema.org/EventScheduled'));
        $this->add_if_not_empty($schema, 'eventAttendanceMode', $this->get_field($this->post->ID, 'event_attendance_mode'));
        $this->add_if_not_empty($schema, 'isAccessibleForFree', $this->get_field($this->post->ID, 'is_accessible_for_free'));

        // Event Schedule
        $schedule = $this->build_event_schedule($this->post->ID);
        if ($schedule) {
            $schema['eventSchedule'] = $schedule;
        }

        // Location
        $location = $this->build_location($this->post->ID);
        if (!empty($location)) {
            $schema['location'] = $location;
        }

        // Organizer
        $organizer = $this->build_organizer($this->post->ID);
        if (!empty($organizer)) {
            $schema['organizer'] = $organizer;
        }

        // Work Featured
        $workFeatured = $this->build_work_featured($this->post->ID);
        if (!empty($workFeatured)) {
            $schema['workFeatured'] = $workFeatured;
        }

        $image = $this->build_image();

        if (!empty($image)) {
            $schema['image'] = $image;
        }

        return $schema;
    }

    protected function build_event_schedule(int $post_id): ?array
    {
        try {
            $schedule_field = "event_schedule";
            $schedule_prefix = $schedule_field."_";

            $start_date = $this->get_field($post_id, $schedule_prefix.'start_date');
            $end_date = $this->get_field($post_id, $schedule_prefix.'end_date');

            $start_time = $this->get_field($post_id, $schedule_prefix.'start_time');
            $end_time = $this->get_field($post_id, $schedule_prefix.'end_time');

            if (empty($start_date) || empty($end_date) || empty($start_time) || empty($end_time)) {
                return null;
            }

            $start_date_obj = new \DateTime($start_date);
            //set time
            //set timezone

            $end_date_obj = new \DateTime($end_date);

            //set time
            //set timezone
            $date_delta = $end_date_obj->diff($start_date_obj);


            $schedule = [
                '@type' => 'Schedule',
                'startDate' => $start_date,
                'endDate' => $end_date,
                'startTime' => $start_time,
                'endTime' => $end_time,
            ];

            //change date to DateTime with date + time + tz
            //setup iso foramt
            //demand TZ or default UTC-5
            //create interval with both datetime
            //convert interval to ISO 8601

            $date_interval = 0;

            $this->add_if_not_empty($schedule, 'startTime', $start_time);
            $this->add_if_not_empty($schedule, 'endTime', $end_time);
            $this->add_if_not_empty($schedule, 'repeatFrequency', $this->interval_to_ISO8601($date_delta));
            $this->add_if_not_empty($schedule, 'startTime', $this->get_field($post_id, $schedule_prefix.'start_time'));
            $this->add_if_not_empty($schedule, 'endTime', $this->get_field($post_id, $schedule_prefix.'end_time'));
            $this->add_if_not_empty($schedule, 'scheduleTimezone', $this->get_field($post_id, $schedule_prefix.'schedule_timezone'));

            $byDay = $this->get_field($post_id, $schedule_prefix.'by_day', []);
            if (!empty($byDay) && is_array($byDay)) {
                $schedule['byDay'] = $byDay;
            }

            return $schedule;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return null;
    }

    protected function build_location(int $post_id): array
    {
        $locations = [];

        // Physical location
        $placeIds = $this->get_field($post_id, 'location');
        if ($placeIds) {
            if (is_array($placeIds)) {
                foreach ($placeIds as $placeId) {
                    $place = get_post($placeId);
                    if ($place) {
                        $placeAdapter = new PlaceAdapter($place);
                        $locations[] = $placeAdapter->transform();
                    }
                }
            } else {
                $place = get_post($placeIds);
                if ($place) {
                    $placeAdapter = new PlaceAdapter($place);
                    $locations[] = $placeAdapter->transform();
                }
            }
        }

        return $locations;
    }

    protected function build_organizer(int $post_id): array
    {
        $organizers = [];
        $organizerIds = $this->get_field($post_id, 'organizer', []);

        if (is_array($organizerIds)) {
            foreach ($organizerIds as $organizerId) {
                $org = get_post($organizerId);
                if ($org) {
                    $orgAdapter = new OrganizationAdapter($org);
                    $organizers[] = $orgAdapter->transform();
                }
            }
        }

        return $organizers;
    }

    protected function build_work_featured(int $post_id): array
    {
        $works = [];
        $workIds = $this->get_field($post_id, 'work_featured', []);

        if (is_array($workIds)) {
            foreach ($workIds as $workId) {
                $work = get_post($workId);
                if ($work) {
                    $workAdapter = new CreativeWorkAdapter($work);
                    $works[] = $workAdapter->transform();
                }
            }
        }

        return $works;
    }

    protected function interval_to_ISO8601(DateInterval $date_interval): string {
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

