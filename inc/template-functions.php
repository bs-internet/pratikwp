<?php
/**
 * Pluggable Template Functions
 * These functions can be overridden by a child theme.
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('pratikwp_header_content')) {
    /**
     * Display header content
     *
     * @since 1.1.0
     */
    function pratikwp_header_content() {
        ?>
        <header id="masthead" class="site-header<?php echo get_theme_mod('header_sticky', true) ? ' header-sticky' : ''; ?>" role="banner">
            <div class="container">
                <div class="header-inner">
                    
                    <div class="site-branding">
                        <?php if (has_custom_logo()) : ?>
                            <?php the_custom_logo(); ?>
                        <?php else : ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                            </h1>
                            <?php $description = get_bloginfo('description', 'display');
                            if ($description || is_customize_preview()) : ?>
                                <p class="site-description"><?php echo $description; ?></p>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <nav id="site-navigation" class="main-navigation" role="navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'primary',
                            'menu_id'        => 'primary-menu',
                            'container'      => false,
                            'fallback_cb'    => false,
                            'walker'         => new PratikWp_Walker_Nav_Menu()
                        ]);
                        ?>
                    </nav>
                    
                    <?php if (get_theme_mod('header_search', true)) : ?>
                    <div class="header-search">
                        <button class="search-toggle" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>">
                            <span class="search-icon">🔍</span>
                        </button>
                        <div class="search-form-wrapper">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Menü', 'pratikwp'); ?>">
                        <span class="menu-icon">☰</span>
                    </button>
                    
                </div>
            </div>
        </header>
        <?php
    }
}

if (!function_exists('pratikwp_footer_content')) {
    /**
     * Display footer content
     *
     * @since 1.1.0
     */
    function pratikwp_footer_content() {
        ?>
        <footer id="colophon" class="site-footer" role="contentinfo">
            <?php if (is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <div class="row">
                        <?php for ( $i = 1; $i <= 4; $i++ ) :
                            if ( is_active_sidebar( 'footer-' . $i ) ) : ?>
                                <div class="col-lg-3 col-md-6">
                                    <?php dynamic_sidebar( 'footer-' . $i ); ?>
                                </div>
                            <?php endif;
                        endfor; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="site-info">
                <div class="container">
                   <div class="copyright-text">
                        <?php
                        $copyright = get_theme_mod('copyright_text', sprintf(__('© %s %s. Tüm hakları saklıdır.', 'pratikwp'), date('Y'), get_bloginfo('name')));
                        echo wp_kses_post($copyright);
                        ?>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }
}

if (!function_exists('pratikwp_post_header_content')) {
    /**
     * Display single post header content.
     */
    function pratikwp_post_header_content() {
        ?>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
            <div class="entry-meta">
                <?php pratikwp_post_meta(); ?>
            </div>
        </header>
        <?php
    }
}

if (!function_exists('pratikwp_post_content_body')) {
    /**
     * Display single post content.
     */
    function pratikwp_post_content_body() {
        if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
        </div>
        <?php endif; ?>

        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages([
                'before' => '<div class="page-links">' . esc_html__('Sayfalar:', 'pratikwp'),
                'after'  => '</div>',
            ]);
            ?>
        </div>
        <?php
    }
}

if (!function_exists('pratikwp_post_footer_content')) {
    /**
     * Display single post footer content.
     */
    function pratikwp_post_footer_content() {
        ?>
        <footer class="entry-footer">
            <?php the_tags('<div class="tag-links"><strong>' . esc_html__('Etiketler:', 'pratikwp') . '</strong> ', ', ', '</div>'); ?>
            <?php edit_post_link(__('Düzenle', 'pratikwp'), '<div class="edit-link">', '</div>'); ?>
        </footer>
        <?php
    }
}

if (!function_exists('pratikwp_page_header_content')) {
    /**
    * Display page header content.
    * Automatically hides the title on pages built with Elementor.
    */
    function pratikwp_page_header_content() {
        
        if ( did_action('elementor/loaded') && \Elementor\Plugin::$instance->db->is_built_with_elementor( get_the_ID() ) ) {
            return;
        }

        if ( get_post_meta( get_the_ID(), '_pratikwp_hide_page_title', true ) ) {
            return;
        }
        ?>
        <header class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <?php
    }
}

if (!function_exists('pratikwp_page_content_body')) {
    /**
    * Display page content.
    */
    function pratikwp_page_content_body() {
        ?>
        <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
        </div>
        <?php endif; ?>
        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages([
                'before' => '<div class="page-links">' . esc_html__('Sayfalar:', 'pratikwp'),
                'after'  => '</div>',
            ]);
            ?>
        </div>
        <?php edit_post_link(__('Düzenle', 'pratikwp'), '<footer class="entry-footer"><div class="edit-link">', '</div></footer>'); ?>
        <?php
    }
}