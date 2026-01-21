<?php
/**
 * Storage Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Storage
{

    /**
     * Keys for options.
     */
    const KEY_BASELINE = 'wprd_baseline_metrics';
    const KEY_RECENT = 'wprd_recent_events';
    const KEY_REGRESSION = 'wprd_regression_events';

    /**
     * Get baseline metrics.
     *
     * @return array
     */
    public static function get_baseline()
    {
        return get_option(self::KEY_BASELINE, array(
            'avg_load_time' => 0,
            'avg_query_count' => 0,
            'avg_memory_usage' => 0,
            'sample_size' => 0,
            'last_updated' => 0,
        ));
    }

    /**
     * Update baseline metrics.
     *
     * @param array $data Data to save.
     */
    public static function update_baseline($data)
    {
        update_option(self::KEY_BASELINE, $data);
    }

    /**
     * Get recent events.
     *
     * @return array
     */
    public static function get_recent_events()
    {
        return get_option(self::KEY_RECENT, array());
    }

    /**
     * Add a recent event.
     * Max 10 events.
     *
     * @param array $event Event data.
     */
    public static function add_recent_event($event)
    {
        $events = self::get_recent_events();

        // Prepend new event
        array_unshift($events, $event);

        // Slice to max 10
        if (count($events) > 10) {
            $events = array_slice($events, 0, 10);
        }

        update_option(self::KEY_RECENT, $events);
    }

    /**
     * Get regression events.
     *
     * @return array
     */
    public static function get_regression_events()
    {
        return get_option(self::KEY_REGRESSION, array());
    }

    /**
     * Add a regression event.
     *
     * @param array $event Regression data.
     */
    public static function add_regression_event($event)
    {
        $events = self::get_regression_events();

        // Prepend
        array_unshift($events, $event);

        // Limit to last 50 for safety (prompt implies "Max limited")
        if (count($events) > 50) {
            $events = array_slice($events, 0, 50);
        }

        update_option(self::KEY_REGRESSION, $events);
    }
}
