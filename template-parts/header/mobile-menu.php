<?php
/**
 * Template part for displaying mobile menu
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$mobile_menu_style = get_theme_mod('mobile_menu_style', 'offcanvas');
$show_mobile_search = get_theme_mod('mobile_menu_show_search', true);
$show_mobile_social = get_theme_mod('mobile_menu_show_social', true);
$show_mobile_contact = get_theme_mod('mobile_menu_show_contact', false);
?>

<!-- Mobile Menu Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    
    <!-- Offcanvas Header -->
    <div class="offcanvas-header border-bottom">
        <div class="offcanvas-title d-flex align-items-center" id="mobileMenuLabel">
            <?php
            $mobile_logo = get_theme_mod('mobile_logo');
            $site_title = get_bloginfo('name');
            
            if ($mobile_logo) :
            ?>
            <img src="<?php echo esc_url($mobile_logo); ?>" alt="<?php echo esc_attr($site_title); ?>" class="mobile-logo" height="40">
            <?php else : ?>
            <h5 class="mb-0"><?php echo esc_html($site_title); ?></h5>
            <?php endif; ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e('Kapat', 'pratikwp'); ?>"></button>
    </div>

    <!-- Offcanvas Body -->
    <div class="offcanvas-body">
        
        <!-- Mobile Search -->
        <?php if ($show_mobile_search) : ?>
        <div class="mobile-search mb-4">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="input-group">
                    <input type="search" 
                           class="form-control" 
                           placeholder="<?php echo esc_attr_x('Arama...', 'placeholder', 'pratikwp'); ?>" 
                           value="<?php echo get_search_query(); ?>" 
                           name="s" 
                           autocomplete="off">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Mobile Navigation -->
        <nav class="mobile-navigation" role="navigation">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class' => 'mobile-menu-list list-unstyled',
                'container' => false,
                'walker' => new PratikWp_Mobile_Walker_Nav_Menu(),
                'fallback_cb' => 'pratikwp_mobile_menu_fallback'
            ]);
            ?>
        </nav>

        <!-- Mobile Contact Info -->
        <?php if ($show_mobile_contact) : ?>
        <div class="mobile-contact mt-4 pt-4 border-top">
            <h6 class="mb-3"><?php esc_html_e('İletişim', 'pratikwp'); ?></h6>
            
            <?php 
            $phone = get_theme_mod('contact_phone', '');
            if ($phone) : ?>
            <div class="contact-item d-flex align-items-center mb-3">
                <div class="contact-icon me-3">
                    <i class="fas fa-phone text-primary"></i>
                </div>
                <div class="contact-content">
                    <a href="tel:<?php echo esc_attr(str_replace([' ', '-', '(', ')'], '', $phone)); ?>" class="text-decoration-none">
                        <?php echo esc_html($phone); ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $email = get_theme_mod('contact_email', '');
            if ($email) : ?>
            <div class="contact-item d-flex align-items-center mb-3">
                <div class="contact-icon me-3">
                    <i class="fas fa-envelope text-primary"></i>
                </div>
                <div class="contact-content">
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="text-decoration-none">
                        <?php echo esc_html($email); ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $address = get_theme_mod('contact_address', '');
            if ($address) : ?>
            <div class="contact-item d-flex align-items-start mb-3">
                <div class="contact-icon me-3">
                    <i class="fas fa-map-marker-alt text-primary"></i>
                </div>
                <div class="contact-content">
                    <span><?php echo esc_html($address); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <?php 
            $working_hours = get_theme_mod('working_hours', '');
            if ($working_hours) : ?>
            <div class="contact-item d-flex align-items-center mb-3">
                <div class="contact-icon me-3">
                    <i class="fas fa-clock text-primary"></i>
                </div>
                <div class="contact-content">
                    <span><?php echo esc_html($working_hours); ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Mobile Social Links -->
        <?php if ($show_mobile_social) : ?>
        <div class="mobile-social mt-4 pt-4 border-top">
            <h6 class="mb-3"><?php esc_html_e('Sosyal Medya', 'pratikwp'); ?></h6>
            <div class="social-links d-flex flex-wrap">
                <?php
                $social_links = [
                    'facebook' => [
                        'url' => get_theme_mod('social_facebook', ''),
                        'icon' => 'fab fa-facebook-f',
                        'color' => 'bg-primary'
                    ],
                    'twitter' => [
                        'url' => get_theme_mod('social_twitter', ''),
                        'icon' => 'fab fa-twitter',
                        'color' => 'bg-info'
                    ],
                    'instagram' => [
                        'url' => get_theme_mod('social_instagram', ''),
                        'icon' => 'fab fa-instagram',
                        'color' => 'bg-danger'
                    ],
                    'linkedin' => [
                        'url' => get_theme_mod('social_linkedin', ''),
                        'icon' => 'fab fa-linkedin-in',
                        'color' => 'bg-primary'
                    ],
                    'youtube' => [
                        'url' => get_theme_mod('social_youtube', ''),
                        'icon' => 'fab fa-youtube',
                        'color' => 'bg-danger'
                    ],
                    'whatsapp' => [
                        'url' => get_theme_mod('social_whatsapp', ''),
                        'icon' => 'fab fa-whatsapp',
                        'color' => 'bg-success'
                    ]
                ];

                foreach ($social_links as $platform => $data) :
                    if ($data['url']) :
                        $target = ($platform === 'whatsapp') ? '_self' : '_blank';
                        $href = ($platform === 'whatsapp') ? 'https://wa.me/' . $data['url'] : $data['url'];
                ?>
                <a href="<?php echo esc_url($href); ?>" 
                   target="<?php echo esc_attr($target); ?>"
                   class="social-link btn btn-sm <?php echo esc_attr($data['color']); ?> text-white me-2 mb-2 rounded-circle"
                   style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"
                   aria-label="<?php echo esc_attr(ucfirst($platform)); ?>"
                   <?php if ($target === '_blank') echo 'rel="noopener noreferrer"'; ?>>
                    <i class="<?php echo esc_attr($data['icon']); ?>"></i>
                </a>
                <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- WooCommerce Account & Cart (if available) -->
        <?php if (class_exists('WooCommerce')) : ?>
        <div class="mobile-woo-links mt-4 pt-4 border-top">
            <div class="row">
                <div class="col-6">
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="btn btn-outline-primary w-100">
                        <i class="fas fa-user me-2"></i>
                        <?php esc_html_e('Hesabım', 'pratikwp'); ?>
                    </a>
                </div>
                <div class="col-6">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="btn btn-outline-primary w-100 position-relative">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <?php esc_html_e('Sepet', 'pratikwp'); ?>
                        <?php
                        $cart_count = WC()->cart->get_cart_contents_count();
                        if ($cart_count > 0) :
                        ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo esc_html($cart_count); ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Language Switcher -->
        <?php if (function_exists('pll_the_languages')) : ?>
        <div class="mobile-language mt-4 pt-4 border-top">
            <h6 class="mb-3"><?php esc_html_e('Dil Seçenekleri', 'pratikwp'); ?></h6>
            <div class="language-options">
                <?php
                pll_the_languages([
                    'show_names' => 1,
                    'show_flags' => 1,
                    'hide_current' => 0
                ]);
                ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php
/**
 * Mobile menu walker class
 */
class PratikWp_Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu list-unstyled ps-3\">\n";
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="mobile-menu-item ' . esc_attr($class_names) . '"' : ' class="mobile-menu-item"';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $has_children = in_array('menu-item-has-children', $classes);
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target) ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn) ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url) ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . ' class="mobile-menu-link d-flex align-items-center justify-content-between py-2">';
        $item_output .= '<span>' . (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '') . '</span>';
        
        if ($has_children) {
            $item_output .= '<i class="fas fa-chevron-down submenu-toggle"></i>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Mobile menu fallback
 */
function pratikwp_mobile_menu_fallback() {
    echo '<ul class="mobile-menu-list list-unstyled">';
    echo '<li class="mobile-menu-item"><a href="' . esc_url(home_url('/')) . '" class="mobile-menu-link d-block py-2">' . esc_html__('Ana Sayfa', 'pratikwp') . '</a></li>';
    wp_list_pages(['title_li' => '', 'echo' => 1, 'link_before' => '<span class="mobile-menu-link d-block py-2">', 'link_after' => '</span>']);
    echo '</ul>';
}
?>