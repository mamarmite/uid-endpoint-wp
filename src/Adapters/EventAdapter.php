<?php

namespace Mamarmite\UIDEndpoint\Adapters;

use DateInterval;
use Mamarmite\UIDEndpoint\Blueprints\ArtistBlueprint;
use Mamarmite\UIDEndpoint\Blueprints\Blueprint;
use Mamarmite\UIDEndpoint\Blueprints\EventBlueprint;
use const Mamarmite\UIDEndpoint\CLIENT_CONTEXT_DEFAULT;

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

    function __construct(\WP_Post $post, Blueprint $blueprint = null)
    {
        parent::__construct($post, $blueprint ?? new EventBlueprint());
    }

    public function transform(bool $isSchemaRoot = false): array
    {
        $context = parent::transform($isSchemaRoot);
        $schema = array_merge($context, $this->build_base_schema($this->post, $isSchemaRoot));

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
            $this->add_to_schema($schema, 'startDate', $start_date_utc->format($date_format));
        }
        if (!empty($end_date_str)) {
            $end_date = new \DateTimeImmutable($end_date_str, $timezone);
            $end_date_utc = $end_date->setTimezone(new \DateTimeZone('UTC'));
            $this->add_to_schema($schema, 'endDate', $end_date_utc->format($date_format));
        }

        $this->add_to_schema($schema, 'alternateName', $this->get_field($this->post->ID, 'alternate_name'));
        $this->add_to_schema($schema, 'description', \get_the_excerpt($this->post->ID));
        $this->add_to_schema($schema, 'url', get_permalink($this->post->ID));
        //$this->add_to_schema($schema, 'image', $this->get_field($this->post->ID, 'image'));
        $this->add_to_schema($schema, 'additionalType', $this->get_field($this->post->ID, 'additional_type'), "http://kg.artsdata.ca/resource/");
        $this->add_to_schema($schema, 'keywords', $this->get_field($this->post->ID, 'keywords'));
        $this->add_to_schema($schema, 'eventStatus', $this->get_field($this->post->ID, 'event_status', 'EventScheduled'), "https://schema.org/");
        $this->add_to_schema($schema, 'inLanguage', $this->current_language);
        $this->add_to_schema($schema, 'eventAttendanceMode', $this->get_field($this->post->ID, 'event_attendance_mode'), "https://schema.org/");
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

    protected function build_event_schedule(int $post_id, string $context = CLIENT_CONTEXT_DEFAULT): ?array
    {
        $schedule = [];
        //if the key is in the allow list from context.
        if ($this->blueprint->is_allowed('eventSchedule', $context)) {

            $scheduleAdapter = new ScheduleAdapter($this->post);
            $schedule[] = $scheduleAdapter->transform();

        }
        return $schedule;
    }

    protected function build_location(int $post_id, string $context = CLIENT_CONTEXT_DEFAULT): array
    {
        $locations = [];

        //if the key is in the allow list from context.
        if ($this->blueprint->is_allowed('location', $context)) {

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
        }
        return $locations;
    }

    protected function build_organizer(int $post_id, string $context = CLIENT_CONTEXT_DEFAULT): array
    {
        $organizers = [];
        //if the key is in the allow list from context.
        if ($this->blueprint->is_allowed('organizer', $context)) {

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
        }
        return $organizers;
    }

    protected function build_artist(int $post_id, $field_name="contributor", string $context = CLIENT_CONTEXT_DEFAULT): array
    {
        $return = [];
        //if the key is in the allow list from context.
        if ($this->blueprint->is_allowed($field_name, $context)) {
            $artists = $this->get_field($post_id, $field_name, []);
            if (is_array($artists)) {
                foreach ($artists as $artistId) {
                    $artist = get_post($artistId);
                    if ($artist) {
                        $sub_entity_fields = $this->blueprint->get_embed_fields($field_name, $context);
                        $artistAdapter = new ArtistAdapter($artist, new ArtistBlueprint($sub_entity_fields));
                        $return[] = $artistAdapter->transform();
                    }
                }
            }
        }
        return $return;
    }

    protected function build_work_featured(int $post_id, string $context = CLIENT_CONTEXT_DEFAULT): array
    {
        $works = [];

        $allow_list_from_context = $this->blueprint->allow_list($context);
        //if the key is in the allow list from context.
        if ($this->blueprint->is_allowed("work_featured", $context)) {

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
        }
        return $works;
    }
}

