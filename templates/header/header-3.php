<?php
/**
 * Header Template 3 - Minimal Modern Layout
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

// Header classes
$header_classes = ['site-header', 'header-layout-3', 'header-minimal'];

if ($header_transparent) {
    $header_classes[] = 'header-transparent';
}

if ($header_sticky) {
    $header_classes[] = 'header-sticky';
}

$header_bg_color = get_theme_mod('header_background_color', '#ffffff');
$header_text_color = get_theme_mod('header_text_color', '#333333');
$header_style = get_theme_mod('header_minimal_style', 'clean');

?>

<header class="<?php echo esc_attr(implode(' ', $header_classes)); ?> <?php echo esc_attr('header-style-' . $header_style); ?>" role="banner">
    
    <!-- Main Header -->
    <div class="main-header">
        <div class="container-fluid">
            <div class="header-inner">
                
                <!-- Logo Section -->
                <div class="logo-section">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo_width = get_theme_mod('logo_width', 140);
                    $logo_height = get_theme_mod('logo_height', 'auto');
                    
                    if ($custom_logo_id):
                        $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                        $logo_alt = get_post_meta($custom_logo_id, '_wp_attachment_image_alt', true);
                        if (empty($logo_alt)) {
                            $logo_alt = get_bloginfo('name');
                        }
                    ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-link" rel="home">
                            <img src="<?php echo esc_url($logo_url); ?>" 
                                 alt="<?php echo esc_attr($logo_alt); ?>" 
                                 class="site-logo"
                                 style="max-width: <?php echo esc_attr($logo_width); ?>px; height: <?php echo esc_attr($logo_height === 'auto' ? 'auto' : $logo_height . 'px'); ?>;" />
                        </a>
                    <?php else: ?>
                        <div class="text-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-title-link">
                                <?php bloginfo('name'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Navigation Section -->
                <nav class="main-navigation minimal-nav" role="navigation" aria-label="<?php esc_attr_e('Ana Menü', 'pratikwp'); ?>">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_class'     => 'minimal-menu',
                            'container'      => false,
                            'depth'          => 2,
                            'walker'         => new PratikWp_Walker_Nav_Menu(),
                        ]);
                    } else {
                        // Fallback menu
                        if (current_user_can('manage_options')):
                        ?>
                            <ul class="minimal-menu fallback-menu">
                                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Ana Sayfa', 'pratikwp'); ?></a></li>
                                <li><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('+ Menü Ekle', 'pratikwp'); ?></a></li>
                            </ul>
                        <?php endif; ?>
                    <?php } ?>
                </nav>

                <!-- Actions Section -->
                <div class="header-actions minimal-actions">
                    
                    <?php if ($header_search): ?>
                        <!-- Minimal Search -->
                        <div class="minimal-search">
                            <button type="button" class="search-trigger" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </button>
                            
                            <!-- Search Dropdown -->
                            <div class="search-dropdown">
                                <form role="search" method="get" class="minimal-search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                    <input type="search" 
                                           class="search-input" 
                                           placeholder="<?php esc_attr_e('Aramak istediğinizi yazın...', 'pratikwp'); ?>" 
                                           value="<?php echo get_search_query(); ?>" 
                                           name="s" />
                                    <button type="submit" class="search-btn">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.35-4.35"></path>
                                        </svg>
                                    </button>
                                </form>
                                
                                <?php if (get_theme_mod('header_show_popular_searches', false)): ?>
                                    <div class="popular-searches">
                                        <span class="popular-label"><?php esc_html_e('Popüler:', 'pratikwp'); ?></span>
                                        <?php
                                        $popular_searches = get_theme_mod('header_popular_searches', 'hizmetler, ürünler, iletişim');
                                        $searches = array_map('trim', explode(',', $popular_searches));
                                        foreach ($searches as $search):
                                            if (!empty($search)):
                                        ?>
                                            <a href="<?php echo esc_url(home_url('?s=' . urlencode($search))); ?>" class="popular-link">
                                                <?php echo esc_html($search); ?>
                                            </a>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('header_show_cart', false) && class_exists('WooCommerce')): ?>
                        <!-- Mini Cart -->
                        <div class="mini-cart">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-trigger">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                    <line x1="3" y1="6" x2="21" y2="6"></line>
                                    <path d="m16 10a4 4 0 0 1-8 0"></path>
                                </svg>
                                <?php if (WC()->cart->get_cart_contents_count() > 0): ?>
                                    <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($header_button): ?>
                        <!-- Minimal CTA -->
                        <?php
                        $button_text = get_theme_mod('header_button_text', __('İletişim', 'pratikwp'));
                        $button_url = get_theme_mod('header_button_url', '#');
                        $button_target = get_theme_mod('header_button_new_tab', false) ? '_blank' : '_self';
                        $button_style = get_theme_mod('header_button_style', 'minimal');
                        ?>
                        <div class="minimal-cta">
                            <a href="<?php echo esc_url($button_url); ?>" 
                               target="<?php echo esc_attr($button_target); ?>"
                               class="cta-button cta-<?php echo esc_attr($button_style); ?>">
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Trigger -->
                    <div class="mobile-trigger-wrapper">
                        <button type="button" class="mobile-menu-trigger" aria-label="<?php esc_attr_e('Mobil Menü', 'pratikwp'); ?>">
                            <span class="trigger-line"></span>
                            <span class="trigger-line"></span>
                            <span class="trigger-line"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-drawer">
        <div class="drawer-backdrop"></div>
        <div class="drawer-content">
            <div class="drawer-header">
                <div class="drawer-logo">
                    <?php if ($custom_logo_id): ?>
                        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" />
                    <?php else: ?>
                        <?php bloginfo('name'); ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="drawer-close">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <nav class="drawer-navigation">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_class'     => 'drawer-menu',
                        'container'      => false,
                        'depth'          => 3,
                        'walker'         => new PratikWp_Walker_Nav_Menu(),
                    ]);
                }
                ?>
            </nav>
            
            <!-- Mobile Search -->
            <?php if ($header_search): ?>
                <div class="drawer-search">
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-group">
                            <input type="search" 
                                   name="s" 
                                   placeholder="<?php esc_attr_e('Arama...', 'pratikwp'); ?>" 
                                   value="<?php echo get_search_query(); ?>" />
                            <button type="submit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <path d="m21 21-4.35-4.35"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
            <!-- Mobile Contact -->
            <?php
            $phone = get_theme_mod('company_phone', '');
            $email = get_theme_mod('company_email', '');
            
            if ($phone || $email):
            ?>
                <div class="drawer-contact">
                    <h4><?php esc_html_e('İletişim', 'pratikwp'); ?></h4>
                    <?php if ($phone): ?>
                        <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>" class="contact-link">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <?php echo esc_html($phone); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($email): ?>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-link">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <?php echo esc_html($email); ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Mobile Social Links -->
            <?php if ($header_social): ?>
                <div class="drawer-social">
                    <h4><?php esc_html_e('Takip Edin', 'pratikwp'); ?></h4>
                    <?php pratikwp_social_links(['style' => 'minimal', 'size' => 'small']); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<style>
/* Header Layout 3 - Minimal Modern Styles */
.header-layout-3 {
    background-color: <?php echo esc_attr($header_bg_color); ?>;
    color: <?php echo esc_attr($header_text_color); ?>;
    position: relative;
    z-index: 1000;
    transition: all 0.3s ease;
}

.header-layout-3.header-transparent {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background-color: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    z-index: 1001;
}

.header-layout-3.header-sticky {
    position: sticky;
    top: 0;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
}

/* Main Header */
.header-layout-3 .main-header {
    padding: 15px 0;
    transition: padding 0.3s ease;
}

.header-layout-3.header-sticky .main-header {
    padding: 10px 0;
}

.header-layout-3 .header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
}

/* Logo Section */
.header-layout-3 .logo-section .logo-link,
.header-layout-3 .logo-section .site-title-link {
    display: inline-block;
    text-decoration: none;
    color: inherit;
    font-weight: 700;
    font-size: 28px;
    letter-spacing: -0.5px;
}

.header-layout-3 .site-logo {
    transition: all 0.3s ease;
}

.header-layout-3 .logo-link:hover .site-logo {
    transform: scale(1.05);
}

/* Minimal Navigation */
.header-layout-3 .minimal-nav {
    flex-grow: 1;
    display: flex;
    justify-content: center;
}

.header-layout-3 .minimal-menu {
    display: flex;
    align-items: center;
    gap: 40px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.header-layout-3 .minimal-menu > li > a {
    font-size: 15px;
    font-weight: 500;
    color: inherit;
    text-decoration: none;
    padding: 8px 0;
    position: relative;
    transition: all 0.3s ease;
}

.header-layout-3 .minimal-menu > li > a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background-color: var(--primary-color, #007cba);
    transition: width 0.3s ease;
}

.header-layout-3 .minimal-menu > li > a:hover::after,
.header-layout-3 .minimal-menu > li.current-menu-item > a::after {
    width: 100%;
}

/* Minimal Actions */
.header-layout-3 .minimal-actions {
    display: flex;
    align-items: center;
    gap: 25px;
}

/* Minimal Search */
.header-layout-3 .minimal-search {
    position: relative;
}

.header-layout-3 .search-trigger {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    color: inherit;
    border-radius: 50%;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.header-layout-3 .search-trigger:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.header-layout-3 .search-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 320px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-top: 10px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
}

.header-layout-3 .search-dropdown.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.header-layout-3 .minimal-search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.header-layout-3 .search-input {
    flex: 1;
    padding: 12px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    background-color: #f8f9fa;
    transition: all 0.3s ease;
}

.header-layout-3 .search-input:focus {
    outline: none;
    border-color: var(--primary-color, #007cba);
    background-color: white;
}

.header-layout-3 .search-btn {
    background: var(--primary-color, #007cba);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-layout-3 .search-btn:hover {
    background: var(--primary-color-dark, #005a87);
}

.header-layout-3 .popular-searches {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    align-items: center;
}

.header-layout-3 .popular-label {
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.header-layout-3 .popular-link {
    font-size: 12px;
    color: #666;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 12px;
    background-color: #f0f0f0;
    transition: all 0.3s ease;
}

.header-layout-3 .popular-link:hover {
    background-color: var(--primary-color, #007cba);
    color: white;
}

/* Mini Cart */
.header-layout-3 .cart-trigger {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px;
    color: inherit;
    text-decoration: none;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.header-layout-3 .cart-trigger:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.header-layout-3 .cart-count {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--primary-color, #007cba);
    color: white;
    font-size: 10px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

/* Minimal CTA */
.header-layout-3 .cta-button {
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    font-size: 14px;
    transition: all 0.3s ease;
    display: inline-block;
}

.header-layout-3 .cta-minimal {
    background: transparent;
    color: var(--primary-color, #007cba);
    border: 1px solid var(--primary-color, #007cba);
}

.header-layout-3 .cta-minimal:hover {
    background: var(--primary-color, #007cba);
    color: white;
}

.header-layout-3 .cta-solid {
    background: var(--primary-color, #007cba);
    color: white;
    border: 1px solid var(--primary-color, #007cba);
}

.header-layout-3 .cta-solid:hover {
    background: var(--primary-color-dark, #005a87);
    border-color: var(--primary-color-dark, #005a87);
}

/* Mobile Trigger */
.header-layout-3 .mobile-trigger-wrapper {
    display: none;
}

.header-layout-3 .mobile-menu-trigger {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 3px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.header-layout-3 .mobile-menu-trigger:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.header-layout-3 .trigger-line {
    width: 22px;
    height: 2px;
    background-color: currentColor;
    transition: all 0.3s ease;
}

/* Mobile Drawer */
.header-layout-3 .mobile-drawer {
    position: fixed;
    top: 0;
    right: -100%;
    width: 350px;
    height: 100vh;
    background: white;
    z-index: 10000;
    transition: right 0.4s ease;
    overflow-y: auto;
}

.header-layout-3 .mobile-drawer.active {
    right: 0;
}

.header-layout-3 .drawer-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.header-layout-3 .mobile-drawer.active .drawer-backdrop {
    opacity: 1;
    visibility: visible;
}

.header-layout-3 .drawer-content {
    padding: 30px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.header-layout-3 .drawer-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.header-layout-3 .drawer-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.header-layout-3 .drawer-close:hover {
    background-color: rgba(0, 0, 0, 0.05);
}

.header-layout-3 .drawer-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.header-layout-3 .drawer-menu li {
    margin-bottom: 15px;
}

.header-layout-3 .drawer-menu a {
    display: block;
    padding: 12px 0;
    color: #333;
    text-decoration: none;
    font-weight: 500;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.header-layout-3 .drawer-menu a:hover {
    color: var(--primary-color, #007cba);
    padding-left: 10px;
}

/* Drawer Sections */
.header-layout-3 .drawer-search,
.header-layout-3 .drawer-contact,
.header-layout-3 .drawer-social {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.header-layout-3 .drawer-search .search-group {
    display: flex;
    gap: 10px;
}

.header-layout-3 .drawer-search input {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.header-layout-3 .drawer-search button {
    background: var(--primary-color, #007cba);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
}

.header-layout-3 .drawer-contact h4,
.header-layout-3 .drawer-social h4 {
    margin: 0 0 15px 0;
    font-size: 16px;
    color: #333;
}

.header-layout-3 .contact-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    color: #666;
    text-decoration: none;
    transition: color 0.3s ease;
}

.header-layout-3 .contact-link:hover {
    color: var(--primary-color, #007cba);
}

/* Responsive Styles */
@media (max-width: 1024px) {
    .header-layout-3 .minimal-nav {
        display: none;
    }
    
    .header-layout-3 .mobile-trigger-wrapper {
        display: block;
    }
    
    .header-layout-3 .minimal-actions {
        gap: 15px;
    }
}

@media (max-width: 768px) {
    .header-layout-3 .header-inner {
        padding: 0 20px;
    }
    
    .header-layout-3 .mobile-drawer {
        width: 300px;
    }
    
    .header-layout-3 .search-dropdown {
        width: 280px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile drawer functionality
    const mobileToggle = document.querySelector('.header-layout-3 .mobile-menu-trigger');
    const mobileDrawer = document.querySelector('.header-layout-3 .mobile-drawer');
    const drawerClose = document.querySelector('.header-layout-3 .drawer-close');
    const drawerBackdrop = document.querySelector('.header-layout-3 .drawer-backdrop');

    function toggleMobileDrawer() {
        mobileDrawer.classList.toggle('active');
        document.body.classList.toggle('drawer-open');
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleMobileDrawer);
    }

    if (drawerClose) {
        drawerClose.addEventListener('click', toggleMobileDrawer);
    }

    if (drawerBackdrop) {
        drawerBackdrop.addEventListener('click', toggleMobileDrawer);
    }

    // Search dropdown functionality
    const searchTrigger = document.querySelector('.header-layout-3 .search-trigger');
    const searchDropdown = document.querySelector('.header-layout-3 .search-dropdown');

    function toggleSearchDropdown() {
        searchDropdown.classList.toggle('active');
        
        if (searchDropdown.classList.contains('active')) {
            setTimeout(() => {
                const searchInput = searchDropdown.querySelector('.search-input');
                if (searchInput) {
                    searchInput.focus();
                }
            }, 200);
        }
    }

    if (searchTrigger) {
        searchTrigger.addEventListener('click', toggleSearchDropdown);
    }

    // Close search dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (searchDropdown && !searchDropdown.contains(e.target) && !searchTrigger.contains(e.target)) {
            searchDropdown.classList.remove('active');
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('.header-layout-3 a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Close mobile drawer if open
                if (mobileDrawer && mobileDrawer.classList.contains('active')) {
                    toggleMobileDrawer();
                }
            }
        });
    });

    // Header scroll effect
    let lastScrollTop = 0;
    const header = document.querySelector('.header-layout-3');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Hide/show header on scroll (optional)
        if (scrollTop > lastScrollTop && scrollTop > 200) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Close search dropdown on Escape
        if (e.key === 'Escape') {
            if (searchDropdown && searchDropdown.classList.contains('active')) {
                searchDropdown.classList.remove('active');
            }
            
            if (mobileDrawer && mobileDrawer.classList.contains('active')) {
                toggleMobileDrawer();
            }
        }
        
        // Open search with Ctrl+K or Cmd+K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (searchTrigger) {
                toggleSearchDropdown();
            }
        }
    });

    // Add loading animation to forms
    document.querySelectorAll('.header-layout-3 form').forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.style.opacity = '0.7';
                submitBtn.style.pointerEvents = 'none';
                
                setTimeout(() => {
                    submitBtn.style.opacity = '';
                    submitBtn.style.pointerEvents = '';
                }, 3000);
            }
        });
    });
});

// Add CSS for scrolled state
const scrolledCSS = `
.header-layout-3.scrolled {
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.header-layout-3.scrolled .main-header {
    padding: 8px 0;
}

.header-layout-3 {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

body.drawer-open {
    overflow: hidden;
}

@media (max-width: 480px) {
    .header-layout-3 .mobile-drawer {
        width: 100%;
        right: -100%;
    }
    
    .header-layout-3 .drawer-content {
        padding: 20px;
    }
    
    .header-layout-3 .header-inner {
        padding: 0 15px;
    }
}

/* Focus styles for accessibility */
.header-layout-3 button:focus,
.header-layout-3 a:focus,
.header-layout-3 input:focus {
    outline: 2px solid var(--primary-color, #007cba);
    outline-offset: 2px;
}

/* Smooth animations */
@media (prefers-reduced-motion: no-preference) {
    .header-layout-3 * {
        transition-duration: 0.3s;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .header-layout-3 {
        border-bottom: 1px solid;
    }
    
    .header-layout-3 .minimal-menu > li > a::after {
        height: 3px;
    }
}
`;

// Inject the CSS
const styleSheet = document.createElement('style');
styleSheet.textContent = scrolledCSS;
document.head.appendChild(styleSheet);
</script>