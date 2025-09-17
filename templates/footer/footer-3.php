<?php
/**
 * Footer Template 3 - Business Style
 * Professional business footer with company info and contact details
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
$show_contact_info = get_theme_mod('footer_show_contact_info', true);
$show_newsletter = get_theme_mod('footer_show_newsletter', true);

// Contact information
$company_address = get_theme_mod('company_address', '');
$company_phone = get_theme_mod('company_phone', '');
$company_email = get_theme_mod('company_email', '');
$company_hours = get_theme_mod('company_hours', '');
?>

<footer class="site-footer footer-template-3" role="contentinfo" itemscope itemtype="https://schema.org/WPFooter">
    
    <!-- Main Footer Section -->
    <div class="footer-main-section py-5">
        <div class="container">
            <div class="row">
                
                <!-- Company Info Column -->
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="footer-company-info">
                        
                        <!-- Company Logo/Name -->
                        <div class="company-branding mb-4">
                            <?php if (has_custom_logo()): ?>
                                <div class="footer-logo">
                                    <?php the_custom_logo(); ?>
                                </div>
                            <?php else: ?>
                                <h3 class="company-name mb-2">
                                    <a href="<?php echo esc_url(home_url('/')); ?>" 
                                       class="text-decoration-none"
                                       rel="home">
                                        <?php bloginfo('name'); ?>
                                    </a>
                                </h3>
                            <?php endif; ?>
                            
                            <p class="company-tagline text-muted mb-3">
                                <?php echo wp_kses_post(get_bloginfo('description')); ?>
                            </p>
                        </div>
                        
                        <!-- Contact Information -->
                        <?php if ($show_contact_info && ($company_address || $company_phone || $company_email)): ?>
                        <div class="contact-info">
                            <h6 class="contact-title mb-3"><?php esc_html_e('İletişim Bilgileri', 'pratikwp'); ?></h6>
                            
                            <?php if (!empty($company_address)): ?>
                            <div class="contact-item mb-2" itemscope itemtype="https://schema.org/PostalAddress">
                                <i class="fas fa-map-marker-alt me-2 text-primary" aria-hidden="true"></i>
                                <span class="contact-text" itemprop="streetAddress">
                                    <?php echo wp_kses_post($company_address); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($company_phone)): ?>
                            <div class="contact-item mb-2">
                                <i class="fas fa-phone me-2 text-primary" aria-hidden="true"></i>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^+\d]/', '', $company_phone)); ?>" 
                                   class="contact-text text-decoration-none"
                                   itemprop="telephone">
                                    <?php echo esc_html($company_phone); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($company_email)): ?>
                            <div class="contact-item mb-2">
                                <i class="fas fa-envelope me-2 text-primary" aria-hidden="true"></i>
                                <a href="mailto:<?php echo esc_attr($company_email); ?>" 
                                   class="contact-text text-decoration-none"
                                   itemprop="email">
                                    <?php echo esc_html($company_email); ?>
                                </a>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($company_hours)): ?>
                            <div class="contact-item mb-2">
                                <i class="fas fa-clock me-2 text-primary" aria-hidden="true"></i>
                                <span class="contact-text" itemprop="openingHours">
                                    <?php echo wp_kses_post($company_hours); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                    </div>
                </div>
                
                <!-- Footer Widget 1 -->
                <?php if (is_active_sidebar('footer-1')): ?>
                <div class="col-lg-2 col-md-6 col-12 mb-4">
                    <div class="footer-widget-wrapper">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Footer Widget 2 -->
                <?php if (is_active_sidebar('footer-2')): ?>
                <div class="col-lg-2 col-md-6 col-12 mb-4">
                    <div class="footer-widget-wrapper">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Newsletter & Social -->
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    
                    <!-- Newsletter Signup -->
                    <?php if ($show_newsletter): ?>
                    <div class="newsletter-section mb-4">
                        <h6 class="newsletter-title mb-3"><?php esc_html_e('Bülten Aboneliği', 'pratikwp'); ?></h6>
                        <p class="newsletter-description text-muted mb-3">
                            <?php esc_html_e('En son haberler ve güncellemeler için bültenimize abone olun.', 'pratikwp'); ?>
                        </p>
                        
                        <form class="newsletter-form" method="post" action="#" aria-label="<?php esc_attr_e('Bülten abonelik formu', 'pratikwp'); ?>">
                            <div class="input-group mb-3">
                                <input type="email" 
                                       class="form-control" 
                                       placeholder="<?php esc_attr_e('E-posta adresiniz', 'pratikwp'); ?>"
                                       aria-label="<?php esc_attr_e('E-posta adresi', 'pratikwp'); ?>"
                                       required>
                                <button class="btn btn-primary" 
                                        type="submit"
                                        aria-label="<?php esc_attr_e('Bültene abone ol', 'pratikwp'); ?>">
                                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                                </button>
                            </div>
                        </form>
                        
                        <small class="newsletter-disclaimer text-muted">
                            <?php esc_html_e('E-posta adresinizi kimseyle paylaşmayız.', 'pratikwp'); ?>
                        </small>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Social Media Links -->
                    <?php if ($show_social_links): ?>
                    <div class="social-media-section">
                        <h6 class="social-title mb-3"><?php esc_html_e('Bizi Takip Edin', 'pratikwp'); ?></h6>
                        
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
                        
                        $social_labels = [
                            'facebook' => 'Facebook',
                            'twitter' => 'Twitter',
                            'instagram' => 'Instagram',
                            'linkedin' => 'LinkedIn',
                            'youtube' => 'YouTube',
                            'whatsapp' => 'WhatsApp',
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
                        <div class="social-links d-flex align-items-center flex-wrap gap-3">
                            
                            <?php foreach ($social_links as $platform => $url): ?>
                                <?php if (!empty($url)): ?>
                                <a href="<?php echo esc_url($url); ?>" 
                                   class="social-link social-<?php echo esc_attr($platform); ?> d-flex align-items-center text-decoration-none" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   aria-label="<?php printf(esc_attr__('%s sayfamızı ziyaret edin', 'pratikwp'), $social_labels[$platform]); ?>"
                                   title="<?php printf(esc_attr__('%s sayfamızı ziyaret edin', 'pratikwp'), $social_labels[$platform]); ?>">
                                    <i class="<?php echo esc_attr($social_icons[$platform]); ?> me-2" aria-hidden="true"></i>
                                    <span class="social-label"><?php echo esc_html($social_labels[$platform]); ?></span>
                                </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom Section -->
    <div class="footer-bottom-section py-3 border-top">
        <div class="container">
            <div class="row align-items-center">
                
                <!-- Copyright -->
                <div class="col-lg-6 col-md-12 text-center text-lg-start mb-3 mb-lg-0">
                    <?php if ($show_copyright): ?>
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
                    <?php endif; ?>
                </div>
                
                <!-- Footer Navigation & Legal Links -->
                <div class="col-lg-6 col-md-12 text-center text-lg-end">
                    
                    <!-- Footer Navigation Menu -->
                    <?php if (has_nav_menu('footer')): ?>
                    <nav class="footer-navigation d-inline-block me-3" role="navigation" aria-label="<?php esc_attr_e('Footer Menüsü', 'pratikwp'); ?>">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'footer',
                            'menu_class' => 'd-flex justify-content-center justify-content-lg-end align-items-center flex-wrap gap-3 list-unstyled mb-0',
                            'container' => false,
                            'depth' => 1,
                            'link_before' => '<span class="small">',
                            'link_after' => '</span>',
                            'fallback_cb' => false,
                        ]);
                        ?>
                    </nav>
                    <?php endif; ?>
                    
                    <!-- Legal Links -->
                    <?php
                    $privacy_page = get_option('wp_page_for_privacy_policy');
                    $terms_page = get_theme_mod('footer_terms_page', '');
                    ?>
                    
                    <?php if ($privacy_page || $terms_page): ?>
                    <div class="footer-legal-links d-inline-block">
                        <div class="d-flex justify-content-center justify-content-lg-end align-items-center flex-wrap gap-3">
                            
                            <?php if ($privacy_page): ?>
                            <a href="<?php echo esc_url(get_permalink($privacy_page)); ?>" 
                               class="text-decoration-none small text-muted">
                                <?php esc_html_e('Gizlilik', 'pratikwp'); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($terms_page): ?>
                            <a href="<?php echo esc_url(get_permalink($terms_page)); ?>" 
                               class="text-decoration-none small text-muted">
                                <?php esc_html_e('Şartlar', 'pratikwp'); ?>
                            </a>
                            <?php endif; ?>
                            
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
                
            </div>
        </div>
    </div>
    
</footer>

<!-- Structured Data for Organization -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
    "url": "<?php echo esc_url(home_url('/')); ?>",
    "logo": "<?php echo esc_url(wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0] ?? ''); ?>"
    <?php if (!empty(get_bloginfo('description'))): ?>
    ,"description": "<?php echo esc_js(get_bloginfo('description')); ?>"
    <?php endif; ?>
    <?php if (!empty($company_address)): ?>
    ,"address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo esc_js(wp_strip_all_tags($company_address)); ?>"
    }
    <?php endif; ?>
    <?php if (!empty($company_phone)): ?>
    ,"telephone": "<?php echo esc_js($company_phone); ?>"
    <?php endif; ?>
    <?php if (!empty($company_email)): ?>
    ,"email": "<?php echo esc_js($company_email); ?>"
    <?php endif; ?>
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