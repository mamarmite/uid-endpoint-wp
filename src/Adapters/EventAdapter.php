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

    function __construct(\WP_Post $post, $schema_allow_list=[])
    {
        $this->default_allow_list = [
            "startDate" => true,
            "endDate" => true,
            "alternateName" => true,
            "description" => true,
            "url" => true,
            "keywords" => true,
            "eventStatus" => true,
            "inLanguage" => true,
            "eventAttendanceMode" => true,
            "isAccessibleForFree" => true,
            "mainEntityOfPage" => true,
            "eventSchedule" => [
                "all"
            ],
            "location" => [
                "all"
            ],
            "organizer" => [
                "all"
            ],
            "workFeatured" => [
                "all"
            ],
            "image" => [
                "all"
            ],
            "performer" => [
                "alternateName" => true,
                "sameAs" => true,
            ],
            "contributor" => [
                "alternateName" => true,
                "sameAs" => true,
            ]
        ];
        parent::__construct($post, $schema_allow_list);
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
            $this->add_to_schema($schema, 'startDate', $start_date_utc->format("c"));
        }
        if (!empty($end_date_str)) {
            $end_date = new \DateTimeImmutable($end_date_str, $timezone);
            $end_date_utc = $end_date->setTimezone(new \DateTimeZone('UTC'));
            $this->add_to_schema($schema, 'endDate', $end_date_utc->format("c"));
        }

        $this->add_to_schema($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_to_schema($schema, 'description', $this->get_field($this->post->ID, 'description', \get_the_excerpt($this->post->ID)));
        $this->add_to_schema($schema, 'url', get_permalink($this->post->ID));
        //$this->add_to_schema($schema, 'image', $this->get_field($this->post->ID, 'image'));
        $this->add_to_schema($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'));
        $this->add_to_schema($schema, 'keywords', $this->get_field($this->post->ID, 'keywords'));
        $this->add_to_schema($schema, 'eventStatus', $this->get_field($this->post->ID, 'event_status', 'https://schema.org/EventScheduled'));
        $this->add_to_schema($schema, 'inLanguage', $this->current_language);
        $this->add_to_schema($schema, 'eventAttendanceMode', $this->get_field($this->post->ID, 'event_attendance_mode'));
        $this->add_to_schema($schema, 'isAccessibleForFree', $this->get_field($this->post->ID, 'is_accessible_for_free'));

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

        // Performer
        $performers = $this->build_artist($this->post->ID, "performer");
        if (!empty($performers)) {
            $schema['performer'] = $performers;
        }

        // contributor
        $contributors = $this->build_artist($this->post->ID, "contributor");
        if (!empty($contributors)) {
            $schema['contributor'] = $contributors;
        }

        // Work Featured
        $workFeatured = $this->build_work_featured($this->post->ID);
        if (!empty($workFeatured)) {
            $schema['workFeatured'] = $workFeatured;
        }

        //MediaObject
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
            $end_date_obj = new \DateTime($end_date);
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

            $this->add_to_schema($schedule, 'startTime', $start_time);
            $this->add_to_schema($schedule, 'endTime', $end_time);
            $this->add_to_schema($schedule, 'repeatFrequency', $this->interval_to_ISO8601($date_delta));
            $this->add_to_schema($schedule, 'startTime', $this->get_field($post_id, $schedule_prefix.'start_time'));
            $this->add_to_schema($schedule, 'endTime', $this->get_field($post_id, $schedule_prefix.'end_time'));
            $this->add_to_schema($schedule, 'scheduleTimezone', $this->get_field($post_id, $schedule_prefix.'schedule_timezone'));

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

    protected function build_artist(int $post_id, $field_name="performer"): array
    {
        $return = [];
        $artists = $this->get_field($post_id, $field_name, []);

        if (is_array($artists)) {
            foreach ($artists as $artist) {
                if ($artist) {
                    $override_allow_list = is_array($this->allow_list[$field_name]) ? $this->allow_list[$field_name] : [];
                    $artistAdapter = new ArtistAdapter($artist, $override_allow_list);
                    $return[] = $artistAdapter->transform();
                }
            }
        }
        return $return;
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

