<?php
/**
 * Plugin Name: GMW Easy Manage
 * Plugin URI: https://apps.gmwsys.com/
 * Description: Structured content management for bars and restaurants. Stores hours, specials, menus, events, gallery, contact info, and social links.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.0
 * Author: GMW Systems
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
});
