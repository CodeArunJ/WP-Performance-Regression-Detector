<?php
/**
 * Regression Engine Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Regression_Engine
{

    /**
     * Instance.
     *
     * @var WPRD_Regression_Engine
     */
    private static $instance;

    /**
     * Get instance.
     *
     * @return WPRD_Regression_Engine
     */
    public static function get_instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct()
    {
        add_action('wprd_baseline_updated', array($this, 'check_for_regression'), 10, 2);
    }

    /**
     * Check data for regression.
     *
     * @param array $current_metrics  Current request metrics.
     * @param array $baseline_metrics Baseline metrics.
     */
    public function check_for_regression($current_metrics, $baseline_metrics)
    {
        // Only check if we have a decent sample size (e.g. at least 5) to avoid noise at start?
        // Prompt doesn't specify minimum sample, but "Rolling average" implies we assume baseline is valid.
        // However, if baseline is 0, we skip.
        if (empty($baseline_metrics['sample_size']) || $baseline_metrics['sample_size'] < 5) {
            return;
        }

        $regressions = array();

        // Thresholds
        // Load time > +30%
        if ($baseline_metrics['avg_load_time'] > 0 && $current_metrics['load_time'] > ($baseline_metrics['avg_load_time'] * 1.30)) {
            $regressions[] = array(
                'metric' => 'Load Time',
                'baseline' => $baseline_metrics['avg_load_time'],
                'current' => $current_metrics['load_time'],
                'severity' => 'HIGH', // > 30% is HIGH
            );
        }

        // Query count > +25%
        if ($baseline_metrics['avg_query_count'] > 0 && $current_metrics['query_count'] > ($baseline_metrics['avg_query_count'] * 1.25)) {
            $regressions[] = array(
                'metric' => 'DB Queries',
                'baseline' => $baseline_metrics['avg_query_count'],
                'current' => $current_metrics['query_count'],
                'severity' => 'MEDIUM',
            );
        }

        // Memory usage > +20%
        if ($baseline_metrics['avg_memory_usage'] > 0 && $current_metrics['memory_usage'] > ($baseline_metrics['avg_memory_usage'] * 1.20)) {
            $regressions[] = array(
                'metric' => 'Memory Usage',
                'baseline' => $baseline_metrics['avg_memory_usage'],
                'current' => $current_metrics['memory_usage'],
                'severity' => 'LOW',
            );
        }

        foreach ($regressions as $regression) {
            $this->log_regression($regression, $current_metrics['url']);
        }
    }

    /**
     * Log the regression.
     *
     * @param array  $regression_data Regression details.
     * @param string $url             Page URL.
     */
    private function log_regression($regression_data, $url)
    {
        // Find most recent event for correlation
        $recent_events = WPRD_Storage::get_recent_events();
        $trigger_event = 'Unknown';

        if (!empty($recent_events)) {
            // Get the most recent event
            $latest = $recent_events[0];
            // Format: "Event (Entity) - Time ago"
            $trigger_event = sprintf('%s (%s)', $latest['event_type'], $latest['entity']);
        }

        $event = array(
            'page_url' => $url,
            'metric' => $regression_data['metric'],
            'baseline_value' => $regression_data['baseline'],
            'current_value' => $regression_data['current'],
            'severity' => $regression_data['severity'],
            'trigger_event' => $trigger_event,
            'timestamp' => time(),
        );

        WPRD_Storage::add_regression_event($event);
    }
}
