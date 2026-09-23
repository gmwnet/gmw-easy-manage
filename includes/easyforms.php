<?php

defined('ABSPATH') or die;

// GMW EasyForms module — [gmw_easyform org="..." form="..." refresh="1|0"]
// Renders the hosted form's fields inline. The embed fragment is fetched from
// the host ONCE and stored as a local file (wp-content/uploads/gmw-forms/) —
// page renders never call the EasyForms host (Varnish-cache friendly, static
// HTML). Pass refresh="1" to re-fetch after a form change on the host.
// Submission is a cross-origin fetch POST to the host, which does ALL
// validation (origin, honeypot, rate limit, Altcha PoW, fields) then stores +
// emails and returns {ok, id}; the plugin swaps the form for a thank-you.

define('GMW_EF_DEFAULT_HOST', 'https://easyforms.gmwtest.net');

function gmw_ef_host()
{
    $host = defined('GMW_EF_HOST') ? GMW_EF_HOST : GMW_EF_DEFAULT_HOST;
    return rtrim($host, '/');
}

function gmw_ef_form_url($org, $form)
{
    return gmw_ef_host() . '/' . rawurlencode($org) . '/forms/' . rawurlencode($form);
}

function gmw_ef_fragment_dir()
{
    $up = wp_upload_dir();
    return trailingslashit($up['basedir']) . 'gmw-forms';
}

function gmw_ef_ensure_fragment_dir()
{
    $dir = gmw_ef_fragment_dir();
    if (!is_dir($dir)) {
        wp_mkdir_p($dir);
        // Block direct web access to the cached fragments (uploads/ is public by default).
        @file_put_contents($dir . '/.htaccess', "Require all denied\n");
        @file_put_contents($dir . '/index.php', "<?php\n// Silence is golden.\n");
    }
    return is_dir($dir);
}

function gmw_ef_fragment_file($org, $form)
{
    return gmw_ef_fragment_dir() . '/' . $org . '-' . $form . '.frag.html';
}

function gmw_ef_fetch_fragment($org, $form, $refresh = false)
{
    $file = gmw_ef_fragment_file($org, $form);

    if (!$refresh && is_file($file)) {
        $cached = file_get_contents($file);
        if ($cached !== false && trim($cached) !== '') {
            return $cached;
        }
    }

    $url = gmw_ef_form_url($org, $form) . '/embed';
    $resp = wp_remote_get($url, ['timeout' => 10, 'redirection' => 2]);
    if (is_wp_error($resp)) {
        if (is_file($file)) {
            $cached = file_get_contents($file);
            if ($cached !== false && trim($cached) !== '') {
                return $cached;
            }
        }
        return '<!-- GMW EasyForms: fetch failed (' . esc_html($resp->get_error_message()) . ') -->';
    }
    $code = (int)wp_remote_retrieve_response_code($resp);
    if ($code !== 200) {
        if (is_file($file)) {
            $cached = file_get_contents($file);
            if ($cached !== false && trim($cached) !== '') {
                return $cached;
            }
        }
        return '<!-- GMW EasyForms: host returned HTTP ' . $code . ' -->';
    }
    $body = wp_remote_retrieve_body($resp);

    // Persist locally — page renders never call the host again until refresh.
    if (gmw_ef_ensure_fragment_dir()) {
        @file_put_contents($file, $body, LOCK_EX);
    }
    return $body;
}

function gmw_easyform_shortcode($atts)
{
    $atts = shortcode_atts([
        'org' => '',
        'form' => '',
        'refresh' => '',
        'thank_you' => '',
    ], $atts, 'gmw_easyform');

    $org = sanitize_title($atts['org']);
    $form = sanitize_title($atts['form']);
    if ($org === '' || $form === '') {
        return '<!-- GMW EasyForms: org and form attributes are required. -->';
    }

    $refresh = $atts['refresh'] === '1' || $atts['refresh'] === 'yes' || $atts['refresh'] === 'true';
    $fragment = gmw_ef_fetch_fragment($org, $form, $refresh);

    wp_enqueue_style('gmw-easyforms-embed', GMW_EM_URL . 'assets/gmw-easyform.css', [], GMW_EM_VERSION);
    wp_enqueue_script('gmw-easyforms-embed', GMW_EM_URL . 'assets/gmw-easyform.js', [], GMW_EM_VERSION, true);

    $thankYou = $atts['thank_you'] !== ''
        ? wp_kses_post($atts['thank_you'])
        : 'Thank you! Your submission was received. We\'ll be in touch if we have any questions.';

    $html = '<div class="gmw-easyform" data-org="' . esc_attr($org) . '" data-form="' . esc_attr($form) . '"'
        . ' data-host="' . esc_attr(gmw_ef_host()) . '"'
        . ' data-thank-you="' . esc_attr($thankYou) . '">';
    $html .= $fragment;
    $html .= '</div>';
    return $html;
}
add_shortcode('gmw_easyform', 'gmw_easyform_shortcode');