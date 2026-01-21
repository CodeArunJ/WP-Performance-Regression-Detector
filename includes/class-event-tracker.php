<?php
/**
 * Event Tracker Class
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPRD_Event_Tracker
{

    /**
     * Instance.
     *
     * @var WPRD_Event_Tracker
     */
    private static $instance;

    /**
     * Get instance.
     *
     * @return WPRD_Event_Tracker
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
        add_action('activated_plugin', array($this, 'track_plugin_activation'));
        add_action('upgrader_process_complete', array($this, 'track_updates'), 10, 2);
        add_action('switch_theme', array($this, 'track_theme_switch'));
        add_action('transition_post_status', array($this, 'track_post_update'), 10, 3);
    }

    /**
     * Track plugin activation.
     *
     * @param string $plugin Plugin path.
     */
    public function track_plugin_activation($plugin)
    {
        $this->log_event('Plugin Activation', $plugin);
    }

    /**
     * Track updates (Plugin or Theme).
     *
     * @param WP_Upgrader $upgrader   Upgrader instance.
     * @param array       $hook_extra Extra args.
     */
    public function track_updates($upgrader, $hook_extra)
    {
        if (isset($hook_extra['type']) && 'plugin' === $hook_extra['type']) {
            if (isset($hook_extra['plugins'])) {
                foreach ($hook_extra['plugins'] as $plugin) {
                    $this->log_event('Plugin Update', $plugin);
                }
            }
        } elseif (isset($hook_extra['type']) && 'theme' === $hook_extra['type']) {
            if (isset($hook_extra['themes'])) {
                foreach ($hook_extra['themes'] as $theme) {
                    $this->log_event('Theme Update', $theme);
                }
            }
        }
    }

    /**
     * Track theme switch.
     *
     * @param string $new_name New theme name.
     * @param WP_Theme $new_theme New theme object.
     */
    public function track_theme_switch($new_name, $new_theme = null)
    {
        // WP < 4.5 vs WP > 4.5 arg handling, but $new_name usually works as first arg (name or theme object depending on WP version history, but usually simple)
        // For safety, assume $new_name is a string or fallback to get_stylesheet()
        $name = is_string($new_name) ? $new_name : wp_get_theme()->get('Name');
        $this->log_event('Theme Switch', $name);
    }

    /**
     * Track post publish/update.
     *
     * @param string  $new_status New status.
     * @param string  $old_status Old status.
     * @param WP_Post $post       Post object.
     */
    public function track_post_update($new_status, $old_status, $post)
    {
        if ('publish' === $new_status) {
            if ('publish' !== $old_status) {
                $this->log_event('Post Publish', $post->post_title);
            } else {
                $this->log_event('Post Update', $post->post_title);
            }
        }
    }

    /**
     * Log the event.
     *
     * @param string $type   Event type.
     * @param string $entity Entity name/ID.
     */
    private function log_event($type, $entity)
    {
        $event = array(
            'event_type' => $type,
            'entity' => $entity,
            'timestamp' => time(),
        );
        WPRD_Storage::add_recent_event($event);
    }
}
