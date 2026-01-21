<?php
/**
 * Plugin Name: WP Performance Regression Detector
 * Description: Detects real-time performance regressions in WordPress and correlates them with recent system actions.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: wprd
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define('WPRD_VERSION', '1.0.0');
define('WPRD_PATH', plugin_dir_path(__FILE__));
define('WPRD_URL', plugin_dir_url(__FILE__));

/**
 * Main Plugin Class
 */
class WPRD_Plugin
{

    /**
     * Instance of this class.
     *
     * @var WPRD_Plugin
     */
    private static $instance;

    /**
     * Get the singleton instance.
     *
     * @return WPRD_Plugin
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
        $this->includes();
        $this->hooks();
    }

    /**
     * Include necessary files.
     */
    private function includes()
    {
        require_once WPRD_PATH . 'includes/class-storage.php';
        require_once WPRD_PATH . 'includes/class-metrics-collector.php';
        require_once WPRD_PATH . 'includes/class-baseline-manager.php';
        require_once WPRD_PATH . 'includes/class-regression-engine.php';
        require_once WPRD_PATH . 'includes/class-event-tracker.php';

        if (is_admin()) {
            require_once WPRD_PATH . 'admin/class-admin-dashboard.php';
        }
    }

    /**
     * Initialize hooks.
     */
    private function hooks()
    {
        WPRD_Metrics_Collector::get_instance();
        WPRD_Baseline_Manager::get_instance();
        WPRD_Regression_Engine::get_instance();
        WPRD_Event_Tracker::get_instance();

        if (is_admin()) {
            WPRD_Admin_Dashboard::get_instance();
        }
    }
}

/**
 * Initialize the plugin.
 */
function wprd_init()
{
    WPRD_Plugin::get_instance();
}
add_action('plugins_loaded', 'wprd_init');
