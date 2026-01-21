<?php
/**
 * Metrics Collector Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Metrics_Collector
{

    /**
     * Start time of the request.
     *
     * @var float
     */
    private $start_time;

    /**
     * Instance of this class.
     *
     * @var WPRD_Metrics_Collector
     */
    private static $instance;

    /**
     * Get the singleton instance.
     *
     * @return WPRD_Metrics_Collector
     */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private constructor.
     */
    private function __construct()
    {
        add_action('init', array($this, 'start_timer'), 1); // High priority to start early
        add_action('shutdown', array($this, 'capture_metrics'), 20); // Late priority
    }

    /**
     * Start the timer.
     */
    public function start_timer()
    {
        $this->start_time = microtime(true);
    }

    /**
     * Capture metrics at shutdown.
     */
    public function capture_metrics()
    {
        // Only collect if we have a start time
        if (empty($this->start_time)) {
            return;
        }

        // Calculate metrics
        $end_time = microtime(true);
        $load_time = $end_time - $this->start_time;
        $memory_usage = memory_get_peak_usage(); // In bytes

        global $wpdb;
        $query_count = isset($wpdb->num_queries) ? $wpdb->num_queries : 0;

        $metrics = array(
            'load_time' => $load_time,
            'memory_usage' => $memory_usage,
            'query_count' => $query_count,
            'timestamp' => time(), // Current time
            'url' => $_SERVER['REQUEST_URI'] ?? '',
        );

        // Pass to Baseline Manager (to be implemented)
        // For now, we'll just log if debug is enabled or prepare for the hook
        do_action('wprd_metrics_collected', $metrics);
    }
}
