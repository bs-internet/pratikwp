<?php
/**
 * PratikWp Theme Functions
 * Elementor Compatible WordPress Theme
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('PRATIKWP_VERSION', '1.0.0');
define('PRATIKWP_DIR', get_template_directory());
define('PRATIKWP_URI', get_template_directory_uri());
define('PRATIKWP_INC', PRATIKWP_DIR . '/inc');
define('PRATIKWP_ASSETS', PRATIKWP_URI . '/assets');

/**
 * Auto-load classes
 */
spl_autoload_register(function ($class_name) {
    if (strpos($class_name, 'PratikWp_') === 0) {
        $class_file = PRATIKWP_INC . '/classes/' . str_replace('PratikWp_', '', $class_name) . '.php';
        if (file_exists($class_file)) {
            require_once $class_file;
        }
    }
});

/**
 * Core Theme Setup
 */
class PratikWp_Theme {
    
    public function __construct() {
        add_action('after_setup_theme', [$this, 'theme_setup']);
        add_action('init', [$this, 'init_theme_classes']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('elementor/theme/register_locations', [$this, 'register_elementor_locations']);
    }

    /**
     * Theme setup
     */
    public function theme_setup() {
        // Language support
        load_theme_textdomain('pratikwp', PRATIKWP_DIR . '/languages');
        
        // Theme supports
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('custom-logo', [
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ]);
        
        // HTML5 support
        add_theme_support('html5', [
            'search-form',
            'comment-form', 
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script'
        ]);
        
        // Feed links
        add_theme_support('automatic-feed-links');
        
        // Editor styles
        add_theme_support('editor-styles');
        add_editor_style('assets/css/editor-style.css');
        
        // Custom header
        add_theme_support('custom-header', [
            'default-image'      => '',
            'default-text-color' => '000',
            'width'              => 1200,
            'height'             => 400,
            'flex-width'         => true,
            'flex-height'        => true,
        ]);
        
        // Custom background
        add_theme_support('custom-background', [
            'default-color' => 'ffffff',
        ]);
        
        // Elementor support
        add_theme_support('elementor');
        
        // Navigation menus
        register_nav_menus([
            'primary' => __('Ana Menü', 'pratikwp'),
            'footer'  => __('Alt Menü', 'pratikwp'),
            'mobile'  => __('Mobil Menü', 'pratikwp'),
        ]);
        
        // Content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
    }

    /**
     * Initialize theme classes
     */
    public function init_theme_classes() {
        // Core classes
        new PratikWp_Enqueue();
        new PratikWp_Helpers();
        new PratikWp_ThemeSettings();
        
        // Slider system (maintained)
        global $slider_settings;
        $slider_settings = new PratikWp_SliderSettings();
        
        // New systems
        new PratikWp_Customizer();
        new PratikWp_Elementor();
        new PratikWp_Performance();
        new PratikWp_Admin();
        
        // Widgets
        add_action('widgets_init', [$this, 'register_widgets']);
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_assets() {
        // Main CSS framework (Pure CSS grid system)
        wp_enqueue_style(
            'pratikwp-framework',
            PRATIKWP_ASSETS . '/css/framework.css',
            [],
            PRATIKWP_VERSION
        );
        
        // Theme main styles
        wp_enqueue_style(
            'pratikwp-style',
            get_stylesheet_uri(),
            ['pratikwp-framework'],
            PRATIKWP_VERSION
        );
        
        // Dynamic styles from customizer
        $this->add_dynamic_styles();
        
        // Main JS
        wp_enqueue_script(
            'pratikwp-main',
            PRATIKWP_ASSETS . '/js/main.js',
            ['jquery'],
            PRATIKWP_VERSION,
            true
        );
        
        // Localize script
        wp_localize_script('pratikwp-main', 'pratikwp_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('pratikwp_nonce'),
        ]);
        
        // Comment reply
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Admin assets
     */
    public function admin_assets($hook) {
        // Theme admin pages only
        if (strpos($hook, 'pratikwp') !== false) {
            wp_enqueue_media();
            
            wp_enqueue_style(
                'pratikwp-admin',
                PRATIKWP_ASSETS . '/css/admin.css',
                [],
                PRATIKWP_VERSION
            );
            
            wp_enqueue_script(
                'pratikwp-admin',
                PRATIKWP_ASSETS . '/js/admin.js',
                ['jquery', 'wp-color-picker'],
                PRATIKWP_VERSION,
                true
            );
        }
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        register_sidebar([
            'name'          => __('Sidebar', 'pratikwp'),
            'id'            => 'main-sidebar',
            'description'   => __('Ana sidebar widget alanı', 'pratikwp'),
            'before_widget' => '<div class="widget mb-4">',
            'after_widget'  => '</div>',
            'before_title'  => '<h5 class="widget-title">',
            'after_title'   => '</h5>',
        ]);
        
        register_sidebar([
            'name'          => __('Footer 1', 'pratikwp'),
            'id'            => 'footer-1',
            'description'   => __('Footer ilk kolon', 'pratikwp'),
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h6 class="footer-widget-title">',
            'after_title'   => '</h6>',
        ]);
        
        register_sidebar([
            'name'          => __('Footer 2', 'pratikwp'),
            'id'            => 'footer-2',
            'description'   => __('Footer ikinci kolon', 'pratikwp'),
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h6 class="footer-widget-title">',
            'after_title'   => '</h6>',
        ]);
        
        register_sidebar([
            'name'          => __('Footer 3', 'pratikwp'),
            'id'            => 'footer-3',
            'description'   => __('Footer üçüncü kolon', 'pratikwp'),
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h6 class="footer-widget-title">',
            'after_title'   => '</h6>',
        ]);
        
        register_sidebar([
            'name'          => __('Footer 4', 'pratikwp'),
            'id'            => 'footer-4',
            'description'   => __('Footer dördüncü kolon', 'pratikwp'),
            'before_widget' => '<div class="footer-widget">',
            'after_widget'  => '</div>',
            'before_title'  => '<h6 class="footer-widget-title">',
            'after_title'   => '</h6>',
        ]);
    }

    /**
     * Register Elementor theme locations
     */
    public function register_elementor_locations($manager) {
        $manager->register_location('header');
        $manager->register_location('footer');
        $manager->register_location('single');
        $manager->register_location('archive');
    }

    /**
     * Add dynamic CSS from customizer
     */
    private function add_dynamic_styles() {
        $css = $this->generate_dynamic_css();
        
        if (!empty($css)) {
            wp_add_inline_style('pratikwp-style', $css);
        }
    }

    /**
     * Generate dynamic CSS
     */
    private function generate_dynamic_css() {
        $css = '';
        
        // Primary color
        $primary_color = get_theme_mod('primary_color', '#007cba');
        if ($primary_color !== '#007cba') {
            $css .= "
                :root {
                    --bs-primary: {$primary_color};
                }
                .btn-primary {
                    background-color: {$primary_color};
                    border-color: {$primary_color};
                }
            ";
        }
        
        // Typography
        $body_font = get_theme_mod('body_font_family', '');
        if (!empty($body_font)) {
            $css .= "body { font-family: {$body_font}; }";
        }
        
        $heading_font = get_theme_mod('heading_font_family', '');
        if (!empty($heading_font)) {
            $css .= "h1, h2, h3, h4, h5, h6 { font-family: {$heading_font}; }";
        }
        
        return $css;
    }
}

// Initialize theme
new PratikWp_Theme();

/**
 * Required files
 */
require_once PRATIKWP_INC . '/template-functions.php';
require_once PRATIKWP_INC . '/template-hooks.php';