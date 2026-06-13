<?php

defined('ABSPATH') or die;

function gmw_get_data($key)
{
    $data = get_option("gmw_{$key}", null);

    if ($data === null) {
        $defaults = [
            'hours' => [],
            'specials' => [],
            'happy_hours' => [],
            'menu' => ['pdf_attachment_id' => 0, 'label' => ''],
            'events' => [],
            'gallery' => [],
            'contact' => ['phone' => '', 'email' => '', 'address' => ''],
            'social' => [],
            'alert' => ['text' => '', 'url' => ''],
            'promotion' => ['text' => '', 'url' => ''],
        ];

        $data = isset($defaults[$key]) ? $defaults[$key] : [];
    }

    return $data;
}

function gmw_update_data($key, $data)
{
    $sanitized = gmw_sanitize_data($key, $data);
    update_option("gmw_{$key}", $sanitized, false);
    return $sanitized;
}

function gmw_delete_data($key)
{
    delete_option("gmw_{$key}");
}

function gmw_sanitize_data($key, $data)
{
    switch ($key) {
        case 'hours':
            if (!is_array($data)) return [];
            return array_map(function ($item) {
                return [
                    'day' => sanitize_text_field($item['day'] ?? ''),
                    'open' => sanitize_text_field($item['open'] ?? ''),
                    'close' => sanitize_text_field($item['close'] ?? ''),
                    'closed' => !empty($item['closed']),
                ];
            }, $data);

        case 'specials':
            if (!is_array($data)) return [];
            return array_map(function ($item) {
                return [
                    'title' => sanitize_text_field($item['title'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                    'image_id' => absint($item['image_id'] ?? 0),
                    'url' => esc_url_raw($item['url'] ?? ''),
                    'valid_until' => sanitize_text_field($item['valid_until'] ?? ''),
                ];
            }, $data);

        case 'happy_hours':
            if (!is_array($data)) return [];
            return array_map(function ($item) {
                return [
                    'day' => sanitize_text_field($item['day'] ?? ''),
                    'start' => sanitize_text_field($item['start'] ?? ''),
                    'end' => sanitize_text_field($item['end'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                ];
            }, $data);

        case 'menu':
            return [
                'pdf_attachment_id' => absint($data['pdf_attachment_id'] ?? 0),
                'label' => sanitize_text_field($data['label'] ?? ''),
            ];

        case 'events':
            if (!is_array($data)) return [];
            return array_map(function ($item) {
                return [
                    'date' => sanitize_text_field($item['date'] ?? ''),
                    'title' => sanitize_text_field($item['title'] ?? ''),
                    'description' => wp_kses_post($item['description'] ?? ''),
                    'image_id' => absint($item['image_id'] ?? 0),
                ];
            }, $data);

        case 'gallery':
            if (!is_array($data)) return [];
            return array_map('absint', $data);

        case 'contact':
            return [
                'phone' => sanitize_text_field($data['phone'] ?? ''),
                'email' => sanitize_email($data['email'] ?? ''),
                'address' => sanitize_textarea_field($data['address'] ?? ''),
            ];

        case 'alert':
            return [
                'text' => wp_kses_post($data['text'] ?? ''),
                'url' => esc_url_raw($data['url'] ?? ''),
            ];

        case 'promotion':
            return [
                'text' => wp_kses_post($data['text'] ?? ''),
                'url' => esc_url_raw($data['url'] ?? ''),
            ];

        case 'social':
            if (!is_array($data)) return [];
            $sanitized = [];
            foreach ($data as $platform => $url) {
                $platform = sanitize_text_field($platform);
                $url = esc_url_raw($url);
                if ($platform && $url) {
                    $sanitized[$platform] = $url;
                }
            }
            return $sanitized;

        default:
            return $data;
    }
}
