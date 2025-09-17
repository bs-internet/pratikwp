<?php
/**
 * Footer Template 2 - Minimal Style
 * Clean minimal footer with centered layout and social links
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
$show_logo = get_theme_mod('footer_show_logo', true);
$footer_description = get_theme_mod('footer_description', '');
?>

<footer class="site-footer footer-template-2" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
    
    <div class="footer-main-section py-5">
        <div class="container">
            
            <!-- Footer Logo & Description -->
            <?php if ($show_logo || !empty($footer_description)): ?>
            <div class="footer-branding text-center mb-5">
                
                <?php if ($show_logo): ?>
                <div class="footer-logo mb-4">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <h2 class="site-title mb-0">
                            <a href="<?php echo esc_url(home_url('/')); ?>" 
                               class="text-decoration-none"
                               rel="home">
                                <?php bloginfo('name'); ?>
                            </a>
                        </h2>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($footer_description)): ?>
                <div class="footer-description">
                    <p class="lead mb-0 text-muted">
                        <?php echo wp_kses_post($footer_description); ?>
                    </p>
                </div>
                <?php elseif (!$show_logo): ?>
                <div class="footer-description">
                    <p class="lead mb-0 text-muted">
                        <?php echo wp_kses_post(get_bloginfo('description')); ?>
                    </p>
                </div>
                <?php endif; ?>
                
            </div>
            <?php endif; ?>
            
            <!-- Footer Widgets (Centered Layout) -->
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2')): ?>
            <div class="footer-widgets-section mb-5">
                <div class="row justify-content-center">
                    
                    <?php if (is_active_sidebar('footer-1')): ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="footer-widget-wrapper text-center">
                            <?php dynamic_sidebar('footer-1'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (is_active_sidebar('footer-2')): ?>
                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                        <div class="footer-widget-wrapper text-center">
                            <?php dynamic_sidebar('footer-2'); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Social Links -->
            <?php if ($show_social_links): ?>
            <div class="footer-social-section mb-4">
                <?php
                $social_links = [
                    'facebook' => get_theme_mod('social_facebook', ''),
                    'twitter' => get_theme_mod('social_twitter', ''),
                    'instagram' => get_theme_mod('social_instagram', ''),
                    'linkedin' => get_theme_mod('social_linkedin', ''),
                    'youtube' => get_theme_mod('social_youtube', ''),
                    'pinterest' => get_theme_mod('social_pinterest', ''),
                    'tiktok' => get_theme_mod('social_tiktok', ''),
                    'whatsapp' => get_theme_mod('social_whatsapp', ''),
                ];
                
                $social_icons = [
                    'facebook' => 'fab fa-facebook-f',
                    'twitter' => 'fab fa-twitter',
                    'instagram' => 'fab fa-instagram',
                    'linkedin' => 'fab fa-linkedin-in',
                    'youtube' => 'fab fa-youtube',
                    'pinterest' => 'fab fa-pinterest-p',
                    'tiktok' => 'fab fa-tiktok',
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
                <div class="social-links-wrapper text-center">
                    <h6 class="social-title mb-3"><?php esc_html_e('Sosyal Medya', 'pratikwp'); ?></h6>
                    <div class="social-links d-flex justify-content-center align-items-center flex-wrap gap-3">
                        
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
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Footer Navigation Menu -->
            <?php if (has_nav_menu('footer')): ?>
            <div class="footer-navigation-section mb-4">
                <nav class="footer-navigation text-center" role="navigation" aria-label="<?php esc_attr_e('Footer Menüsü', 'pratikwp'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'footer',
                        'menu_class' => 'd-flex justify-content-center align-items-center flex-wrap gap-4 list-unstyled mb-0',
                        'container' => false,
                        'depth' => 1,
                        'link_before' => '<span>',
                        'link_after' => '</span>',
                        'fallback_cb' => false,
                    ]);
                    ?>
                </nav>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
    
    <!-- Footer Bottom Section -->
    <?php if ($show_copyright): ?>
    <div class="footer-bottom-section py-3 border-top">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    
                    <div class="footer-copyright">
                        <?php if (!empty($copyright_text)): ?>
                            <?php echo wp_kses_post($copyright_text); ?>
                        <?php else: ?>
                            <p class="mb-0 small text-muted">
                                &copy; <?php echo esc_html(date('Y')); ?> 
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-decoration-none">
                                    <?php bloginfo('name'); ?>
                                </a>. 
                                <?php esc_html_e('Tüm hakları saklıdır.', 'pratikwp'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Additional Footer Links -->
                    <?php
                    $privacy_page = get_option('wp_page_for_privacy_policy');
                    $terms_page = get_theme_mod('footer_terms_page', '');
                    ?>
                    
                    <?php if ($privacy_page || $terms_page): ?>
                    <div class="footer-legal-links mt-2">
                        <div class="d-flex justify-content-center align-items-center flex-wrap gap-3">
                            
                            <?php if ($privacy_page): ?>
                            <a href="<?php echo esc_url(get_permalink($privacy_page)); ?>" 
                               class="text-decoration-none small text-muted">
                                <?php esc_html_e('Gizlilik Politikası', 'pratikwp'); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($terms_page): ?>
                            <a href="<?php echo esc_url(get_permalink($terms_page)); ?>" 
                               class="text-decoration-none small text-muted">
                                <?php esc_html_e('Kullanım Şartları', 'pratikwp'); ?>
                            </a>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </div>
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
        <?php if (!empty($footer_description)): ?>
        ,"description": "<?php echo esc_js(wp_strip_all_tags($footer_description)); ?>"
        <?php endif; ?>
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