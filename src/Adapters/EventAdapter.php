<?php

namespace Mamarmite\UIDEndpoint\Adapters;
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
    protected string $prefix = "e";

    function __construct(string $postType, \WP_Post $post = null)
    {
        parent::__construct($postType, $post);
    }

    public function transform(): array
    {
        $schema = $this->buildBaseSchema($this->post);

        $this->addIfNotEmpty($schema, 'alternateName', $this->getField($this->post->ID, 'alternate_name'));
        $this->addIfNotEmpty($schema, 'description', $this->getField($this->post->ID, 'description', $this->post->post_content));
        $this->addIfNotEmpty($schema, 'url', $this->getField($this->post->ID, 'url', get_permalink($this->post->ID)));
        $this->addIfNotEmpty($schema, 'image', $this->getField($this->post->ID, 'image'));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($this->post->ID, 'additional_type'));
        $this->addIfNotEmpty($schema, 'keywords', $this->getField($this->post->ID, 'keywords'));
        $this->addIfNotEmpty($schema, 'eventStatus', $this->getField($this->post->ID, 'event_status', 'https://schema.org/EventScheduled'));
        $this->addIfNotEmpty($schema, 'eventAttendanceMode', $this->getField($this->post->ID, 'event_attendance_mode'));
        $this->addIfNotEmpty($schema, 'isAccessibleForFree', $this->getField($this->post->ID, 'is_accessible_for_free'));

        // Event Schedule
        $schedule = $this->buildEventSchedule($this->post->ID);
        if ($schedule) {
            $schema['eventSchedule'] = $schedule;
        }

        // Location
        $location = $this->buildLocation($this->post->ID);
        if (!empty($location)) {
            $schema['location'] = $location;
        }

        // Organizer
        $organizer = $this->buildOrganizer($this->post->ID);
        if (!empty($organizer)) {
            $schema['organizer'] = $organizer;
        }

        // Work Featured
        $workFeatured = $this->buildWorkFeatured($this->post->ID);
        if (!empty($workFeatured)) {
            $schema['workFeatured'] = $workFeatured;
        }

        return $schema;
    }

    protected function buildEventSchedule(int $post_id): ?array
    {
        $startDate = $this->getField($post_id, 'start_date');
        if (empty($startDate)) {
            return null;
        }

        $schedule = [
            '@type' => 'Schedule',
            'startDate' => $startDate,
        ];

        $this->addIfNotEmpty($schedule, 'endDate', $this->getField($post_id, 'end_date'));
        $this->addIfNotEmpty($schedule, 'repeatFrequency', $this->getField($post_id, 'repeat_frequency'));
        $this->addIfNotEmpty($schedule, 'startTime', $this->getField($post_id, 'start_time'));
        $this->addIfNotEmpty($schedule, 'endTime', $this->getField($post_id, 'end_time'));
        $this->addIfNotEmpty($schedule, 'scheduleTimezone', $this->getField($post_id, 'schedule_timezone'));

        $byDay = $this->getField($post_id, 'by_day', []);
        if (!empty($byDay) && is_array($byDay)) {
            $schedule['byDay'] = $byDay;
        }

        return $schedule;
    }

    protected function buildLocation(int $post_id): array
    {
        $locations = [];

        // Physical location
        $placeId = $this->getField($post_id, 'location_place');
        if ($placeId) {
            $place = get_post($placeId);
            if ($place) {
                $placeAdapter = new PlaceAdapter();
                $locations[] = $placeAdapter->transform($place);
            }
        }

        // Virtual location
        $virtualUrl = $this->getField($post_id, 'virtual_location_url');
        if ($virtualUrl) {
            $locations[] = [
                '@type' => 'VirtualLocation',
                'url' => $virtualUrl,
            ];
        }

        return $locations;
    }

    protected function buildOrganizer(int $post_id): array
    {
        $organizers = [];
        $organizerIds = $this->getField($post_id, 'organizers', []);

        if (is_array($organizerIds)) {
            foreach ($organizerIds as $organizerId) {
                $org = get_post($organizerId);
                if ($org) {
                    $orgAdapter = new OrganizationAdapter();
                    $organizers[] = $orgAdapter->transform($org);
                }
            }
        }

        return $organizers;
    }

    protected function buildWorkFeatured(int $post_id): array
    {
        $works = [];
        $workIds = $this->getField($post_id, 'work_featured', []);

        if (is_array($workIds)) {
            foreach ($workIds as $workId) {
                $work = get_post($workId);
                if ($work) {
                    $workAdapter = new CreativeWorkAdapter();
                    $works[] = $workAdapter->transform($work);
                }
            }
        }

        return $works;
    }
}
