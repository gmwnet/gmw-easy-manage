<?php

defined('ABSPATH') or die;

class GMW_CLI extends WP_CLI_Command
{
    public function get($args)
    {
        if (empty($args[0])) {
            WP_CLI::error('Usage: wp gmw get <key>');
        }

        $key = sanitize_key($args[0]);
        $data = gmw_get_data($key);
        WP_CLI::line(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function update($args)
    {
        if (count($args) < 2) {
            WP_CLI::error('Usage: wp gmw update <key> <json>');
        }

        $key = sanitize_key($args[0]);
        $data = json_decode($args[1], true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            WP_CLI::error('Invalid JSON: ' . json_last_error_msg());
        }

        $result = gmw_update_data($key, $data);
        WP_CLI::success("Updated gmw_{$key}");
    }

    public function upload($args)
    {
        if (empty($args[0])) {
            WP_CLI::error('Usage: wp gmw upload <file> [name]');
        }

        $file_path = $args[0];
        if (!file_exists($file_path)) {
            WP_CLI::error("File not found: {$file_path}");
        }

        $filename = !empty($args[1]) ? $args[1] : basename($file_path);

        $file_contents = file_get_contents($file_path);
        $upload = wp_upload_bits($filename, null, $file_contents);

        if (!empty($upload['error'])) {
            WP_CLI::error('Upload failed: ' . $upload['error']);
        }

        $attachment_id = wp_insert_attachment([
            'post_title' => pathinfo($filename, PATHINFO_FILENAME),
            'post_mime_type' => mime_content_type($file_path),
            'guid' => $upload['url'],
        ], $upload['file']);

        if (!$attachment_id) {
            WP_CLI::error('Failed to create attachment');
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $upload['file']));

        WP_CLI::line((string) $attachment_id);
    }

    public function revoke($args)
    {
        if (empty($args[0])) {
            WP_CLI::error('Usage: wp gmw revoke <username>');
        }

        $username = sanitize_user($args[0]);
        $user = get_user_by('login', $username);

        if (!$user) {
            WP_CLI::error("User not found: {$username}");
        }

        $user->set_role('subscriber');

        $new_password = wp_generate_password(24, true, true);
        wp_set_password($new_password, $user->ID);

        WP_CLI::success("User {$username} demoted to Subscriber with a new random password");
    }

    public function status()
    {
        global $wpdb;

        $option_keys = [
            'gmw_hours',
            'gmw_specials',
            'gmw_happy_hours',
            'gmw_menu',
            'gmw_events',
            'gmw_gallery',
            'gmw_contact',
            'gmw_social',
        ];

        WP_CLI::line('GMW Easy Manage Status');
        WP_CLI::line('Version: ' . GMW_EM_VERSION);
        WP_CLI::line('');

        foreach ($option_keys as $key) {
            $value = get_option($key);
            $size = $value ? strlen(maybe_serialize($value)) : 0;
            WP_CLI::line(sprintf('  %-25s %s bytes', $key, number_format($size)));
        }

        WP_CLI::line('');
        WP_CLI::line('Total options: ' . count($option_keys));
    }
}

WP_CLI::add_command('gmw', 'GMW_CLI');
