<?php
/**
 * Header Template 2 - Centered Logo Layout
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
$header_classes = ['site-header', 'header-layout-2', 'header-centered'];

if ($header_transparent) {
    $header_classes[] = 'header-transparent';
}

if ($header_sticky) {
    $header_classes[] = 'header-sticky';
}

$header_bg_color = get_theme_mod('header_background_color', '#ffffff');
$header_text_color = get_theme_mod('header_text_color', '#333333');
$header_border_color = get_theme_mod('header_border_color', '#e5e5e5');

?>

<header class="<?php echo esc_attr(implode(' ', $header_classes)); ?>" role="banner">
    
    <?php if (get_theme_mod('header_show_announcement', false)): ?>
        <!-- Announcement Bar -->
        <div class="announcement-bar">
            <div class="container">
                <div class="announcement-content">
                    <div class="announcement-text">
                        <?php echo wp_kses_post(get_theme_mod('header_announcement_text', '')); ?>
                    </div>
                    <?php if (get_theme_mod('header_announcement_dismissible', true)): ?>
                        <button type="button" class="announcement-close" aria-label="<?php esc_attr_e('Kapat', 'pratikwp'); ?>">
                            <i class="fas fa-times"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Header Top Section -->
    <div class="header-top-section">
        <div class="container">
            <div class="header-top-content">
                
                <!-- Left Navigation -->
                <nav class="navigation-left" role="navigation">
                    <?php
                    if (has_nav_menu('primary')) {
                        $menu_items = wp_get_nav_menu_items(wp_get_nav_menu_object(get_nav_menu_locations()['primary']));
                        $left_menu_items = array_slice($menu_items, 0, ceil(count($menu_items) / 2));
                        
                        if (!empty($left_menu_items)):
                        ?>
                            <ul class="left-menu">
                                <?php foreach ($left_menu_items as $item): ?>
                                    <li class="menu-item <?php echo $item->current ? 'current-menu-item' : ''; ?>">
                                        <a href="<?php echo esc_url($item->url); ?>" 
                                           <?php echo $item->target ? 'target="' . esc_attr($item->target) . '"' : ''; ?>>
                                            <?php echo esc_html($item->title); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php } ?>
                </nav>

                <!-- Centered Logo -->
                <div class="site-branding centered-logo">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo_width = get_theme_mod('logo_width', 180);
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

                <!-- Right Navigation -->
                <nav class="navigation-right" role="navigation">
                    <?php
                    if (has_nav_menu('primary') && !empty($menu_items)) {
                        $right_menu_items = array_slice($menu_items, ceil(count($menu_items) / 2));
                        
                        if (!empty($right_menu_items)):
                        ?>
                            <ul class="right-menu">
                                <?php foreach ($right_menu_items as $item): ?>
                                    <li class="menu-item <?php echo $item->current ? 'current-menu-item' : ''; ?>">
                                        <a href="<?php echo esc_url($item->url); ?>" 
                                           <?php echo $item->target ? 'target="' . esc_attr($item->target) . '"' : ''; ?>>
                                            <?php echo esc_html($item->title); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    <?php } ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Header Actions Bar -->
    <div class="header-actions-bar">
        <div class="container">
            <div class="actions-content">
                
                <!-- Contact Info -->
                <?php
                $phone = get_theme_mod('company_phone', '');
                $email = get_theme_mod('company_email', '');
                $address = get_theme_mod('company_address', '');
                ?>
                
                <div class="header-contact-info">
                    <?php if ($phone): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="contact-details">
                                <span class="contact-label"><?php esc_html_e('Telefon', 'pratikwp'); ?></span>
                                <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>" class="contact-value">
                                    <?php echo esc_html($phone); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($email): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-details">
                                <span class="contact-label"><?php esc_html_e('E-posta', 'pratikwp'); ?></span>
                                <a href="mailto:<?php echo esc_attr($email); ?>" class="contact-value">
                                    <?php echo esc_html($email); ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($address): ?>
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="contact-details">
                                <span class="contact-label"><?php esc_html_e('Adres', 'pratikwp'); ?></span>
                                <span class="contact-value">
                                    <?php echo esc_html($address); ?>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Header Actions -->
                <div class="header-actions">
                    
                    <?php if ($header_search): ?>
                        <!-- Search -->
                        <div class="header-search">
                            <button type="button" class="search-toggle" aria-label="<?php esc_attr_e('Arama Aç', 'pratikwp'); ?>">
                                <i class="fas fa-search"></i>
                                <span><?php esc_html_e('Ara', 'pratikwp'); ?></span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($header_social): ?>
                        <!-- Social Links -->
                        <div class="header-social-links">
                            <?php pratikwp_social_links(['style' => 'rounded']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($header_button): ?>
                        <!-- Header Button -->
                        <?php
                        $button_text = get_theme_mod('header_button_text', __('Teklif Al', 'pratikwp'));
                        $button_url = get_theme_mod('header_button_url', '#');
                        $button_target = get_theme_mod('header_button_new_tab', false) ? '_blank' : '_self';
                        ?>
                        <div class="header-button">
                            <a href="<?php echo esc_url($button_url); ?>" 
                               target="<?php echo esc_attr($button_target); ?>"
                               class="btn btn-primary header-cta-button">
                                <i class="fas fa-paper-plane"></i>
                                <?php echo esc_html($button_text); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Toggle -->
                    <div class="mobile-menu-toggle">
                        <button type="button" class="mobile-toggle" aria-label="<?php esc_attr_e('Mobil Menü', 'pratikwp'); ?>">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                            <span class="toggle-text"><?php esc_html_e('Menü', 'pratikwp'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full-Width Search -->
    <div class="fullwidth-search">
        <div class="search-overlay"></div>
        <div class="search-container">
            <div class="container">
                <div class="search-form-wrapper">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="search-input-wrapper">
                            <input type="search" 
                                   class="search-field" 
                                   placeholder="<?php esc_attr_e('Ne aramak istiyorsunuz?', 'pratikwp'); ?>" 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s" />
                            <button type="submit" class="search-submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <p class="search-hint"><?php esc_html_e('Enter tuşuna basın veya arama butonuna tıklayın', 'pratikwp'); ?></p>
                    </form>
                    <button type="button" class="search-close">
                        <i class="fas fa-times"></i>
                    </button>
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
            
            <!-- Mobile Contact -->
            <?php if ($phone || $email): ?>
                <div class="mobile-contact">
                    <h4><?php esc_html_e('İletişim', 'pratikwp'); ?></h4>
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
            
            <?php if ($header_social): ?>
                <div class="mobile-social">
                    <h4><?php esc_html_e('Bizi Takip Edin', 'pratikwp'); ?></h4>
                    <?php pratikwp_social_links(['style' => 'rounded', 'size' => 'large']); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="mobile-menu-overlay"></div>
    </div>
</header>

<style>
/* Header Layout 2 Styles */
.header-layout-2 {
    background-color: <?php echo esc_attr($header_bg_color); ?>;
    color: <?php echo esc_attr($header_text_color); ?>;
    position: relative;
    z-index: 1000;
}

.header-layout-2.header-transparent {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background-color: transparent;
    z-index: 1001;
}

.header-layout-2.header-sticky {
    position: sticky;
    top: 0;
}

/* Announcement Bar */
.header-layout-2 .announcement-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 0;
    font-size: 14px;
    text-align: center;
    position: relative;
}

.header-layout-2 .announcement-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
}

.header-layout-2 .announcement-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 5px;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.header-layout-2 .announcement-close:hover {
    opacity: 1;
}

/* Header Top Section */
.header-layout-2 .header-top-section {
    padding: 20px 0;
    border-bottom: 1px solid <?php echo esc_attr($header_border_color); ?>;
}

.header-layout-2 .header-top-content {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 30px;
}

.header-layout-2 .navigation-left,
.header-layout-2 .navigation-right {
    display: flex;
}

.header-layout-2 .navigation-left {
    justify-content: flex-end;
}

.header-layout-2 .navigation-right {
    justify-content: flex-start;
}

.header-layout-2 .left-menu,
.header-layout-2 .right-menu {
    display: flex;
    align-items: center;
    gap: 25px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.header-layout-2 .centered-logo {
    text-align: center;
}

/* Header Actions Bar */
.header-layout-2 .header-actions-bar {
    background-color: rgba(0, 0, 0, 0.02);
    padding: 15px 0;
}

.header-layout-2 .actions-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
}

.header-layout-2 .header-contact-info {
    display: flex;
    gap: 40px;
    flex-grow: 1;
}

.header-layout-2 .contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.header-layout-2 .contact-icon {
    width: 40px;
    height: 40px;
    background-color: var(--primary-color, #007cba);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.header-layout-2 .contact-details {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.header-layout-2 .contact-label {
    font-size: 12px;
    opacity: 0.7;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.header-layout-2 .contact-value {
    font-size: 14px;
    font-weight: 600;
    color: inherit;
    text-decoration: none;
    transition: color 0.3s ease;
}

.header-layout-2 .contact-value:hover {
    color: var(--primary-color, #007cba);
}

.header-layout-2 .header-actions {
    display: flex;
    align-items: center;
    gap: 20px;
}

.header-layout-2 .search-toggle {
    background: none;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    color: inherit;
    border-radius: 25px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    border: 1px solid rgba(0, 0, 0, 0.1);
}

.header-layout-2 .search-toggle:hover {
    background-color: var(--primary-color, #007cba);
    color: white;
    border-color: var(--primary-color, #007cba);
}

.header-layout-2 .header-cta-button {
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.header-layout-2 .header-cta-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Full-width Search */
.header-layout-2 .fullwidth-search {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.header-layout-2 .fullwidth-search.active {
    opacity: 1;
    visibility: visible;
}

.header-layout-2 .search-form-wrapper {
    position: relative;
    max-width: 800px;
    width: 100%;
    padding: 0 20px;
}

.header-layout-2 .search-input-wrapper {
    position: relative;
}

.header-layout-2 .search-field {
    width: 100%;
    padding: 20px 70px 20px 30px;
    font-size: 24px;
    border: none;
    border-radius: 50px;
    background-color: white;
    color: #333;
}

.header-layout-2 .search-submit {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color, #007cba);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    font-size: 18px;
}

.header-layout-2 .search-hint {
    text-align: center;
    color: white;
    margin: 20px 0 0 0;
    opacity: 0.8;
}

.header-layout-2 .search-close {
    position: absolute;
    top: -60px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 30px;
    cursor: pointer;
    padding: 10px;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.header-layout-2 .search-close:hover {
    opacity: 1;
}

/* Mobile Styles */
@media (max-width: 1024px) {
    .header-layout-2 .header-top-content {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 20px;
    }
    
    .header-layout-2 .navigation-left,
    .header-layout-2 .navigation-right {
        display: none;
    }
    
    .header-layout-2 .header-contact-info {
        display: none;
    }
    
    .header-layout-2 .mobile-menu-toggle {
        display: flex;
    }
}

@media (max-width: 768px) {
    .header-layout-2 .header-actions {
        gap: 10px;
    }
    
    .header-layout-2 .search-toggle span,
    .header-layout-2 .toggle-text {
        display: none;
    }
    
    .header-layout-2 .header-actions-bar {
        padding: 10px 0;
    }
    
    .header-layout-2 .actions-content {
        justify-content: center;
        gap: 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu functionality
    const mobileToggle = document.querySelector('.header-layout-2 .mobile-toggle');
    const mobileNavigation = document.querySelector('.header-layout-2 .mobile-navigation');
    const mobileClose = document.querySelector('.header-layout-2 .mobile-menu-close');
    const mobileOverlay = document.querySelector('.header-layout-2 .mobile-menu-overlay');

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

    // Full-width search functionality
    const searchToggle = document.querySelector('.header-layout-2 .search-toggle');
    const fullwidthSearch = document.querySelector('.header-layout-2 .fullwidth-search');
    const searchClose = document.querySelector('.header-layout-2 .search-close');

    function toggleFullwidthSearch() {
        fullwidthSearch.classList.toggle('active');
        if (fullwidthSearch.classList.contains('active')) {
            setTimeout(() => {
                const searchField = fullwidthSearch.querySelector('.search-field');
                if (searchField) {
                    searchField.focus();
                }
            }, 300);
        }
    }

    if (searchToggle) {
        searchToggle.addEventListener('click', toggleFullwidthSearch);
    }

    if (searchClose) {
        searchClose.addEventListener('click', toggleFullwidthSearch);
    }

    // Close search on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && fullwidthSearch && fullwidthSearch.classList.contains('active')) {
            toggleFullwidthSearch();
        }
    });

    // Announcement bar close
    const announcementClose = document.querySelector('.header-layout-2 .announcement-close');
    if (announcementClose) {
        announcementClose.addEventListener('click', function() {
            const announcementBar = document.querySelector('.header-layout-2 .announcement-bar');
            if (announcementBar) {
                announcementBar.style.display = 'none';
                // Save to localStorage so it stays closed
                localStorage.setItem('pratikwp_announcement_closed', 'true');
            }
        });

        // Check if announcement was previously closed
        if (localStorage.getItem('pratikwp_announcement_closed') === 'true') {
            const announcementBar = document.querySelector('.header-layout-2 .announcement-bar');
            if (announcementBar) {
                announcementBar.style.display = 'none';
            }
        }
    }
});
</script>