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
     * Get baseline metrics.
     *
     * @return array
     */
    public static function get_baseline()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wprd_baseline_metrics';

        $baseline = $wpdb->get_row("SELECT * FROM $table LIMIT 1", ARRAY_A);

        if (!$baseline) {
            // Insert default baseline
            $wpdb->insert($table, array(
                'avg_load_time' => 0,
                'avg_query_count' => 0,
                'avg_memory_usage' => 0,
                'sample_size' => 0,
                'last_updated' => 0,
            ));
            $baseline = array(
                'id' => $wpdb->insert_id,
                'avg_load_time' => 0,
                'avg_query_count' => 0,
                'avg_memory_usage' => 0,
                'sample_size' => 0,
                'last_updated' => 0,
            );
        }

        return $baseline;
    }

    /**
     * Update baseline metrics.
     *
     * @param array $data Data to save.
     */
    public static function update_baseline($data)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wprd_baseline_metrics';

        $baseline = self::get_baseline();

        $data['last_updated'] = time();

        $wpdb->update($table, $data, array('id' => $baseline['id']));
    }

    /**
     * Get recent events.
     *
     * @return array
     */
    public static function get_recent_events()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wprd_recent_events';

        $events = $wpdb->get_results("SELECT * FROM $table ORDER BY timestamp DESC LIMIT 10", ARRAY_A);

        return $events ? $events : array();
    }

    /**
     * Add a recent event.
     * Max 10 events.
     *
     * @param array $event Event data.
     */
    public static function add_recent_event($event)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wprd_recent_events';

        $event['timestamp'] = isset($event['timestamp']) ? $event['timestamp'] : time();
        $event['metrics'] = isset($event['metrics']) ? maybe_serialize($event['metrics']) : '';

        $wpdb->insert($table, array(
            'event_type' => isset($event['event_type']) ? $event['event_type'] : 'unknown',
            'description' => isset($event['description']) ? $event['description'] : '',
            'metrics' => $event['metrics'],
            'timestamp' => $event['timestamp'],
        ));

        // Clean up old events, keep only last 10
        $wpdb->query("DELETE FROM $table WHERE id NOT IN (SELECT id FROM (SELECT id FROM $table ORDER BY timestamp DESC LIMIT 10) AS temp)");
    }

    /**
     * Get regression events.
     *
     * @return array
     */
    public static function get_regression_events()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wprd_regression_events';

        $events = $wpdb->get_results("SELECT * FROM $table ORDER BY timestamp DESC LIMIT 50", ARRAY_A);

        if ($events) {
            foreach ($events as &$event) {
                if (isset($event['related_events'])) {
                    $event['related_events'] = maybe_unserialize($event['related_events']);
                }
            }
        }

        return $events ? $events : array();
    }

    /**
     * Add a regression event.
     *
     * @param array $event Regression data.
     */
    public static function add_regression_event($event)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wprd_regression_events';

        $event['timestamp'] = isset($event['timestamp']) ? $event['timestamp'] : time();
        $related_events = isset($event['related_events']) ? maybe_serialize($event['related_events']) : '';

        $wpdb->insert($table, array(
            'severity' => isset($event['severity']) ? $event['severity'] : 'medium',
            'metric_type' => isset($event['metric_type']) ? $event['metric_type'] : 'unknown',
            'expected_value' => isset($event['expected_value']) ? floatval($event['expected_value']) : 0,
            'actual_value' => isset($event['actual_value']) ? floatval($event['actual_value']) : 0,
            'deviation_percent' => isset($event['deviation_percent']) ? floatval($event['deviation_percent']) : 0,
            'related_events' => $related_events,
            'timestamp' => $event['timestamp'],
        ));

        // Clean up old events, keep only last 50
        $wpdb->query("DELETE FROM $table WHERE id NOT IN (SELECT id FROM (SELECT id FROM $table ORDER BY timestamp DESC LIMIT 50) AS temp)");
    }
}
