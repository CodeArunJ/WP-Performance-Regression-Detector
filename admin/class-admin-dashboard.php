<?php
/**
 * Admin Dashboard Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Admin_Dashboard
{

    /**
     * Instance.
     *
     * @var WPRD_Admin_Dashboard
     */
    private static $instance;

    /**
     * Get instance.
     *
     * @return WPRD_Admin_Dashboard
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
        add_action('admin_menu', array($this, 'add_menu_page'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    /**
     * Add menu page.
     */
    public function add_menu_page()
    {
        add_management_page(
            'Performance Regression Detector',
            'Performance Regression',
            'manage_options',
            'wprd-dashboard',
            array($this, 'render_dashboard')
        );
    }

    /**
     * Enqueue assets.
     *
     * @param string $hook Current admin page hook.
     */
    public function enqueue_assets($hook)
    {
        if ('tools_page_wprd-dashboard' !== $hook) {
            return;
        }

        wp_enqueue_style(
            'wprd-admin-css',
            WPRD_URL . 'assets/css/admin.css',
            array(),
            WPRD_VERSION
        );
    }

    /**
     * Render the dashboard view.
     */
    public function render_dashboard()
    {
        require_once WPRD_PATH . 'admin/views/dashboard.php';
    }
}
