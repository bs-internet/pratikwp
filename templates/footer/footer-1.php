<?php
/**
 * Footer Template 1 - Corporate Style
 * Modern corporate footer with 4 widget columns and social links
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get footer settings from customizer
$show_social_links = get_theme_mod('footer_show_social_links', true);
$show_copyright = get_theme_mod('footer_show_copyright', true);
$copyright_text = get_theme_mod('footer_copyright_text', '');
$footer_columns = get_theme_mod('footer_columns', 4);
?>

<footer class="site-footer footer-template-1" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
    
    <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')): ?>
    
    <!-- Footer Widgets Section -->
    <div class="footer-widgets-section py-5">
        <div class="container">
            <div class="row">
                
                <?php 
                $column_classes = [
                    1 => 'col-12',
                    2 => 'col-lg-6 col-md-6 col-12',
                    3 => 'col-lg-4 col-md-6 col-12',
                    4 => 'col-lg-3 col-md-6 col-12'
                ];
                $column_class = $column_classes[$footer_columns] ?? 'col-lg-3 col-md-6 col-12';
                ?>
                
                <!-- Footer Widget 1 -->
                <?php if (is_active_sidebar('footer-1')): ?>
                <div class="footer-widget-column <?php echo esc_attr($column_class); ?> mb-4">
                    <div class="footer-widget-wrapper">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Footer Widget 2 -->
                <?php if (is_active_sidebar('footer-2') && $footer_columns >= 2): ?>
                <div class="footer-widget-column <?php echo esc_attr($column_class); ?> mb-4">
                    <div class="footer-widget-wrapper">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Footer Widget 3 -->
                <?php if (is_active_sidebar('footer-3') && $footer_columns >= 3): ?>
                <div class="footer-widget-column <?php echo esc_attr($column_class); ?> mb-4">
                    <div class="footer-widget-wrapper">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Footer Widget 4 -->
                <?php if (is_active_sidebar('footer-4') && $footer_columns >= 4): ?>
                <div class="footer-widget-column <?php echo esc_attr($column_class); ?> mb-4">
                    <div class="footer-widget-wrapper">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
    
    <?php endif; ?>
    
    <!-- Footer Bottom Section -->
    <div class="footer-bottom-section py-4 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
        <div class="container">
            <div class="row align-items-center">
                
                <!-- Copyright -->
                <div class="col-lg-6 col-md-12 text-center text-lg-start mb-3 mb-lg-0">
                    <?php if ($show_copyright): ?>
                    <div class="footer-copyright">
                        <?php if (!empty($copyright_text)): ?>
                            <?php echo wp_kses_post($copyright_text); ?>
                        <?php else: ?>
                            <p class="mb-0">
                                &copy; <?php echo esc_html(date('Y')); ?> 
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-decoration-none">
                                    <?php bloginfo('name'); ?>
                                </a>. 
                                <?php esc_html_e('Tüm hakları saklıdır.', 'pratikwp'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Social Links & Footer Menu -->
                <div class="col-lg-6 col-md-12 text-center text-lg-end">
                    
                    <?php if ($show_social_links): ?>
                    <!-- Social Links -->
                    <div class="footer-social-links mb-3 mb-lg-0">
                        <?php
                        $social_links = [
                            'facebook' => get_theme_mod('social_facebook', ''),
                            'twitter' => get_theme_mod('social_twitter', ''),
                            'instagram' => get_theme_mod('social_instagram', ''),
                            'linkedin' => get_theme_mod('social_linkedin', ''),
                            'youtube' => get_theme_mod('social_youtube', ''),
                            'whatsapp' => get_theme_mod('social_whatsapp', ''),
                        ];
                        
                        $social_icons = [
                            'facebook' => 'fab fa-facebook-f',
                            'twitter' => 'fab fa-twitter',
                            'instagram' => 'fab fa-instagram',
                            'linkedin' => 'fab fa-linkedin-in',
                            'youtube' => 'fab fa-youtube',
                            'whatsapp' => 'fab fa-whatsapp',
                        ];
                        
                        $has_social = false;
                        foreach ($social_links as $platform => $url) {
                            if (!empty($url)) {
                                $has_social = true;
                                break;
                            }
                        }
                        ?>
                        
                        <?php if ($has_social): ?>
                        <div class="social-links-wrapper d-flex justify-content-center justify-content-lg-end align-items-center flex-wrap gap-3">
                            <span class="social-label me-2"><?php esc_html_e('Bizi Takip Edin:', 'pratikwp'); ?></span>
                            
                            <?php foreach ($social_links as $platform => $url): ?>
                                <?php if (!empty($url)): ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   class="social-link social-<?php echo esc_attr($platform); ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php printf(esc_attr__('%s sayfamızı ziyaret edin', 'pratikwp'), ucfirst($platform)); ?>"
                                   title="<?php printf(esc_attr__('%s sayfamızı ziyaret edin', 'pratikwp'), ucfirst($platform)); ?>">
                                    <i class="<?php echo esc_attr($social_icons[$platform]); ?>" aria-hidden="true"></i>
                                </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Footer Navigation Menu -->
                    <?php if (has_nav_menu('footer')): ?>
                    <nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e('Footer Menüsü', 'pratikwp'); ?>">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class' => 'd-flex justify-content-center justify-content-lg-end align-items-center flex-wrap gap-3 list-unstyled mb-0',
                            'container' => false,
                            'depth' => 1,
                            'link_before' => '<span>',
                            'link_after' => '</span>',
                            'fallback_cb' => false,
                        ]);
                        ?>
                    </nav>
                    <?php endif; ?>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <?php if (get_theme_mod('footer_show_back_to_top', true)): ?>
    <button class="back-to-top-btn" 
            aria-label="<?php esc_attr_e('Sayfa başına git', 'pratikwp'); ?>"
            title="<?php esc_attr_e('Sayfa başına git', 'pratikwp'); ?>">
        <i class="fas fa-chevron-up" aria-hidden="true"></i>
    </button>
    <?php endif; ?>
    
</footer>

<!-- Structured Data for Footer -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WPFooter",
    "url": "<?php echo esc_url(home_url('/')); ?>",
    "copyrightYear": "<?php echo esc_js(date('Y')); ?>",
    "copyrightHolder": {
        "@type": "Organization",
        "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
        "url": "<?php echo esc_url(home_url('/')); ?>"
    }
    <?php if ($has_social): ?>
    ,"sameAs": [
        <?php 
        $social_urls = [];
        foreach ($social_links as $url) {
            if (!empty($url)) {
                $social_urls[] = '"' . esc_url($url) . '"';
            }
        }
        echo implode(',', $social_urls);
        ?>
    ]
    <?php endif; ?>
}
</script>