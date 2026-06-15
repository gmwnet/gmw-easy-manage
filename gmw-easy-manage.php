<?php
/**
 * Plugin Name: GMW Easy Manage
 * Plugin URI: https://apps.gmwsys.com/
 * Description: Structured content management for bars and restaurants. Stores hours, specials, menus, events, gallery, contact info, and social links.
 * Version: 1.6.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: GMW Systems
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: gmw-easy-manage
 */

defined('ABSPATH') or die;

define('GMW_EM_VERSION', '1.6.0');
define('GMW_EM_PATH', plugin_dir_path(__FILE__));
define('GMW_EM_URL', plugin_dir_url(__FILE__));
define('GMW_EM_UPDATE_URL', 'https://apps.gmwsys.com/gmw-easy-manage-update/update.json');
define('GMW_EM_UPDATE_SECRET', 'a9f3c8e1b2d7f4a6c0e9d8b7a5f3e1c2d4b6a8f0e7c9d1b3a5f7e0c2d4b6a8');

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

add_filter('pre_set_site_transient_update_plugins', function ($transient) {
    if (empty($transient->checked)) return $transient;
    $remote = wp_remote_get(GMW_EM_UPDATE_URL, ['timeout' => 5]);
    if (is_wp_error($remote) || wp_remote_retrieve_response_code($remote) !== 200) return $transient;
    $data = json_decode(wp_remote_retrieve_body($remote));
    if (!$data || !isset($data->version) || !isset($data->signature)) return $transient;
    $payload = json_encode([
        'version' => $data->version,
        'download_url' => $data->download_url,
    ], JSON_UNESCAPED_SLASHES);
    if (!hash_equals(hash_hmac('sha256', $payload, GMW_EM_UPDATE_SECRET), $data->signature)) return $transient;
    if (version_compare(GMW_EM_VERSION, $data->version, '<')) {
        $transient->response[plugin_basename(__FILE__)] = (object)[
            'slug'        => dirname(plugin_basename(__FILE__)),
            'new_version' => $data->version,
            'package'     => $data->download_url,
            'tested'      => $data->tested ?? '7.0',
            'requires'    => $data->requires ?? '6.0',
            'url'         => $data->homepage ?? 'https://github.com/gmwnet/gmw-easy-manage',
        ];
    }
    return $transient;
});

add_action('admin_post_gmw_em_check_updates', function () {
    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'gmw_em_check_updates') || !current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }
    delete_site_transient('update_plugins');
    wp_redirect(admin_url('plugins.php?update-check=1'));
    exit;
});

add_action('init', function () {
    if (get_option('gmw_purge') === '1') {
        delete_option('gmw_purge');
        $siteHost = wp_parse_url(home_url('/'), PHP_URL_HOST) ?: 'localhost';
        $varnishHost = defined('GMW_VARNISH_HOST') ? GMW_VARNISH_HOST : '127.0.0.1';
        $varnishPort = defined('GMW_VARNISH_PORT') ? GMW_VARNISH_PORT : 6081;
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "http://{$varnishHost}:{$varnishPort}/",
            CURLOPT_CUSTOMREQUEST => 'PURGE',
            CURLOPT_HTTPHEADER => [
                'Host: ' . $siteHost,
                'X-Purge-Method: regex',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
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

add_filter('plugin_row_meta', function ($links, $file) {
    if ($file === plugin_basename(__FILE__)) {
        $check_url = wp_nonce_url(admin_url('admin-post.php?action=gmw_em_check_updates'), 'gmw_em_check_updates');
        $links[] = '<a href="' . esc_url($check_url) . '">Check for Updates</a>';
    }
    return $links;
}, 10, 2);

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
