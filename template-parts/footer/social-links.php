<?php
/**
 * Template part for displaying footer social links
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$show_footer_social = get_theme_mod('show_footer_social', true);
if (!$show_footer_social) {
    return;
}

$social_style = get_theme_mod('footer_social_style', 'icons');
$social_size = get_theme_mod('footer_social_size', 'medium');
$social_shape = get_theme_mod('footer_social_shape', 'rounded');
$show_labels = get_theme_mod('footer_social_show_labels', false);

// Social links configuration
$social_links = [
    'facebook' => [
        'url' => get_theme_mod('social_facebook', ''),
        'icon' => 'fab fa-facebook-f',
        'label' => __('Facebook', 'pratikwp'),
        'color' => '#1877f2'
    ],
    'twitter' => [
        'url' => get_theme_mod('social_twitter', ''),
        'icon' => 'fab fa-twitter',
        'label' => __('Twitter', 'pratikwp'),
        'color' => '#1da1f2'
    ],
    'instagram' => [
        'url' => get_theme_mod('social_instagram', ''),
        'icon' => 'fab fa-instagram',
        'label' => __('Instagram', 'pratikwp'),
        'color' => '#e4405f'
    ],
    'linkedin' => [
        'url' => get_theme_mod('social_linkedin', ''),
        'icon' => 'fab fa-linkedin-in',
        'label' => __('LinkedIn', 'pratikwp'),
        'color' => '#0077b5'
    ],
    'youtube' => [
        'url' => get_theme_mod('social_youtube', ''),
        'icon' => 'fab fa-youtube',
        'label' => __('YouTube', 'pratikwp'),
        'color' => '#ff0000'
    ],
    'tiktok' => [
        'url' => get_theme_mod('social_tiktok', ''),
        'icon' => 'fab fa-tiktok',
        'label' => __('TikTok', 'pratikwp'),
        'color' => '#000000'
    ],
    'pinterest' => [
        'url' => get_theme_mod('social_pinterest', ''),
        'icon' => 'fab fa-pinterest',
        'label' => __('Pinterest', 'pratikwp'),
        'color' => '#bd081c'
    ],
    'telegram' => [
        'url' => get_theme_mod('social_telegram', ''),
        'icon' => 'fab fa-telegram',
        'label' => __('Telegram', 'pratikwp'),
        'color' => '#0088cc'
    ],
    'whatsapp' => [
        'url' => get_theme_mod('social_whatsapp', ''),
        'icon' => 'fab fa-whatsapp',
        'label' => __('WhatsApp', 'pratikwp'),
        'color' => '#25d366'
    ],
    'discord' => [
        'url' => get_theme_mod('social_discord', ''),
        'icon' => 'fab fa-discord',
        'label' => __('Discord', 'pratikwp'),
        'color' => '#7289da'
    ],
    'github' => [
        'url' => get_theme_mod('social_github', ''),
        'icon' => 'fab fa-github',
        'label' => __('GitHub', 'pratikwp'),
        'color' => '#333333'
    ],
    'dribbble' => [
        'url' => get_theme_mod('social_dribbble', ''),
        'icon' => 'fab fa-dribbble',
        'label' => __('Dribbble', 'pratikwp'),
        'color' => '#ea4c89'
    ]
];

// Filter out empty links
$active_links = array_filter($social_links, function($link) {
    return !empty($link['url']);
});

if (empty($active_links)) {
    return;
}

// Size classes
$size_classes = [
    'small' => 'btn-sm',
    'medium' => '',
    'large' => 'btn-lg'
];

// Shape classes
$shape_classes = [
    'square' => '',
    'rounded' => 'rounded',
    'circle' => 'rounded-circle'
];

$footer_social_bg = get_theme_mod('footer_style', 'dark');
?>

<div class="footer-social-section bg-<?php echo esc_attr($footer_social_bg); ?> text-<?php echo $footer_social_bg === 'light' ? 'dark' : 'light'; ?> py-4 border-top">
    <div class="container">
        <div class="row">
            <div class="col-12">
                
                <?php if (get_theme_mod('footer_social_title', '')) : ?>
                <div class="footer-social-header text-center mb-4">
                    <h5 class="footer-social-title mb-2">
                        <?php echo esc_html(get_theme_mod('footer_social_title', '')); ?>
                    </h5>
                    <?php if (get_theme_mod('footer_social_subtitle', '')) : ?>
                    <p class="footer-social-subtitle text-muted mb-0">
                        <?php echo esc_html(get_theme_mod('footer_social_subtitle', '')); ?>
                    </p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="footer-social-links text-center">
                    
                    <?php if ($social_style === 'buttons') : ?>
                    <!-- Button Style -->
                    <div class="social-buttons d-flex flex-wrap justify-content-center gap-2">
                        <?php foreach ($active_links as $platform => $data) : 
                            $target = ($platform === 'whatsapp') ? '_self' : '_blank';
                            $href = ($platform === 'whatsapp') ? 'https://wa.me/' . $data['url'] : $data['url'];
                        ?>
                        <a href="<?php echo esc_url($href); ?>" 
                           target="<?php echo esc_attr($target); ?>"
                           class="btn btn-outline-light <?php echo esc_attr($size_classes[$social_size]); ?> <?php echo esc_attr($shape_classes[$social_shape]); ?> social-btn-<?php echo esc_attr($platform); ?>"
                           style="border-color: <?php echo esc_attr($data['color']); ?>; color: <?php echo esc_attr($data['color']); ?>;"
                           aria-label="<?php echo esc_attr($data['label']); ?>"
                           <?php if ($target === '_blank') echo 'rel="noopener noreferrer"'; ?>>
                            <i class="<?php echo esc_attr($data['icon']); ?><?php echo $show_labels ? ' me-2' : ''; ?>"></i>
                            <?php if ($show_labels) echo esc_html($data['label']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>

                    <?php elseif ($social_style === 'colored') : ?>
                    <!-- Colored Style -->
                    <div class="social-colored d-flex flex-wrap justify-content-center gap-2">
                        <?php foreach ($active_links as $platform => $data) : 
                            $target = ($platform === 'whatsapp') ? '_self' : '_blank';
                            $href = ($platform === 'whatsapp') ? 'https://wa.me/' . $data['url'] : $data['url'];
                        ?>
                        <a href="<?php echo esc_url($href); ?>" 
                           target="<?php echo esc_attr($target); ?>"
                           class="btn <?php echo esc_attr($size_classes[$social_size]); ?> <?php echo esc_attr($shape_classes[$social_shape]); ?> text-white social-btn-<?php echo esc_attr($platform); ?>"
                           style="background-color: <?php echo esc_attr($data['color']); ?>; border-color: <?php echo esc_attr($data['color']); ?>;"
                           aria-label="<?php echo esc_attr($data['label']); ?>"
                           <?php if ($target === '_blank') echo 'rel="noopener noreferrer"'; ?>>
                            <i class="<?php echo esc_attr($data['icon']); ?><?php echo $show_labels ? ' me-2' : ''; ?>"></i>
                            <?php if ($show_labels) echo esc_html($data['label']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>

                    <?php else : ?>
                    <!-- Icon Style (Default) -->
                    <div class="social-icons d-flex flex-wrap justify-content-center gap-3">
                        <?php foreach ($active_links as $platform => $data) : 
                            $target = ($platform === 'whatsapp') ? '_self' : '_blank';
                            $href = ($platform === 'whatsapp') ? 'https://wa.me/' . $data['url'] : $data['url'];
                            
                            $icon_size = '';
                            switch ($social_size) {
                                case 'small':
                                    $icon_size = 'fa-lg';
                                    break;
                                case 'large':
                                    $icon_size = 'fa-2x';
                                    break;
                                default:
                                    $icon_size = 'fa-xl';
                            }
                        ?>
                        <a href="<?php echo esc_url($href); ?>" 
                           target="<?php echo esc_attr($target); ?>"
                           class="social-icon text-decoration-none social-icon-<?php echo esc_attr($platform); ?>"
                           style="color: <?php echo esc_attr($data['color']); ?>; transition: all 0.3s ease;"
                           aria-label="<?php echo esc_attr($data['label']); ?>"
                           title="<?php echo esc_attr($data['label']); ?>"
                           <?php if ($target === '_blank') echo 'rel="noopener noreferrer"'; ?>
                           onmouseover="this.style.transform='scale(1.2)'; this.style.opacity='0.8';"
                           onmouseout="this.style.transform='scale(1)'; this.style.opacity='1';">
                            <i class="<?php echo esc_attr($data['icon']); ?> <?php echo esc_attr($icon_size); ?>"></i>
                            <?php if ($show_labels) : ?>
                            <span class="social-label d-block mt-1 small"><?php echo esc_html($data['label']); ?></span>
                            <?php endif; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>

                <?php if (get_theme_mod('footer_social_newsletter', false)) : ?>
                <!-- Newsletter Signup -->
                <div class="footer-newsletter mt-4 pt-4 border-top text-center">
                    <h6 class="newsletter-title mb-3">
                        <?php echo esc_html(get_theme_mod('newsletter_title', __('Bültene Abone Ol', 'pratikwp'))); ?>
                    </h6>
                    <p class="newsletter-description mb-3 text-muted">
                        <?php echo esc_html(get_theme_mod('newsletter_description', __('Güncel haberler ve özel tekliflerden haberdar olun.', 'pratikwp'))); ?>
                    </p>
                    
                    <?php
                    $newsletter_shortcode = get_theme_mod('newsletter_shortcode', '');
                    if ($newsletter_shortcode) {
                        echo do_shortcode($newsletter_shortcode);
                    } else {
                        // Fallback form
                        ?>
                        <form class="newsletter-form d-flex justify-content-center" action="#" method="post">
                            <div class="input-group" style="max-width: 400px;">
                                <input type="email" class="form-control" placeholder="<?php esc_attr_e('E-posta adresiniz', 'pratikwp'); ?>" required>
                                <button class="btn btn-primary" type="submit">
                                    <?php esc_html_e('Abone Ol', 'pratikwp'); ?>
                                </button>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>