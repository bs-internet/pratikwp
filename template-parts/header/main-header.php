<?php
/**
 * Template part for displaying main header
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$header_layout = get_theme_mod('header_layout', 'layout-1');
$header_style = get_theme_mod('header_style', 'light');
$sticky_header = get_theme_mod('sticky_header', true);
$show_search_button = get_theme_mod('header_show_search', true);
$show_cart_icon = get_theme_mod('header_show_cart', false);
$logo_position = get_theme_mod('logo_position', 'left');

$header_classes = [
    'main-header',
    'header-' . $header_layout,
    'bg-' . $header_style,
    'text-' . ($header_style === 'light' ? 'dark' : 'light')
];

if ($sticky_header) {
    $header_classes[] = 'sticky-header';
}
?>

<div class="<?php echo esc_attr(implode(' ', $header_classes)); ?>" id="main-header">
    <div class="container">
        
        <?php if ($header_layout === 'layout-1') : ?>
        <!-- Layout 1: Logo Left, Menu Right -->
        <div class="row align-items-center py-3">
            <div class="col-6 col-lg-3">
                <?php get_template_part('template-parts/header/site-logo'); ?>
            </div>
            <div class="col-6 col-lg-9">
                <div class="header-right d-flex align-items-center justify-content-end">
                    
                    <!-- Desktop Navigation -->
                    <nav class="main-navigation d-none d-lg-block me-auto" role="navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_class' => 'navbar-nav main-menu',
                            'container' => false,
                            'walker' => new PratikWp_Walker_Nav_Menu()
                        ]);
                        ?>
                    </nav>

                    <!-- Header Actions -->
                    <div class="header-actions d-flex align-items-center">
                        
                        <?php if ($show_search_button) : ?>
                        <button type="button" class="btn btn-link header-search-btn me-2" data-bs-toggle="modal" data-bs-target="#headerSearchModal" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php endif; ?>

                        <?php if ($show_cart_icon && class_exists('WooCommerce')) : ?>
                        <div class="header-cart me-3">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <?php
                                $cart_count = WC()->cart->get_cart_contents_count();
                                if ($cart_count > 0) :
                                ?>
                                <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo esc_html($cart_count); ?>
                                </span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Mobile Menu Toggle -->
                        <button class="btn btn-link mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="<?php esc_attr_e('Menü', 'pratikwp'); ?>">
                            <span class="navbar-toggler-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php elseif ($header_layout === 'layout-2') : ?>
        <!-- Layout 2: Centered Logo, Menu Below -->
        <div class="text-center py-4">
            <?php get_template_part('template-parts/header/site-logo'); ?>
        </div>
        <nav class="main-navigation border-top pt-3" role="navigation">
            <div class="row align-items-center">
                <div class="col-lg-10">
                    <div class="d-none d-lg-block">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_class' => 'navbar-nav main-menu justify-content-center',
                            'container' => false,
                            'walker' => new PratikWp_Walker_Nav_Menu()
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="header-actions d-flex align-items-center justify-content-lg-end">
                        
                        <?php if ($show_search_button) : ?>
                        <button type="button" class="btn btn-link header-search-btn me-2" data-bs-toggle="modal" data-bs-target="#headerSearchModal" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php endif; ?>

                        <?php if ($show_cart_icon && class_exists('WooCommerce')) : ?>
                        <div class="header-cart me-3">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <?php
                                $cart_count = WC()->cart->get_cart_contents_count();
                                if ($cart_count > 0) :
                                ?>
                                <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo esc_html($cart_count); ?>
                                </span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Mobile Menu Toggle -->
                        <button class="btn btn-link mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="<?php esc_attr_e('Menü', 'pratikwp'); ?>">
                            <span class="navbar-toggler-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <?php elseif ($header_layout === 'layout-3') : ?>
        <!-- Layout 3: Logo Center, Menu Left & Right -->
        <div class="row align-items-center py-3">
            <div class="col-lg-4">
                <nav class="left-navigation d-none d-lg-block" role="navigation">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary-left',
                        'menu_class' => 'navbar-nav main-menu',
                        'container' => false,
                        'walker' => new PratikWp_Walker_Nav_Menu(),
                        'fallback_cb' => false
                    ]);
                    ?>
                </nav>
            </div>
            <div class="col-6 col-lg-4 text-center">
                <?php get_template_part('template-parts/header/site-logo'); ?>
            </div>
            <div class="col-6 col-lg-4">
                <div class="header-right d-flex align-items-center justify-content-end">
                    
                    <!-- Right Navigation -->
                    <nav class="right-navigation d-none d-lg-block me-auto" role="navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary-right',
                            'menu_class' => 'navbar-nav main-menu justify-content-end',
                            'container' => false,
                            'walker' => new PratikWp_Walker_Nav_Menu(),
                            'fallback_cb' => false
                        ]);
                        ?>
                    </nav>

                    <!-- Header Actions -->
                    <div class="header-actions d-flex align-items-center">
                        
                        <?php if ($show_search_button) : ?>
                        <button type="button" class="btn btn-link header-search-btn me-2" data-bs-toggle="modal" data-bs-target="#headerSearchModal" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php endif; ?>

                        <?php if ($show_cart_icon && class_exists('WooCommerce')) : ?>
                        <div class="header-cart me-3">
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link position-relative">
                                <i class="fas fa-shopping-cart"></i>
                                <?php
                                $cart_count = WC()->cart->get_cart_contents_count();
                                if ($cart_count > 0) :
                                ?>
                                <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo esc_html($cart_count); ?>
                                </span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <?php endif; ?>

                        <!-- Mobile Menu Toggle -->
                        <button class="btn btn-link mobile-menu-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-label="<?php esc_attr_e('Menü', 'pratikwp'); ?>">
                            <span class="navbar-toggler-icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php if ($show_search_button) : ?>
<!-- Header Search Modal -->
<div class="modal fade" id="headerSearchModal" tabindex="-1" aria-labelledby="headerSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="headerSearchModalLabel"><?php esc_html_e('Site İçinde Arama', 'pratikwp'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Kapat', 'pratikwp'); ?>"></button>
            </div>
            <div class="modal-body">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="input-group input-group-lg">
                        <input type="search" 
                               class="form-control" 
                               placeholder="<?php echo esc_attr_x('Ne aramak istiyorsunuz?', 'placeholder', 'pratikwp'); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s" 
                               autocomplete="off"
                               autofocus>
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-search me-2"></i>
                            <?php esc_html_e('Ara', 'pratikwp'); ?>
                        </button>
                    </div>
                </form>
                
                <?php if (get_theme_mod('show_popular_searches', true)) : ?>
                <div class="popular-searches mt-4">
                    <h6 class="mb-3"><?php esc_html_e('Popüler Aramalar:', 'pratikwp'); ?></h6>
                    <div class="popular-tags">
                        <?php
                        $popular_tags = get_tags([
                            'orderby' => 'count',
                            'order' => 'DESC',
                            'number' => 8
                        ]);
                        
                        if ($popular_tags) :
                            foreach ($popular_tags as $tag) :
                        ?>
                        <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="badge bg-light text-dark border me-2 mb-2 p-2 text-decoration-none">
                            <?php echo esc_html($tag->name); ?>
                        </a>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>