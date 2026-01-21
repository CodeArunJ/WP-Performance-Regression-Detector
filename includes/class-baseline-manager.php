<?php
/**
 * Baseline Manager Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Baseline_Manager
{

    /**
     * Instance.
     *
     * @var WPRD_Baseline_Manager
     */
    private static $instance;

    /**
     * Get instance.
     *
     * @return WPRD_Baseline_Manager
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
        add_action('wprd_metrics_collected', array($this, 'process_metrics'));
    }

    /**
     * Process collected metrics.
     *
     * @param array $metrics The metrics from the collector.
     */
    public function process_metrics($metrics)
    {
        $baseline = WPRD_Storage::get_baseline();

        $count = isset($baseline['sample_size']) ? $baseline['sample_size'] : 0;

        // We use a rolling average approximation.
        // If count < 20, we assume accurate average so far.
        // If count >= 20, we use a weight corresponding to N=20 to keep it "rolling".

        $n = ($count < 20) ? $count + 1 : 20;

        $baseline['avg_load_time'] = $this->update_average($baseline['avg_load_time'], $metrics['load_time'], $n);
        $baseline['avg_query_count'] = $this->update_average($baseline['avg_query_count'], $metrics['query_count'], $n);
        $baseline['avg_memory_usage'] = $this->update_average($baseline['avg_memory_usage'], $metrics['memory_usage'], $n);

        $baseline['sample_size'] = min($count + 1, 20);
        $baseline['last_updated'] = time();

        WPRD_Storage::update_baseline($baseline);

        // Hook for regression check
        do_action('wprd_baseline_updated', $metrics, $baseline);
    }

    /**
     * Update average value.
     * New Avg = Old Avg + (New Val - Old Avg) / N
     *
     * @param float $old_avg Old average.
     * @param float $new_val New value.
     * @param int   $n       Sample size (N).
     * @return float Updated average.
     */
    private function update_average($old_avg, $new_val, $n)
    {
        if ($n <= 0) {
            return $new_val;
        }
        return $old_avg + (($new_val - $old_avg) / $n);
    }
}
