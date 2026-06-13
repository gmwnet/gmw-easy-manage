<?php

defined('ABSPATH') or die;

function gmw_placeholder($message = '')
{
    if (!$message) {
        $message = __('Coming soon.', 'gmw-easy-manage');
    }
    return '<div class="gmw-placeholder">' . esc_html($message) . '</div>';
}

function gmw_social_icon($platform)
{
    $svg = [
        'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>',
        'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'x' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'tiktok' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
        'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        'yelp' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20.16 12.594c.516-.162 1.494-.502 1.494-.502s-1.046-3.061-1.296-3.81c-.15-.443-.566-.634-1.111-.416-.386.156-3.341 1.391-3.341 1.391l-.016.004s2.686 2.435 3.276 2.971c.226.205.644.544.994.362zM5.392 17.152c.143.24.65.249 1.106.675.22.203.753.768 1.088 1.137.48.52.941.502 1.146.213.144-.202.632-2.656.632-2.656l.031-1.33s-3.527.97-3.597 1.133c-.025.064-.196.527-.406.828zm2.745-6.265c.107.354-.049.848-.184 1.239-.025.074-.048.145-.067.214-.044.152-.414 1.515-.55 2.019 0 0 3.49.626 4.163.743.627.09.869-.145.959-.319.064-.122.153-.474.139-.87-.021-.634-.758-3.912-.94-4.615-.164-.651-.453-.681-.767-.63-.549.092-2.687 1.464-2.687 1.464s-.066.404-.066.755zM14.48 20.27c.49.149.87.082 1.086-.355.199-.4 1.019-4.21 1.019-4.21l-.494-.778s-3.243 2.139-3.425 2.692c-.061.192-.242.746-.127.967.165.316.615.873 1.227 1.165.2.099.434.114.714-.081v-.4zM18.37 9.22c.382-.077 1.467-.277.467-.576 0-.001-3.233-1.157-3.923-1.398-.485-.17-.788.015-.885.365-.17.613-1.262 4.696-1.262 4.696s4.106-.802 4.482-.958c.016-.006.611-.964 1.121-2.129z"/></svg>',
        'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
        'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 0C5.372 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.628 0 12-5.372 12-12S18.628 0 12 0z"/></svg>',
        'snapchat' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 0C5.373 0 0 5.372 0 12c0 6.627 5.373 12 12 12s12-5.373 12-12S18.627 0 12 0zm-.002 4.194c1.426 0 2.719.505 3.682 1.366.964.862 1.488 2.024 1.488 3.242 0 .056-.02.543-.044 1.085-.024.542-.048 1.15-.048 1.35l.02.107c.067.305.381.545.803.545.2 0 .395-.053.553-.152.108-.068.276-.2.375-.293l.045-.04c.178-.164.429-.276.698-.276a.98.98 0 01.422.09c.655.302.995.838.995 1.566 0 .776-.555 1.501-1.554 2.028-.23.12-.49.215-.757.296a3.21 3.21 0 01-.94.206c-.096.252-.18.5-.229.631-.151.403-.42.728-.831.897-.228.094-.47.137-.707.148a2.88 2.88 0 01-.59-.052c-.308-.056-.614-.082-.896-.082-.513 0-.882.148-1.168.458-.15.162-.352.466-.4.64-.023.083-.016.133.004.196.066.208.29.345.57.446.235.084.502.13.617.158.28.068.377.25.377.481 0 .326-.268.54-.66.662-.14.045-.29.076-.423.104-.478.1-1.003.16-1.545.174-.366.01-.645.004-.888-.002.01.246.026.427.026.427s.002.012.006.036c.026.15.149.835-.863 1.003-.454.076-1.017.09-1.43.09-.413 0-.976-.014-1.43-.09-1.012-.168-.889-.853-.863-1.003.004-.024.006-.036.006-.036s.016-.181.026-.427c-.243.006-.522.012-.888.002-.542-.014-1.067-.074-1.545-.174-.133-.028-.283-.059-.423-.104-.392-.122-.66-.336-.66-.662 0-.231.097-.413.377-.481.115-.028.382-.074.617-.158.28-.101.504-.238.57-.446.02-.063.027-.113.004-.196-.048-.174-.25-.478-.4-.64-.286-.31-.655-.458-1.168-.458-.282 0-.588.026-.896.082a2.88 2.88 0 01-.59.052c-.237-.011-.479-.054-.707-.148-.411-.169-.68-.494-.831-.897-.049-.131-.133-.379-.229-.631a3.21 3.21 0 01-.94-.206c-.757-.304-1.554-.974-1.554-2.028 0-.728.34-1.264.995-1.566a.98.98 0 01.422-.09c.269 0 .52.112.698.276l.045.04c.099.093.267.225.375.293.158.099.353.152.553.152.422 0 .736-.24.803-.545l.02-.107c0-.2-.024-.808-.048-1.35s-.044-1.029-.044-1.085c0-1.218.524-2.38 1.488-3.242.963-.861 2.256-1.366 3.682-1.366z"/></svg>',
    ];

    $platform = strtolower($platform);
    return $svg[$platform] ?? '';
}

function gmw_theme_wrap($html, $theme)
{
    if ($theme && $theme !== 'default') {
        $class = 'gmw-theme-' . sanitize_html_class($theme);
        $html = '<div class="' . $class . '">' . $html . '</div>';
    }
    return $html;
}

add_shortcode('gmw_hours', function ($atts) {
    $atts = shortcode_atts(['template' => 'table', 'theme' => ''], $atts);
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
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_specials', function ($atts) {
    $atts = shortcode_atts(['template' => 'cards', 'theme' => ''], $atts);
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
                <?php if (!empty($item['url'])): ?>
                    <div class="gmw-card-link"><a href="<?php echo esc_url($item['url']); ?>"><?php esc_html_e('More info &raquo;', 'gmw-easy-manage'); ?></a></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_happy_hours', function ($atts) {
    $atts = shortcode_atts(['template' => 'table', 'theme' => ''], $atts);
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
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_menu', function ($atts) {
    $atts = shortcode_atts(['template' => 'link', 'theme' => ''], $atts);
    $data = gmw_get_data('menu');

    if (empty($data['pdf_attachment_id']) && empty($data['label'])) {
        return gmw_placeholder(__('Menu coming soon.', 'gmw-easy-manage'));
    }

    $url = $data['pdf_attachment_id'] ? wp_get_attachment_url($data['pdf_attachment_id']) : '';
    $label = !empty($data['label']) ? $data['label'] : __('View Menu', 'gmw-easy-manage');

    if (!$url) {
        return gmw_placeholder(__('Menu coming soon.', 'gmw-easy-manage'));
    }

    return gmw_theme_wrap('<a href="' . esc_url($url) . '" class="gmw-menu-link" target="_blank" rel="noopener">' . esc_html($label) . '</a>', $atts['theme']);
});

add_shortcode('gmw_events', function ($atts) {
    $atts = shortcode_atts(['template' => 'list', 'theme' => ''], $atts);
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
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_gallery', function ($atts) {
    $atts = shortcode_atts(['template' => 'grid', 'theme' => ''], $atts);
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
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_contact', function ($atts) {
    $atts = shortcode_atts(['template' => 'card', 'theme' => ''], $atts);
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
            <div class="gmw-contact-address"><?php echo esc_html($data['address']); ?>
                <a href="https://www.google.com/maps?q=<?php echo esc_attr(urlencode($data['address'])); ?>" class="gmw-contact-map" target="_blank" rel="noopener noreferrer"><?php esc_html_e('Map', 'gmw-easy-manage'); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_social', function ($atts) {
    $atts = shortcode_atts(['template' => 'row', 'theme' => ''], $atts);
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
                <?php echo gmw_social_icon($platform); ?>
                <?php echo esc_html($labels[strtolower($platform)] ?? ucfirst($platform)); ?>
            </a>
        <?php endforeach; ?>
    </div>
    <?php
    return gmw_theme_wrap(ob_get_clean(), $atts['theme']);
});

add_shortcode('gmw_alert', function ($atts) {
    $atts = shortcode_atts(['theme' => ''], $atts);
    $data = gmw_get_data('alert');

    if (empty($data['text'])) {
        return '';
    }

    $html = '<div class="gmw-banner gmw-alert" role="alert">';
    $html .= '<span class="gmw-banner-icon" aria-hidden="true">&#9888;</span>';
    if (!empty($data['url'])) {
        $html .= '<a href="' . esc_url($data['url']) . '" class="gmw-banner-link">';
        $html .= '<span class="gmw-banner-text">' . wp_kses_post($data['text']) . '</span>';
        $html .= '</a>';
    } else {
        $html .= '<span class="gmw-banner-text">' . wp_kses_post($data['text']) . '</span>';
    }
    $html .= '</div>';

    return gmw_theme_wrap($html, $atts['theme']);
});

add_shortcode('gmw_promotion', function ($atts) {
    $atts = shortcode_atts(['theme' => ''], $atts);
    $data = gmw_get_data('promotion');

    if (empty($data['text'])) {
        return '';
    }

    $html = '<div class="gmw-banner gmw-promotion">';
    $html .= '<span class="gmw-banner-icon" aria-hidden="true">&#33;</span>';
    if (!empty($data['url'])) {
        $html .= '<a href="' . esc_url($data['url']) . '" class="gmw-banner-link">';
        $html .= '<span class="gmw-banner-text">' . wp_kses_post($data['text']) . '</span>';
        $html .= '</a>';
    } else {
        $html .= '<span class="gmw-banner-text">' . wp_kses_post($data['text']) . '</span>';
    }
    $html .= '</div>';

    return gmw_theme_wrap($html, $atts['theme']);
});

add_shortcode('gmw_stylebook', function () {
    if (!current_user_can('read')) {
        return '';
    }

    wp_enqueue_style('gmw-easy-manage-themes', GMW_EM_URL . 'assets/gmw-themes.css', ['gmw-easy-manage'], GMW_EM_VERSION);

    $demo = [
        'hours' => '<table class="gmw-table gmw-hours"><tbody><tr><td class="gmw-day">Monday</td><td class="gmw-time">9:00 AM &ndash; 10:00 PM</td></tr><tr><td class="gmw-day">Friday</td><td class="gmw-time">9:00 AM &ndash; 12:00 AM</td></tr><tr><td class="gmw-day">Sunday</td><td class="gmw-time">10:00 AM &ndash; 8:00 PM</td></tr></tbody></table>',
        'specials' => '<div class="gmw-cards gmw-specials"><div class="gmw-card"><h3 class="gmw-card-title">Happy Hour</h3><div class="gmw-card-text">$5 select beers and well drinks</div><div class="gmw-card-link"><a href="#">More info &raquo;</a></div></div><div class="gmw-card"><h3 class="gmw-card-title">Taco Tuesday</h3><div class="gmw-card-text">$2 tacos all day</div></div></div>',
        'happy_hours' => '<table class="gmw-table gmw-happy-hours"><thead><tr><th>Day</th><th>Time</th><th>Details</th></tr></thead><tbody><tr><td class="gmw-day">Mon-Fri</td><td class="gmw-time">4:00 PM &ndash; 7:00 PM</td><td class="gmw-desc">$1 off all drafts</td></tr><tr><td class="gmw-day">Saturday</td><td class="gmw-time">3:00 PM &ndash; 5:00 PM</td><td class="gmw-desc">Half-price appetizers</td></tr></tbody></table>',
        'menu' => '<a href="#" class="gmw-menu-link" target="_blank" rel="noopener">View Our Menu</a>',
        'events' => '<ul class="gmw-list gmw-events"><li class="gmw-event"><strong class="gmw-event-date">2026-06-20</strong> <span class="gmw-event-title">Live Band</span><div class="gmw-event-desc">Local favorites take the stage</div></li><li class="gmw-event"><strong class="gmw-event-date">2026-07-04</strong> <span class="gmw-event-title">Independence Day Party</span><div class="gmw-event-desc">BBQ, drinks, and fireworks</div></li></ul>',
        'gallery' => '<div class="gmw-grid gmw-gallery"><p class="gmw-placeholder">Gallery coming soon.</p></div>',
        'contact' => '<div class="gmw-card gmw-contact"><div class="gmw-contact-phone"><a href="tel:+15551234567">(555) 123-4567</a></div><div class="gmw-contact-email"><a href="mailto:info@example.com">info@example.com</a></div><div class="gmw-contact-address">123 Main St, Anytown USA <a href="https://www.google.com/maps?q=123+Main+St%2C+Anytown+USA" class="gmw-contact-map" target="_blank" rel="noopener noreferrer">Map</a></div></div>',
        'social' => '<div class="gmw-row gmw-social"><a href="https://facebook.com/" class="gmw-social-link" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> Facebook</a><a href="https://instagram.com/" class="gmw-social-link" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg> Instagram</a></div>',
        'alert' => '<div class="gmw-banner gmw-alert" role="alert"><span class="gmw-banner-icon" aria-hidden="true">&#9888;</span><span class="gmw-banner-text">Weather closure: Opening at noon today due to snow.</span></div>',
        'promotion' => '<div class="gmw-banner gmw-promotion"><span class="gmw-banner-icon" aria-hidden="true">&#33;</span><a href="#" class="gmw-banner-link"><span class="gmw-banner-text">Super Bowl Party! Join us Feb 9 &mdash; reserve your table now!</span></a></div>',
    ];

    $themes = [
        'default' => ['label' => 'Default', 'class' => '', 'attr' => ''],
        'default-transparent' => ['label' => 'Default Transparent', 'class' => 'gmw-preview-transparent', 'attr' => ''],
        'dark' => ['label' => 'Dark', 'class' => 'gmw-theme-dark', 'attr' => ' theme="dark"'],
        'dark-transparent' => ['label' => 'Dark Transparent', 'class' => 'gmw-theme-dark gmw-preview-transparent', 'attr' => ' theme="dark"'],
    ];

    ob_start();
    ?>
    <div class="gmw-stylebook">
        <h1><?php esc_html_e('GMW Easy Manage — Documentation', 'gmw-easy-manage'); ?></h1>
        <p class="gmw-stylebook-intro"><?php esc_html_e('Use the shortcodes below in any page, post, or widget to display your business content. Each section shows the available style variations.', 'gmw-easy-manage'); ?> <a href="#gmw-tech-info" class="gmw-stylebook-tech-link"><?php esc_html_e('Technical info', 'gmw-easy-manage'); ?></a></p>

        <div class="gmw-stylebook-quickref">
            <h2><?php esc_html_e('Quick Reference', 'gmw-easy-manage'); ?></h2>
            <table class="gmw-table">
                <thead><tr><th><?php esc_html_e('Shortcode', 'gmw-easy-manage'); ?></th><th><?php esc_html_e('Displays', 'gmw-easy-manage'); ?></th><th><?php esc_html_e('Default Template', 'gmw-easy-manage'); ?></th></tr></thead>
                <tbody>
                    <tr><td><code>[gmw_hours]</code></td><td><?php esc_html_e('Business hours', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('table', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_specials]</code></td><td><?php esc_html_e('Specials &amp; promotions', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('cards', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_happy_hours]</code></td><td><?php esc_html_e('Happy hour schedule', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('table', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_menu]</code></td><td><?php esc_html_e('Menu PDF link', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('link', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_events]</code></td><td><?php esc_html_e('Upcoming events', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('list', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_gallery]</code></td><td><?php esc_html_e('Photo gallery', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('grid', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_contact]</code></td><td><?php esc_html_e('Contact information', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('card', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_social]</code></td><td><?php esc_html_e('Social media links', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('row', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_alert]</code></td><td><?php esc_html_e('Alert banner (closures, urgent)', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('banner', 'gmw-easy-manage'); ?></td></tr>
                    <tr><td><code>[gmw_promotion]</code></td><td><?php esc_html_e('Promotion banner (events, big news)', 'gmw-easy-manage'); ?></td><td><?php esc_html_e('banner', 'gmw-easy-manage'); ?></td></tr>
                </tbody>
            </table>
            <p><em><?php esc_html_e('Append', 'gmw-easy-manage'); ?> <code>template="alt"</code> <?php esc_html_e('for alternative templates, or', 'gmw-easy-manage'); ?> <code>theme="dark"</code> <?php esc_html_e('for dark theme styling.', 'gmw-easy-manage'); ?></em></p>
        </div>

        <h2><?php esc_html_e('Style Preview', 'gmw-easy-manage'); ?></h2>
        <?php foreach ($demo as $key => $html): ?>
            <div class="gmw-stylebook-section">
                <h3><code>[gmw_<?php echo esc_html($key); ?>]</code></h3>
                <?php foreach ($themes as $theme => $config): ?>
                    <div class="gmw-stylebook-variant">
                        <div class="gmw-stylebook-shortcode"><code>[gmw_<?php echo esc_html($key); ?><?php echo esc_html($config['attr']); ?>]</code></div>
                        <span class="gmw-stylebook-label"><?php echo esc_html($config['label']); ?></span>
                        <div class="gmw-stylebook-preview<?php echo $config['class'] ? ' ' . esc_attr($config['class']) : ''; ?>">
                            <?php echo $html; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="gmw-stylebook-tech" id="gmw-tech-info">
            <h2><?php esc_html_e('Technical Information', 'gmw-easy-manage'); ?></h2>
            <p><?php esc_html_e('All content displayed by these shortcodes is stored in your WordPress database under wp_options. Each shortcode pulls from its own option key — for example, hours data lives in gmw_hours, specials in gmw_specials, and so on. The GMW Easy Manage admin interface at apps.gmwsys.com writes directly to these options via your site database, making it easy for non-technical staff to keep content updated without logging into WordPress.', 'gmw-easy-manage'); ?></p>
            <p><?php esc_html_e('If you are a GMW Systems hosting client, this plugin works with your GMW Easy Manage dashboard out of the box. Each option stores autoload disabled, so the data only loads on pages where the corresponding shortcode is used.', 'gmw-easy-manage'); ?></p>
            <p><?php esc_html_e('If not hosted with GMW Systems, content management requires a technical user. Data can be edited directly via WP-CLI (wp gmw get/update on the server) or programmatically using gmw_get_data() and gmw_update_data() in your theme or plugin code. Contact GMW Systems for onboarding and setup assistance.', 'gmw-easy-manage'); ?></p>
            <hr>
            <p class="gmw-stylebook-license"><?php esc_html_e('GMW Easy Manage — Open source under the MIT License.', 'gmw-easy-manage'); ?></p>
            <p class="gmw-stylebook-license-text"><?php esc_html_e('Copyright (c) 2026 GMW Systems', 'gmw-easy-manage'); ?><br>
            <?php esc_html_e('Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:', 'gmw-easy-manage'); ?></p>
            <p class="gmw-stylebook-license-text"><?php esc_html_e('The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.', 'gmw-easy-manage'); ?></p>
            <p class="gmw-stylebook-license-text"><?php esc_html_e('THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.', 'gmw-easy-manage'); ?></p>
            <p><a href="#"><?php esc_html_e('Back to top', 'gmw-easy-manage'); ?></a></p>
        </div>
    </div>
    <?php
    return ob_get_clean();
});
