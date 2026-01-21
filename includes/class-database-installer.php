<?php
/**
 * Database Installer Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Database_Installer
{

    /**
     * Install database tables.
     */
    public static function install()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Table for baseline metrics
        $baseline_table = $wpdb->prefix . 'wprd_baseline_metrics';
        $baseline_sql = "CREATE TABLE IF NOT EXISTS $baseline_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            avg_load_time float NOT NULL DEFAULT 0,
            avg_query_count int(11) NOT NULL DEFAULT 0,
            avg_memory_usage float NOT NULL DEFAULT 0,
            sample_size int(11) NOT NULL DEFAULT 0,
            last_updated bigint(20) NOT NULL DEFAULT 0,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";

        // Table for recent events
        $recent_table = $wpdb->prefix . 'wprd_recent_events';
        $recent_sql = "CREATE TABLE IF NOT EXISTS $recent_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            event_type varchar(50) NOT NULL,
            description text NOT NULL,
            metrics longtext NOT NULL,
            timestamp bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY event_type (event_type),
            KEY timestamp (timestamp)
        ) $charset_collate;";

        // Table for regression events
        $regression_table = $wpdb->prefix . 'wprd_regression_events';
        $regression_sql = "CREATE TABLE IF NOT EXISTS $regression_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            severity varchar(20) NOT NULL,
            metric_type varchar(50) NOT NULL,
            expected_value float NOT NULL,
            actual_value float NOT NULL,
            deviation_percent float NOT NULL,
            related_events longtext NOT NULL,
            timestamp bigint(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY severity (severity),
            KEY metric_type (metric_type),
            KEY timestamp (timestamp)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($baseline_sql);
        dbDelta($recent_sql);
        dbDelta($regression_sql);

        // Store version
        update_option('wprd_db_version', '1.0.0');
    }

    /**
     * Uninstall database tables.
     */
    public static function uninstall()
    {
        global $wpdb;

        $baseline_table = $wpdb->prefix . 'wprd_baseline_metrics';
        $recent_table = $wpdb->prefix . 'wprd_recent_events';
        $regression_table = $wpdb->prefix . 'wprd_regression_events';

        $wpdb->query("DROP TABLE IF EXISTS $regression_table");
        $wpdb->query("DROP TABLE IF EXISTS $recent_table");
        $wpdb->query("DROP TABLE IF EXISTS $baseline_table");

        delete_option('wprd_db_version');
    }
}
