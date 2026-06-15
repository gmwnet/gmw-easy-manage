<?php
/**
 * Plugin Name: GMW Easy Manage
 * Plugin URI: https://apps.gmwsys.com/
 * Description: Structured content management for bars and restaurants. Stores hours, specials, menus, events, gallery, contact info, and social links.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: GMW Systems
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: gmw-easy-manage
 */

defined('ABSPATH') or die;

define('GMW_EM_VERSION', '1.0.0');
define('GMW_EM_PATH', plugin_dir_path(__FILE__));
define('GMW_EM_URL', plugin_dir_url(__FILE__));

require_once GMW_EM_PATH . 'includes/data.php';
require_once GMW_EM_PATH . 'includes/shortcodes.php';

if (defined('WP_CLI') && WP_CLI) {
    require_once GMW_EM_PATH . 'includes/cli.php';
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('gmw-easy-manage', GMW_EM_URL . 'assets/gmw-frontend.css', [], GMW_EM_VERSION);
    wp_enqueue_style('gmw-easy-manage-themes', GMW_EM_URL . 'assets/gmw-themes.css', ['gmw-easy-manage'], GMW_EM_VERSION);
});

add_filter('wp_robots', function ($robots) {
    $docs_id = get_option('gmw_docs_page_id');
    if ($docs_id && is_page($docs_id)) {
        $robots['noindex'] = true;
        $robots['nofollow'] = true;
    }
    return $robots;
});

function gmw_docs_page_slug()
{
    return 'gmw-easy-manage-docs';
}

function gmw_docs_page_url()
{
    return get_permalink(get_option('gmw_docs_page_id'));
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $url = gmw_docs_page_url();
    if ($url) {
        $links[] = '<a href="' . esc_url($url) . '" target="_blank">Documentation</a>';
    }
    return $links;
});

register_activation_hook(__FILE__, function () {
    $slug = gmw_docs_page_slug();
    $existing = get_page_by_path($slug, OBJECT, 'page');

    if ($existing) {
        if ($existing->post_status !== 'private') {
            wp_update_post(['ID' => $existing->ID, 'post_status' => 'private']);
        }
        update_option('gmw_docs_page_id', $existing->ID);
        return;
    }

    $id = wp_insert_post([
        'post_title' => 'GMW Easy Manage Docs',
        'post_name' => $slug,
        'post_content' => '[gmw_stylebook]',
        'post_status' => 'private',
        'post_type' => 'page',
    ]);

    if ($id && !is_wp_error($id)) {
        update_option('gmw_docs_page_id', $id);
    }
});
