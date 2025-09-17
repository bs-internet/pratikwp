<?php
/**
 * Template part for displaying footer copyright
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$show_copyright = get_theme_mod('show_copyright', true);
if (!$show_copyright) {
    return;
}

$copyright_style = get_theme_mod('copyright_style', 'dark');
$copyright_text = get_theme_mod('copyright_text', '');
$show_footer_menu = get_theme_mod('show_footer_menu', true);
$show_payment_icons = get_theme_mod('show_payment_icons', false);
$show_ssl_badge = get_theme_mod('show_ssl_badge', false);

// Default copyright text
if (empty($copyright_text)) {
    $copyright_text = sprintf(
        esc_html__('© %1$s %2$s. Tüm hakları saklıdır.', 'pratikwp'),
        date('Y'),
        get_bloginfo('name')
    );
}

// Parse shortcodes and placeholders
$copyright_text = str_replace(
    ['{year}', '{site_name}', '{site_url}'],
    [date('Y'), get_bloginfo('name'), home_url()],
    $copyright_text
);
$copyright_text = do_shortcode($copyright_text);
?>

<div class="footer-copyright bg-<?php echo esc_attr($copyright_style); ?> text-<?php echo $copyright_style === 'light' ? 'dark' : 'light'; ?> py-3 border-top">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Copyright Text -->
            <div class="col-lg-6 col-md-12 text-center text-lg-start mb-3 mb-lg-0">
                <div class="copyright-text">
                    <?php echo wp_kses_post($copyright_text); ?>
                </div>
                
                <?php if (get_theme_mod('show_theme_credit', false)) : ?>
                <div class="theme-credit mt-1 small text-muted">
                    <?php
                    printf(
                        esc_html__('WordPress teması: %s', 'pratikwp'),
                        '<a href="#" class="text-decoration-none">PratikWP</a>'
                    );
                    ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Footer Menu & Icons -->
            <div class="col-lg-6 col-md-12">
                <div class="footer-right d-flex flex-column flex-lg-row align-items-center justify-content-lg-end">
                    
                    <!-- Footer Menu -->
                    <?php if ($show_footer_menu && has_nav_menu('footer')) : ?>
                    <nav class="footer-menu mb-2 mb-lg-0 me-lg-4" role="navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class' => 'footer-nav d-flex flex-wrap justify-content-center justify-content-lg-end list-unstyled mb-0',
                            'container' => false,
                            'depth' => 1,
                            'link_before' => '<span class="footer-link px-2">',
                            'link_after' => '</span>',
                            'fallback_cb' => false
                        ]);
                        ?>
                    </nav>
                    <?php endif; ?>

                    <!-- Payment Icons -->
                    <?php if ($show_payment_icons && class_exists('WooCommerce')) : ?>
                    <div class="payment-icons d-flex align-items-center mb-2 mb-lg-0 me-lg-3">
                        <?php
                        $payment_methods = get_theme_mod('payment_methods', ['visa', 'mastercard', 'paypal']);
                        $payment_icons = [
                            'visa' => 'fab fa-cc-visa',
                            'mastercard' => 'fab fa-cc-mastercard',
                            'amex' => 'fab fa-cc-amex',
                            'paypal' => 'fab fa-cc-paypal',
                            'apple-pay' => 'fab fa-cc-apple-pay',
                            'google-pay' => 'fab fa-google-pay',
                            'discover' => 'fab fa-cc-discover',
                            'stripe' => 'fab fa-cc-stripe'
                        ];
                        
                        if (is_array($payment_methods)) {
                            foreach ($payment_methods as $method) {
                                if (isset($payment_icons[$method])) {
                                    echo '<i class="' . esc_attr($payment_icons[$method]) . ' me-2 text-muted" title="' . esc_attr(ucfirst($method)) . '"></i>';
                                }
                            }
                        }
                        ?>
                    </div>
                    <?php endif; ?>

                    <!-- SSL Badge -->
                    <?php if ($show_ssl_badge && is_ssl()) : ?>
                    <div class="ssl-badge d-flex align-items-center">
                        <i class="fas fa-lock me-1 text-success" title="<?php esc_attr_e('SSL Güvenli Bağlantı', 'pratikwp'); ?>"></i>
                        <small class="text-muted"><?php esc_html_e('Güvenli', 'pratikwp'); ?></small>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>

        <?php if (get_theme_mod('show_footer_additional_info', false)) : ?>
        <!-- Additional Footer Info -->
        <div class="row mt-3 pt-3 border-top">
            <div class="col-12">
                <div class="footer-additional-info text-center small text-muted">
                    
                    <?php
                    $additional_info = get_theme_mod('footer_additional_info', '');
                    if ($additional_info) {
                        echo wp_kses_post($additional_info);
                    }
                    ?>

                    <?php if (get_theme_mod('show_privacy_links', true)) : ?>
                    <div class="privacy-links mt-2">
                        <?php
                        $privacy_page = get_option('wp_page_for_privacy_policy');
                        if ($privacy_page) :
                        ?>
                        <a href="<?php echo esc_url(get_permalink($privacy_page)); ?>" class="privacy-link me-3 text-decoration-none">
                            <?php esc_html_e('Gizlilik Politikası', 'pratikwp'); ?>
                        </a>
                        <?php endif; ?>

                        <?php
                        $terms_page_id = get_theme_mod('terms_page');
                        if ($terms_page_id) :
                        ?>
                        <a href="<?php echo esc_url(get_permalink($terms_page_id)); ?>" class="terms-link me-3 text-decoration-none">
                            <?php esc_html_e('Kullanım Şartları', 'pratikwp'); ?>
                        </a>
                        <?php endif; ?>

                        <?php
                        $cookies_page_id = get_theme_mod('cookies_page');
                        if ($cookies_page_id) :
                        ?>
                        <a href="<?php echo esc_url(get_permalink($cookies_page_id)); ?>" class="cookies-link text-decoration-none">
                            <?php esc_html_e('Çerez Politikası', 'pratikwp'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>