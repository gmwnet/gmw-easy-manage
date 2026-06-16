<?php
/**
 * Plugin Name: GMW Easy Manage
 * Plugin URI: https://gmwsys.com
 * Description: Structured content management for bars and restaurants. Stores hours, specials, menus, events, gallery, contact info, and social links.
 * Version: 1.6.3
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: GMW Systems
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: gmw-easy-manage
 */

defined('ABSPATH') or die;

define('GMW_EM_VERSION', '1.6.3');
define('GMW_EM_PATH', plugin_dir_path(__FILE__));
define('GMW_EM_URL', plugin_dir_url(__FILE__));
define('GMW_EM_UPDATE_URL', 'https://apps.gmwsys.com/gmw-easy-manage-update/update.json');
define('GMW_EM_ED25519_PUBLIC_KEY', '1908b0fec1cbf2f692a24594df6f083e8e1726673c079e360ad3e6bd3e01175f');

require_once GMW_EM_PATH . 'includes/data.php';
require_once GMW_EM_PATH . 'includes/shortcodes.php';

if (defined('WP_CLI') && WP_CLI) {
    require_once GMW_EM_PATH . 'includes/cli.php';
}

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style('gmw-easy-manage', GMW_EM_URL . 'assets/gmw-frontend.css', [], GMW_EM_VERSION);
    wp_enqueue_style('gmw-easy-manage-themes', GMW_EM_URL . 'assets/gmw-themes.css', ['gmw-easy-manage'], GMW_EM_VERSION);
});

add_action('admin_menu', function () {
    add_submenu_page('options-general.php', 'GMW Easy Manage Docs', 'EM Docs', 'manage_options', 'gmw-em-docs', function () {
        ?>
        <div class="wrap">
        <h1>GMW Easy Manage — Shortcode Reference</h1>
        <p>Use these shortcodes in any page, post, or widget to display your business content.</p>
        <table class="wp-list-table widefat fixed striped" style="margin-top:1rem;">
        <thead><tr><th style="width:25%;">Shortcode</th><th style="width:30%;">Displays</th><th style="width:15%;">Default Template</th><th>Notes</th></tr></thead>
        <tbody>
        <tr><td><code>[gmw_hours]</code></td><td>Business hours</td><td>table</td><td></td></tr>
        <tr><td><code>[gmw_specials]</code></td><td>Specials &amp; promotions</td><td>cards</td><td>Supports <code>url</code> for "More info" link</td></tr>
        <tr><td><code>[gmw_happy_hours]</code></td><td>Happy hour schedule</td><td>table</td><td></td></tr>
        <tr><td><code>[gmw_menu]</code></td><td>Menu PDF link(s)</td><td>link</td><td>Use <code>[gmw_menu index="1"]</code> through <code>index="5"</code> for individual menus</td></tr>
        <tr><td><code>[gmw_events]</code></td><td>Events</td><td>list</td><td></td></tr>
        <tr><td><code>[gmw_gallery]</code></td><td>Image gallery</td><td>grid</td><td></td></tr>
        <tr><td><code>[gmw_contact]</code></td><td>Contact info</td><td>card</td><td>Call, Email, and Map buttons</td></tr>
        <tr><td><code>[gmw_social]</code></td><td>Social media links</td><td>row</td><td>SVG icons for Facebook, Instagram, X, TikTok, YouTube, Yelp, LinkedIn, Pinterest, Snapchat</td></tr>
        <tr><td><code>[gmw_alert]</code></td><td>Alert banner</td><td>banner</td><td>Hidden when text is empty. Red with &#9888; icon.</td></tr>
        <tr><td><code>[gmw_promotion]</code></td><td>Promotion banner</td><td>banner</td><td>Hidden when text is empty. Yellow with ! icon.</td></tr>
        </tbody>
        </table>

        <h2 style="margin-top:2rem;">Themes</h2>
        <p>Every shortcode accepts a <code>theme</code> attribute:</p>
        <table class="wp-list-table widefat fixed striped">
        <thead><tr><th>Theme</th><th>Attribute</th><th>Description</th></tr></thead>
        <tbody>
        <tr><td>Light</td><td><code>theme="light"</code></td><td>Default. Dark text, white backgrounds.</td></tr>
        <tr><td>Light Transparent</td><td><code>theme="light-transparent"</code></td><td>Dark text, no backgrounds — for light background images.</td></tr>
        <tr><td>Dark</td><td><code>theme="dark"</code></td><td>Light text, dark backgrounds.</td></tr>
        <tr><td>Dark Transparent</td><td><code>theme="dark-transparent"</code></td><td>Light text, no backgrounds — for dark background images.</td></tr>
        </tbody>
        </table>

        <h2 style="margin-top:2rem;">Custom Templates</h2>
        <p>Use <code>[gmw_&lt;section&gt; template="alt"]</code> to switch to an alternative built-in template. Use <code>gmw_get_data("key")</code> in PHP templates for fully custom markup.</p>

        <h2 style="margin-top:2rem;">Technical Info</h2>
        <p><strong>Data storage:</strong> All content in <code>wp_options</code> as serialized arrays. No custom tables, no custom post types. The site is fully portable — move files + database and everything works.</p>
        <p><strong>Version:</strong> <?= GMW_EM_VERSION ?></p>
        <p><strong>License:</strong> MIT — Open source. Free to use, modify, and distribute.</p>
        </div>
        <?php
    });
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
    $sig = @sodium_hex2bin($data->signature);
    if ($sig === false || strlen($sig) !== SODIUM_CRYPTO_SIGN_BYTES) return $transient;
    if (!sodium_crypto_sign_verify_detached($sig, $payload, sodium_hex2bin(GMW_EM_ED25519_PUBLIC_KEY))) return $transient;
    if (version_compare(GMW_EM_VERSION, $data->version, '<')) {
        $transient->response[plugin_basename(__FILE__)] = (object)[
            'slug'        => dirname(plugin_basename(__FILE__)),
            'new_version' => $data->version,
            'package'     => $data->download_url,
            'tested'      => $data->tested ?? '6.7',
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
    return admin_url('options-general.php?page=gmw-em-docs');
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

function gmw_em_register() {
    $url = home_url();
    $storedToken = get_option('gmw_app_token', '');
    if ($storedToken) {
        $signature = hash_hmac('sha256', $url, $storedToken, false);
    } else {
        $regSecret = get_option('gmw_em_activation_secret', '');
        if (!$regSecret) {
            return false;
        }
        $signature = hash_hmac('sha256', $url, $regSecret, false);
    }
    $resp = wp_remote_post('https://apps.gmwsys.com/api/easymanage-register', [
        'headers' => ['Content-Type' => 'application/json'],
        'body' => json_encode([
            'url' => $url,
            'signature' => $signature,
            'activation_secret' => get_option('gmw_em_activation_secret', ''),
            'version' => GMW_EM_VERSION,
        ]),
        'timeout' => 5,
    ]);
    if (is_wp_error($resp) || wp_remote_retrieve_response_code($resp) !== 200) {
        return false;
    }
    $body = json_decode(wp_remote_retrieve_body($resp), true);
    if (!empty($body['company_code'])) {
        update_option('gmw_company_code', $body['company_code']);
    }
    if (!empty($body['app_token'])) {
        update_option('gmw_app_token', $body['app_token']);
    }
    return !empty($body['ok']);
}

add_action('gmw_em_do_register', function () {
    $attempts = (int)get_option('gmw_em_register_attempts', 0) + 1;
    update_option('gmw_em_register_attempts', $attempts);

    if (gmw_em_register()) {
        delete_option('gmw_em_register_attempts');
        delete_option('gmw_em_register_scheduled');
        return;
    }

    if ($attempts >= 3) {
        update_option('gmw_em_register_gaveup', 1);
        delete_option('gmw_em_register_scheduled');
        return;
    }

    $schedules = [60, 300, 1800];
    $delay = $schedules[min($attempts - 1, 2)];
    wp_schedule_single_event(time() + $delay, 'gmw_em_do_register');
    update_option('gmw_em_register_scheduled', time() + $delay);
});

add_action('admin_notices', function () {
    if (get_option('gmw_em_register_scheduled') && current_user_can('activate_plugins')) {
        $remaining = max(0, (int)get_option('gmw_em_register_scheduled') - time());
        $msg = $remaining > 0
            ? sprintf('GMW Easy Manage is registering with the portal. Auto-retry in %ds.', $remaining)
            : 'GMW Easy Manage could not register with the portal.';
        echo '<div class="notice notice-warning is-dismissible"><p>' . esc_html($msg) . '</p></div>';
    }
});

add_action('wp', function () {
    if (!get_option('gmw_company_code') && !get_option('gmw_em_register_gaveup') && !wp_next_scheduled('gmw_em_do_register') && !get_option('gmw_em_register_scheduled')) {
        wp_schedule_single_event(time() + 60, 'gmw_em_do_register');
        update_option('gmw_em_register_scheduled', time() + 60);
    }
});

register_activation_hook(__FILE__, function () {
    if (!get_option('gmw_em_activation_secret')) {
        update_option('gmw_em_activation_secret', bin2hex(random_bytes(32)));
    }

    $slug = gmw_docs_page_slug();
    $existing = get_page_by_path($slug, OBJECT, 'page');

    if ($existing) {
        if ($existing->post_status !== 'private') {
            wp_update_post(['ID' => $existing->ID, 'post_status' => 'private']);
        }
        update_option('gmw_docs_page_id', $existing->ID);
    } else {
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
    }

    gmw_em_register();
});

register_deactivation_hook(__FILE__, function () {
    delete_option('gmw_em_register_attempts');
    delete_option('gmw_em_register_scheduled');
    delete_option('gmw_em_register_gaveup');
    wp_clear_scheduled_hook('gmw_em_do_register');
});