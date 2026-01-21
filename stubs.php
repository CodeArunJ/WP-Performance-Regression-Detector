<?php
/**
 * Development Stubs
 * 
 * This file is for development purposes only to silence IDE errors.
 * It is not loaded by the plugin.
 */

if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1)
    {
    }
}

if (!function_exists('do_action')) {
    function do_action($tag, ...$arg)
    {
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text)
    {
        return $text;
    }
}

if (!function_exists('human_time_diff')) {
    function human_time_diff($from, $to = '')
    {
        return '';
    }
}

if (!function_exists('is_admin')) {
    function is_admin()
    {
        return false;
    }
}

if (!function_exists('plugin_dir_path')) {
    function plugin_dir_path($file)
    {
        return '';
    }
}

if (!function_exists('plugin_dir_url')) {
    function plugin_dir_url($file)
    {
        return '';
    }
}

if (!function_exists('get_option')) {
    function get_option($option, $default = false)
    {
        return $default;
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value, $autoload = null)
    {
        return true;
    }
}

if (!function_exists('delete_option')) {
    function delete_option($option)
    {
        return true;
    }
}

if (!function_exists('add_management_page')) {
    function add_management_page($page_title, $menu_title, $capability, $menu_slug, $function = '')
    {
    }
}

if (!function_exists('wp_enqueue_style')) {
    function wp_enqueue_style($handle, $src = '', $deps = array(), $ver = false, $media = 'all')
    {
    }
}

if (!function_exists('number_format')) {
    // PHP core, usually exists, but just in case of odd env.
    // Actually number_format is standard PHP, likely no need to stub.
}
