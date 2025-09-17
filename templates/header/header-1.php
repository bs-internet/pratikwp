<?php
/**
 * Header Template 1 - Classic Layout
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get header settings
$header_transparent = PratikWp_Page_Settings::is_header_transparent();
$header_sticky = get_theme_mod('header_sticky', true);
$header_search = get_theme_mod('header_show_search', true);
$header_social = get_theme_mod('header_show_social', false);
$header_button = get_theme_mod('header_show_button', false);
$header_layout = get_theme_mod('header_layout', 'layout-1');

// Header classes
$header_classes = ['site-header', 'header-layout-1'];

if ($header_transparent) {
    $header_classes[] = 'header-transparent';
}

if ($header_sticky) {
    $header_classes[] = 'header-sticky';
}

$header_bg_color = get_theme_mod('header_background_color', '#ffffff');
$header_text_color = get_theme_mod('header_text_color', '#333333');

?>

<header class="<?php echo esc_attr(implode(' ', $header_classes)); ?>" role="banner">
    
    <?php if (get_theme_mod('header_show_top_bar', false)): ?>
        <!-- Top Bar -->
        <div class="header-top-bar">
            <div class="container">
                <div class="top-bar-content">
                    <div class="top-bar-left">
                        <?php
                        // Top bar info
                        $top_bar_text = get_theme_mod('header_top_bar_text', '');
                        if ($top_bar_text):
                        ?>
                            <div class="top-bar-text">
                                <?php echo wp_kses_post($top_bar_text); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Contact info
                        $phone = get_theme_mod('company_phone', '');
                        $email = get_theme_mod('company_email', '');
                        
                        if ($phone || $email):
                        ?>
                            <div class="contact-info">
                                <?php if ($phone): ?>
                                    <span class="contact-item phone">
                                        <i class="fas fa-phone"></i>
                                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>">
                                            <?php echo esc_html($phone); ?>
                                        </a>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($email): ?>
                                    <span class="contact-item email">
                                        <i class="fas fa-envelope"></i>
                                        <a href="mailto:<?php echo esc_attr($email); ?>">
                                            <?php echo esc_html($email); ?>
                                        </a>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="top-bar-right">
                        <?php if ($header_social): ?>
                            <div class="header-social-links">
                                <?php pratikwp_social_links(); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Language switcher
                        if (get_theme_mod('header_show_language', false) && function_exists('pll_the_languages')):
                        ?>
                            <div class="language-switcher">
                                <?php
                                pll_the_languages([
                                    'show_flags' => 1,
                                    'show_names' => 0,
                                    'hide_if_empty' => 0,
                                ]);
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Main Header -->
    <div class="main-header">
        <div class="container">
            <div class="header-content">
                
                <!-- Logo -->
                <div class="site-branding">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo_width = get_theme_mod('logo_width', 150);
                    $logo_height = get_theme_mod('logo_height', 'auto');
                    
                    if ($custom_logo_id):
                        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                        if (empty($logo_alt)) {
                            $logo_alt = get_bloginfo('name');
                        }
                    ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                            <img src="<?php echo esc_url($logo_url); ?>" 
                                 alt="<?php echo esc_attr($logo_alt); ?>" 
                                 class="custom-logo"
                                 style="max-width: <?php echo esc_attr($logo_width); ?>px; height: <?php echo esc_attr($logo_height === 'auto' ? 'auto' : $logo_height . 'px'); ?>;" />
                        </a>
                    <?php else: ?>
                        <div class="site-title-description">
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                    <?php bloginfo('name'); ?>
                                </a>
                            </h1>
                            <?php
                            $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()):
                            ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Navigation -->
                <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Ana Menü', 'pratikwp'); ?>">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_class'     => 'primary-menu',
                            'container'      => false,
                            'depth'          => 3,
                            'walker'         => new PratikWp_Walker_Nav_Menu(),
                        ]);
                    } else {
                        // Fallback menu for admin users
                        if (current_user_can('manage_options')):
                        ?>
                            <ul class="primary-menu fallback-menu">
                                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Ana Sayfa', 'pratikwp'); ?></a></li>
                                <li><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Menü Oluştur', 'pratikwp'); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    <?php } ?>
                </nav>

                <!-- Header Actions -->
                <div class="header-actions">
                    
                    <?php if ($header_search): ?>
                        <!-- Search Toggle -->
                        <div class="header-search">
                            <button type="button" class="search-toggle" aria-label="<?php esc_attr_e('Arama Aç', 'pratikwp'); ?>">
                                <i class="fas fa-search"></i>
                            </button>
                            
                            <!-- Search Form -->
                            <div class="search-form-wrapper">
                                <div class="search-form-inner">
                                    <?php get_search_form(); ?>
                                    <button type="button" class="search-close" aria-label="<?php esc_attr_e('Arama Kapat', 'pratikwp'); ?>">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($header_button): ?>
                        <!-- Header Button -->
                        <?php
                        $button_text = get_theme_mod('header_button_text', __('İletişim', 'pratikwp'));
                        $button_url = get_theme_mod('header_button_url', '#');
                        $button_target = get_theme_mod('header_button_new_tab', false) ? '_blank' : '_self';
                        ?>
                        <div class="header-button">
                            <a href="<?php echo esc_url($button_url); ?>" 
                               target="<?php echo esc_attr($button_target); ?>"
                               class="btn btn-primary header-cta-button">
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Toggle -->
                    <div class="mobile-menu-toggle">
                        <button type="button" class="mobile-toggle" aria-label="<?php esc_attr_e('Mobil Menü', 'pratikwp'); ?>">
                            <span class="hamburger">
                                <span class="hamburger-line"></span>
                                <span class="hamburger-line"></span>
                                <span class="hamburger-line"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-navigation">
        <div class="mobile-menu-wrapper">
            <div class="mobile-menu-header">
                <div class="mobile-logo">
                    <?php if ($custom_logo_id): ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" />
                        </a>
                    <?php else: ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <?php bloginfo('name'); ?>
                        </a>
                    <?php endif; ?>
                </div>
                <button type="button" class="mobile-menu-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <nav class="mobile-menu-nav">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_class'     => 'mobile-menu',
                        'container'      => false,
                        'depth'          => 3,
                        'walker'         => new PratikWp_Walker_Nav_Menu(),
                    ]);
                }
                ?>
            </nav>
            
            <?php if ($header_social): ?>
                <div class="mobile-social-links">
                    <?php pratikwp_social_links(); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Mobile contact info
            if ($phone || $email):
            ?>
                <div class="mobile-contact-info">
                    <?php if ($phone): ?>
                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>" class="mobile-contact-item">
                            <i class="fas fa-phone"></i>
                            <?php echo esc_html($phone); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($email): ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="mobile-contact-item">
                            <i class="fas fa-envelope"></i>
                            <?php echo esc_html($email); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Mobile Menu Overlay -->
        <div class="mobile-menu-overlay"></div>
    </div>
</header>

<style>
/* Header Layout 1 Styles */
.header-layout-1 {
    background-color: <?php echo esc_attr($header_bg_color); ?>;
    color: <?php echo esc_attr($header_text_color); ?>;
    position: relative;
    z-index: 1000;
}

.header-layout-1.header-transparent {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background-color: transparent;
    z-index: 1001;
}

.header-layout-1.header-sticky {
    position: sticky;
    top: 0;
}

.header-layout-1 .header-top-bar {
    background-color: rgba(0, 0, 0, 0.05);
    padding: 8px 0;
    font-size: 14px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.header-layout-1 .top-bar-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-layout-1 .contact-info {
    display: flex;
    gap: 20px;
}

.header-layout-1 .contact-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.header-layout-1 .contact-item i {
    font-size: 12px;
    opacity: 0.8;
}

.header-layout-1 .main-header {
    padding: 15px 0;
}

.header-layout-1 .header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
}

.header-layout-1 .site-branding {
    flex-shrink: 0;
}

.header-layout-1 .main-navigation {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.header-layout-1 .primary-menu {
    display: flex;
    align-items: center;
    gap: 30px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.header-layout-1 .header-actions {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-shrink: 0;
}

.header-layout-1 .search-toggle {
    background: none;
    border: none;
    padding: 10px;
    cursor: pointer;
    color: inherit;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.header-layout-1 .search-toggle:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.header-layout-1 .header-cta-button {
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

/* Mobile Styles */
@media (max-width: 1024px) {
    .header-layout-1 .main-navigation {
        display: none;
    }
    
    .header-layout-1 .header-top-bar {
        display: none;
    }
    
    .header-layout-1 .mobile-menu-toggle {
        display: block;
    }
    
    .header-layout-1 .mobile-toggle {
        background: none;
        border: none;
        padding: 10px;
        cursor: pointer;
    }
    
    .header-layout-1 .hamburger {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }
    
    .header-layout-1 .hamburger-line {
        width: 22px;
        height: 2px;
        background-color: currentColor;
        transition: all 0.3s ease;
    }
    
    .header-layout-1 .mobile-navigation {
        position: fixed;
        top: 0;
        left: -100%;
        width: 300px;
        height: 100vh;
        background-color: #fff;
        z-index: 10000;
        transition: left 0.3s ease;
    }
    
    .header-layout-1 .mobile-navigation.active {
        left: 0;
    }
    
    .header-layout-1 .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .header-layout-1 .mobile-navigation.active + .mobile-menu-overlay {
        opacity: 1;
        visibility: visible;
    }
}

@media (max-width: 768px) {
    .header-layout-1 .header-content {
        gap: 15px;
    }
    
    .header-layout-1 .header-actions {
        gap: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mobileNavigation = document.querySelector('.mobile-navigation');
    const mobileOverlay = document.querySelector('.mobile-menu-overlay');
    const mobileClose = document.querySelector('.mobile-menu-close');

    function toggleMobileMenu() {
        mobileNavigation.classList.toggle('active');
        document.body.classList.toggle('mobile-menu-open');
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleMobileMenu);
    }

    if (mobileClose) {
        mobileClose.addEventListener('click', toggleMobileMenu);
    }

    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', toggleMobileMenu);
    }

    // Search toggle
    const searchToggle = document.querySelector('.search-toggle');
    const searchWrapper = document.querySelector('.search-form-wrapper');
    const searchClose = document.querySelector('.search-close');

    function toggleSearch() {
        searchWrapper.classList.toggle('active');
        if (searchWrapper.classList.contains('active')) {
            setTimeout(() => {
                const searchInput = searchWrapper.querySelector('input[type="search"]');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 300);
        }
    }

    if (searchToggle) {
        searchToggle.addEventListener('click', toggleSearch);
    }

    if (searchClose) {
        searchClose.addEventListener('click', toggleSearch);
    }

    // Close search on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && searchWrapper && searchWrapper.classList.contains('active')) {
            toggleSearch();
        }
    });
});
</script>