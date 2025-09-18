<?php
/**
 * Enqueue Assets Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Enqueue {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'frontend_assets'], 10);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets'], 10);
        add_action('enqueue_block_editor_assets', [$this, 'editor_assets'], 10);
        add_action('wp_enqueue_scripts', [$this, 'conditional_assets'], 20);
        add_action('wp_head', [$this, 'preload_assets'], 1);
        add_filter('style_loader_tag', [$this, 'add_preload_attributes'], 10, 4);
        add_filter('script_loader_tag', [$this, 'add_async_defer'], 10, 3);
    }

    /**
     * Frontend assets
     */
    public function frontend_assets() {
        // Framework CSS (Pure CSS grid system)
        wp_enqueue_style(
            'pratikwp-framework',
            PRATIKWP_ASSETS . '/css/framework.css',
            [],
            PRATIKWP_VERSION
        );
        
        // Main theme stylesheet
        wp_enqueue_style(
            'pratikwp-style',
            get_stylesheet_uri(),
            ['pratikwp-framework'],
            PRATIKWP_VERSION
        );
        
        // Responsive styles
        wp_enqueue_style(
            'pratikwp-responsive',
            PRATIKWP_ASSETS . '/css/responsive.css',
            ['pratikwp-style'],
            PRATIKWP_VERSION,
            'screen'
        );
        
        // Main JavaScript
        wp_enqueue_script(
            'pratikwp-main',
            PRATIKWP_ASSETS . '/js/main.js',
            ['jquery'],
            PRATIKWP_VERSION,
            true
        );
        
        // Sticky header
        if (get_theme_mod('enable_sticky_header', true)) {
            wp_enqueue_script(
                'pratikwp-sticky-header',
                PRATIKWP_ASSETS . '/js/sticky-header.js',
                ['jquery'],
                PRATIKWP_VERSION,
                true
            );
        }
        
        // Smooth scroll
        if (get_theme_mod('enable_smooth_scroll', true)) {
            wp_enqueue_script(
                'pratikwp-smooth-scroll',
                PRATIKWP_ASSETS . '/js/smooth-scroll.js',
                [],
                PRATIKWP_VERSION,
                true
            );
        }
        
        // Localize script for AJAX
        wp_localize_script('pratikwp-main', 'pratikwp_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('pratikwp_nonce'),
            'strings'  => [
                'loading' => __('Yükleniyor...', 'pratikwp'),
                'error'   => __('Bir hata oluştu.', 'pratikwp'),
            ]
        ]);
        
        // Comment reply script
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
        
        // Print styles
        wp_enqueue_style(
            'pratikwp-print',
            PRATIKWP_ASSETS . '/css/print.css',
            [],
            PRATIKWP_VERSION,
            'print'
        );
    }

    /**
     * Conditional assets based on page type
     */
    public function conditional_assets() {
        // Slider assets for front page
        if (is_front_page() && get_theme_mod('enable_homepage_slider', true)) {
            wp_enqueue_script(
                'pratikwp-slider',
                PRATIKWP_ASSETS . '/js/slider.js',
                ['jquery'],
                PRATIKWP_VERSION,
                true
            );
            
            wp_enqueue_style(
                'pratikwp-slider',
                PRATIKWP_ASSETS . '/css/slider.css',
                [],
                PRATIKWP_VERSION
            );
        }
        
        // Archive grid styles
        if (is_archive() || is_home()) {
            $archive_layout = get_theme_mod('archive_layout', 'grid');
            
            wp_enqueue_style(
                'pratikwp-archive',
                PRATIKWP_ASSETS . '/css/archive-' . $archive_layout . '.css',
                [],
                PRATIKWP_VERSION
            );
            
            // Masonry for grid layout
            if ($archive_layout === 'masonry') {
                wp_enqueue_script(
                    'pratikwp-masonry',
                    PRATIKWP_ASSETS . '/js/masonry.js',
                    ['jquery', 'masonry'],
                    PRATIKWP_VERSION,
                    true
                );
            }
        }
        
        // Contact form styles
        if (is_page_template('templates/contact.php')) {
            wp_enqueue_style(
                'pratikwp-contact',
                PRATIKWP_ASSETS . '/css/contact.css',
                [],
                PRATIKWP_VERSION
            );
        }
        
        // WooCommerce compatibility
        if (class_exists('WooCommerce')) {
            wp_enqueue_style(
                'pratikwp-woocommerce',
                PRATIKWP_ASSETS . '/css/woocommerce.css',
                [],
                PRATIKWP_VERSION
            );
        }
    }

    /**
     * Admin assets
     */
    public function admin_assets($hook) {
        // Global admin styles
        wp_enqueue_style(
            'pratikwp-admin-global',
            PRATIKWP_ASSETS . '/css/admin-global.css',
            [],
            PRATIKWP_VERSION
        );
        
        // Theme admin pages only
        if (strpos($hook, 'pratikwp') !== false) {
            wp_enqueue_media();
            wp_enqueue_script('wp-color-picker');
            
            wp_enqueue_style(
                'pratikwp-admin',
                PRATIKWP_ASSETS . '/css/admin.css',
                ['wp-color-picker'],
                PRATIKWP_VERSION
            );
            
            wp_enqueue_script(
                'pratikwp-admin',
                PRATIKWP_ASSETS . '/js/admin.js',
                ['jquery', 'wp-color-picker', 'media-upload'],
                PRATIKWP_VERSION,
                true
            );
            
            // Localize admin script
            wp_localize_script('pratikwp-admin', 'pratikwp_admin', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('pratikwp_admin_nonce'),
                'strings'  => [
                    'confirm_reset' => __('Tüm ayarları sıfırlamak istediğinizden emin misiniz?', 'pratikwp'),
                    'saving'        => __('Kaydediliyor...', 'pratikwp'),
                    'saved'         => __('Kaydedildi!', 'pratikwp'),
                    'select_image'  => __('Resim Seç', 'pratikwp'),
                    'use_image'     => __('Kullan', 'pratikwp'),
                ]
            ]);
        }
        
        // Customizer assets
        if ($hook === 'customize.php') {
            wp_enqueue_script(
                'pratikwp-customizer',
                PRATIKWP_ASSETS . '/js/customizer.js',
                ['jquery', 'customize-controls'],
                PRATIKWP_VERSION,
                true
            );
            
            wp_enqueue_style(
                'pratikwp-customizer',
                PRATIKWP_ASSETS . '/css/customizer.css',
                [],
                PRATIKWP_VERSION
            );
        }
        
        // Widget admin styles
        if ($hook === 'widgets.php') {
            wp_enqueue_style(
                'pratikwp-widgets',
                PRATIKWP_ASSETS . '/css/widgets.css',
                [],
                PRATIKWP_VERSION
            );
        }
    }

    /**
     * Block editor assets
     */
    public function editor_assets() {
        wp_enqueue_style(
            'pratikwp-editor',
            PRATIKWP_ASSETS . '/css/editor.css',
            [],
            PRATIKWP_VERSION
        );
        
        wp_enqueue_script(
            'pratikwp-editor',
            PRATIKWP_ASSETS . '/js/editor.js',
            ['wp-blocks', 'wp-dom-ready', 'wp-edit-post'],
            PRATIKWP_VERSION,
            true
        );
    }

    /**
     * Preload critical assets
     */
    public function preload_assets() {
        if (!get_theme_mod('enable_preloading', true)) {
            return;
        }
        
        // Preload critical CSS
        echo '<link rel="preload" href="' . esc_url(PRATIKWP_ASSETS . '/css/framework.css') . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        
        // Preload fonts if using custom fonts
        $body_font = get_theme_mod('body_font_family', '');
        if (!empty($body_font) && strpos($body_font, 'fonts.googleapis.com') !== false) {
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        }
        
        // Preload hero image on front page
        if (is_front_page()) {
            $hero_image = get_theme_mod('hero_background_image', '');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">' . "\n";
            }
        }
    }

    /**
     * Add preload attributes to stylesheets
     */
    public function add_preload_attributes($html, $handle, $href, $media) {
        if (!get_theme_mod('enable_css_preload', false)) {
            return $html;
        }
        
        // Critical CSS files to preload
        $critical_styles = [
            'pratikwp-framework',
            'pratikwp-style'
        ];
        
        if (in_array($handle, $critical_styles)) {
            $html = '<link rel="preload" href="' . esc_url($href) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" id="' . esc_attr($handle) . '-css">';
            $html .= '<noscript><link rel="stylesheet" href="' . esc_url($href) . '"></noscript>';
        }
        
        return $html;
    }

    /**
     * Add async/defer attributes to scripts
     */
    public function add_async_defer($tag, $handle, $src) {
        if (!get_theme_mod('enable_script_optimization', false)) {
            return $tag;
        }
        
        // Scripts to defer
        $defer_scripts = [
            'pratikwp-main',
            'pratikwp-sticky-header',
            'pratikwp-smooth-scroll'
        ];
        
        // Scripts to async
        $async_scripts = [
            'pratikwp-slider',
            'pratikwp-masonry'
        ];
        
        if (in_array($handle, $defer_scripts)) {
            return str_replace('<script ', '<script defer ', $tag);
        }
        
        if (in_array($handle, $async_scripts)) {
            return str_replace('<script ', '<script async ', $tag);
        }
        
        return $tag;
    }

    /**
     * Inline critical CSS
     */
    public function inline_critical_css() {
        if (!get_theme_mod('inline_critical_css', false)) {
            return;
        }
        
        $critical_css_file = PRATIKWP_DIR . '/assets/css/critical.css';
        
        if (file_exists($critical_css_file)) {
            $critical_css = file_get_contents($critical_css_file);
            if ($critical_css) {
                echo '<style id="pratikwp-critical-css">' . $critical_css . '</style>' . "\n";
            }
        }
    }
}