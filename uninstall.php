<?php
/**
 * Fired when the plugin is uninstalled.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options
delete_option( 'wprd_baseline_metrics' );
delete_option( 'wprd_recent_events' );
delete_option( 'wprd_regression_events' );
