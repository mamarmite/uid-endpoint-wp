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

    public function transform(\WP_Post $post): array
    {
        $schema = $this->buildBaseSchema($post);

        $this->addIfNotEmpty($schema, 'alternateName', $this->getField($post->ID, 'alternate_name'));
        $this->addIfNotEmpty($schema, 'description', $this->getField($post->ID, 'description', $post->post_content));
        $this->addIfNotEmpty($schema, 'url', $this->getField($post->ID, 'url', get_permalink($post->ID)));
        $this->addIfNotEmpty($schema, 'image', $this->getField($post->ID, 'image'));
        $this->addIfNotEmpty($schema, 'additionalType', $this->getField($post->ID, 'additional_type'));
        $this->addIfNotEmpty($schema, 'keywords', $this->getField($post->ID, 'keywords'));
        $this->addIfNotEmpty($schema, 'eventStatus', $this->getField($post->ID, 'event_status', 'https://schema.org/EventScheduled'));
        $this->addIfNotEmpty($schema, 'eventAttendanceMode', $this->getField($post->ID, 'event_attendance_mode'));
        $this->addIfNotEmpty($schema, 'isAccessibleForFree', $this->getField($post->ID, 'is_accessible_for_free'));

        // Event Schedule
        $schedule = $this->buildEventSchedule($post->ID);
        if ($schedule) {
            $schema['eventSchedule'] = $schedule;
        }

        // Location
        $location = $this->buildLocation($post->ID);
        if (!empty($location)) {
            $schema['location'] = $location;
        }

        // Organizer
        $organizer = $this->buildOrganizer($post->ID);
        if (!empty($organizer)) {
            $schema['organizer'] = $organizer;
        }

        // Work Featured
        $workFeatured = $this->buildWorkFeatured($post->ID);
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
