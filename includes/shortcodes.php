<?php

defined('ABSPATH') or die;

function gmw_placeholder($message = '')
{
    if (!$message) {
        $message = __('Coming soon.', 'gmw-easy-manage');
    }
    return '<div class="gmw-placeholder">' . esc_html($message) . '</div>';
}

add_shortcode('gmw_hours', function ($atts) {
    $atts = shortcode_atts(['template' => 'table'], $atts);
    $data = gmw_get_data('hours');

    if (empty($data)) {
        return gmw_placeholder(__('Hours coming soon.', 'gmw-easy-manage'));
    }

    ob_start();
    ?>
    <table class="gmw-table gmw-hours">
        <?php foreach ($data as $row): ?>
            <tr>
                <td class="gmw-day"><?php echo esc_html($row['day']); ?></td>
                <td class="gmw-time">
                    <?php if (!empty($row['closed'])): ?>
                        <span class="gmw-closed"><?php esc_html_e('Closed', 'gmw-easy-manage'); ?></span>
                    <?php else: ?>
                        <?php echo esc_html($row['open']); ?> &ndash; <?php echo esc_html($row['close']); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_specials', function ($atts) {
    $atts = shortcode_atts(['template' => 'cards'], $atts);
    $data = gmw_get_data('specials');

    if (empty($data)) {
        return gmw_placeholder(__('Specials coming soon.', 'gmw-easy-manage'));
    }

    ob_start();
    ?>
    <div class="gmw-cards gmw-specials">
        <?php foreach ($data as $item): ?>
            <div class="gmw-card">
                <?php if (!empty($item['image_id'])): ?>
                    <?php echo wp_get_attachment_image($item['image_id'], 'medium', false, ['class' => 'gmw-card-image']); ?>
                <?php endif; ?>
                <h3 class="gmw-card-title"><?php echo esc_html($item['title']); ?></h3>
                <?php if (!empty($item['description'])): ?>
                    <div class="gmw-card-text"><?php echo wp_kses_post($item['description']); ?></div>
                <?php endif; ?>
                <?php if (!empty($item['valid_until'])): ?>
                    <div class="gmw-card-valid"><?php echo esc_html(sprintf(__('Valid until %s', 'gmw-easy-manage'), $item['valid_until'])); ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_happy_hours', function ($atts) {
    $atts = shortcode_atts(['template' => 'table'], $atts);
    $data = gmw_get_data('happy_hours');

    if (empty($data)) {
        return gmw_placeholder(__('Happy hours coming soon.', 'gmw-easy-manage'));
    }

    ob_start();
    ?>
    <table class="gmw-table gmw-happy-hours">
        <thead>
            <tr>
                <th><?php esc_html_e('Day', 'gmw-easy-manage'); ?></th>
                <th><?php esc_html_e('Time', 'gmw-easy-manage'); ?></th>
                <th><?php esc_html_e('Details', 'gmw-easy-manage'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td class="gmw-day"><?php echo esc_html($row['day']); ?></td>
                    <td class="gmw-time"><?php echo esc_html($row['start']); ?> &ndash; <?php echo esc_html($row['end']); ?></td>
                    <td class="gmw-desc"><?php echo wp_kses_post($row['description']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_menu', function ($atts) {
    $atts = shortcode_atts(['template' => 'link'], $atts);
    $data = gmw_get_data('menu');

    if (empty($data['pdf_attachment_id']) && empty($data['label'])) {
        return gmw_placeholder(__('Menu coming soon.', 'gmw-easy-manage'));
    }

    $url = $data['pdf_attachment_id'] ? wp_get_attachment_url($data['pdf_attachment_id']) : '';
    $label = !empty($data['label']) ? $data['label'] : __('View Menu', 'gmw-easy-manage');

    if (!$url) {
        return gmw_placeholder(__('Menu coming soon.', 'gmw-easy-manage'));
    }

    return '<a href="' . esc_url($url) . '" class="gmw-menu-link" target="_blank" rel="noopener">' . esc_html($label) . '</a>';
});

add_shortcode('gmw_events', function ($atts) {
    $atts = shortcode_atts(['template' => 'list'], $atts);
    $data = gmw_get_data('events');

    if (empty($data)) {
        return gmw_placeholder(__('Events coming soon.', 'gmw-easy-manage'));
    }

    ob_start();
    ?>
    <ul class="gmw-list gmw-events">
        <?php foreach ($data as $item): ?>
            <li class="gmw-event">
                <?php if (!empty($item['image_id'])): ?>
                    <?php echo wp_get_attachment_image($item['image_id'], 'thumbnail', false, ['class' => 'gmw-event-image']); ?>
                <?php endif; ?>
                <strong class="gmw-event-date"><?php echo esc_html($item['date']); ?></strong>
                <span class="gmw-event-title"><?php echo esc_html($item['title']); ?></span>
                <?php if (!empty($item['description'])): ?>
                    <div class="gmw-event-desc"><?php echo wp_kses_post($item['description']); ?></div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_gallery', function ($atts) {
    $atts = shortcode_atts(['template' => 'grid'], $atts);
    $data = gmw_get_data('gallery');

    if (empty($data)) {
        return gmw_placeholder(__('Gallery coming soon.', 'gmw-easy-manage'));
    }

    ob_start();
    ?>
    <div class="gmw-grid gmw-gallery">
        <?php foreach ($data as $attachment_id): ?>
            <?php $img = wp_get_attachment_image_src($attachment_id, 'large'); ?>
            <?php if ($img): ?>
                <a href="<?php echo esc_url($img[0]); ?>" class="gmw-gallery-item" rel="lightbox">
                    <?php echo wp_get_attachment_image($attachment_id, 'medium', false, ['class' => 'gmw-gallery-image', 'loading' => 'lazy']); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_contact', function ($atts) {
    $atts = shortcode_atts(['template' => 'card'], $atts);
    $data = gmw_get_data('contact');

    if (empty(array_filter($data))) {
        return gmw_placeholder(__('Contact info coming soon.', 'gmw-easy-manage'));
    }

    ob_start();
    ?>
    <div class="gmw-card gmw-contact">
        <?php if (!empty($data['phone'])): ?>
            <div class="gmw-contact-phone">
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $data['phone'])); ?>"><?php echo esc_html($data['phone']); ?></a>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['email'])): ?>
            <div class="gmw-contact-email">
                <a href="mailto:<?php echo esc_attr($data['email']); ?>"><?php echo esc_html($data['email']); ?></a>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['address'])): ?>
            <div class="gmw-contact-address"><?php echo esc_html($data['address']); ?></div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_social', function ($atts) {
    $atts = shortcode_atts(['template' => 'row'], $atts);
    $data = gmw_get_data('social');

    if (empty($data)) {
        return gmw_placeholder(__('Follow us on social media!', 'gmw-easy-manage'));
    }

    $labels = [
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'twitter' => 'Twitter',
        'x' => 'X',
        'tiktok' => 'TikTok',
        'youtube' => 'YouTube',
        'yelp' => 'Yelp',
        'linkedin' => 'LinkedIn',
        'pinterest' => 'Pinterest',
        'snapchat' => 'Snapchat',
    ];

    ob_start();
    ?>
    <div class="gmw-row gmw-social">
        <?php foreach ($data as $platform => $url): ?>
            <a href="<?php echo esc_url($url); ?>" class="gmw-social-link" target="_blank" rel="noopener noreferrer">
                <?php echo esc_html($labels[strtolower($platform)] ?? ucfirst($platform)); ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});

add_shortcode('gmw_stylebook', function () {
    if (!current_user_can('edit_theme_options')) {
        return '';
    }

    wp_enqueue_style('gmw-easy-manage-themes', GMW_EM_URL . 'assets/gmw-themes.css', ['gmw-easy-manage'], GMW_EM_VERSION);

    $demo = [
        'hours' => '<table class="gmw-table gmw-hours"><tbody><tr><td class="gmw-day">Monday</td><td class="gmw-time">9:00 AM &ndash; 10:00 PM</td></tr><tr><td class="gmw-day">Friday</td><td class="gmw-time">9:00 AM &ndash; 12:00 AM</td></tr><tr><td class="gmw-day">Sunday</td><td class="gmw-time">10:00 AM &ndash; 8:00 PM</td></tr></tbody></table>',
        'specials' => '<div class="gmw-cards gmw-specials"><div class="gmw-card"><h3 class="gmw-card-title">Happy Hour</h3><div class="gmw-card-text">$5 select beers and well drinks</div></div><div class="gmw-card"><h3 class="gmw-card-title">Taco Tuesday</h3><div class="gmw-card-text">$2 tacos all day</div></div></div>',
        'happy_hours' => '<table class="gmw-table gmw-happy-hours"><thead><tr><th>Day</th><th>Time</th><th>Details</th></tr></thead><tbody><tr><td class="gmw-day">Mon-Fri</td><td class="gmw-time">4:00 PM &ndash; 7:00 PM</td><td class="gmw-desc">$1 off all drafts</td></tr><tr><td class="gmw-day">Saturday</td><td class="gmw-time">3:00 PM &ndash; 5:00 PM</td><td class="gmw-desc">Half-price appetizers</td></tr></tbody></table>',
        'menu' => '<a href="#" class="gmw-menu-link" target="_blank" rel="noopener">View Our Menu</a>',
        'events' => '<ul class="gmw-list gmw-events"><li class="gmw-event"><strong class="gmw-event-date">2026-06-20</strong> <span class="gmw-event-title">Live Band</span><div class="gmw-event-desc">Local favorites take the stage</div></li><li class="gmw-event"><strong class="gmw-event-date">2026-07-04</strong> <span class="gmw-event-title">Independence Day Party</span><div class="gmw-event-desc">BBQ, drinks, and fireworks</div></li></ul>',
        'gallery' => '<div class="gmw-grid gmw-gallery"><p class="gmw-placeholder">Gallery coming soon.</p></div>',
        'contact' => '<div class="gmw-card gmw-contact"><div class="gmw-contact-phone"><a href="tel:+15551234567">(555) 123-4567</a></div><div class="gmw-contact-email"><a href="mailto:info@example.com">info@example.com</a></div><div class="gmw-contact-address">123 Main St, Anytown USA</div></div>',
        'social' => '<div class="gmw-row gmw-social"><a href="https://facebook.com/" class="gmw-social-link" target="_blank" rel="noopener noreferrer">Facebook</a><a href="https://instagram.com/" class="gmw-social-link" target="_blank" rel="noopener noreferrer">Instagram</a></div>',
    ];

    $themes = [
        'default' => 'Default',
        'dark' => 'Dark',
    ];

    ob_start();
    ?>
    <div class="gmw-stylebook">
        <h2><?php esc_html_e('GMW Easy Manage — Stylebook', 'gmw-easy-manage'); ?></h2>
        <p class="gmw-stylebook-intro"><?php esc_html_e('Below are all available shortcodes rendered with demo data. Theme developers can use this page to preview the default HTML templates.', 'gmw-easy-manage'); ?></p>
        <?php foreach ($demo as $key => $html): ?>
            <div class="gmw-stylebook-section">
                <h3><code>[gmw_<?php echo esc_html($key); ?>]</code></h3>
                <?php foreach ($themes as $theme => $label): ?>
                    <div class="gmw-stylebook-variant">
                        <span class="gmw-stylebook-label"><?php echo esc_html($label); ?></span>
                        <div class="gmw-stylebook-preview <?php echo $theme === 'default' ? '' : 'gmw-theme-' . esc_attr($theme); ?>">
                            <?php echo $html; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
});
