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
        add_action('widgets_init', [$this, 'register_widgets']);
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
        
        // Elementor support
        add_theme_support('elementor');
        
        // Navigation menus
        register_nav_menus([
            'primary' => __('Ana Menü', 'pratikwp'),
            'footer'  => __('Alt Menü', 'pratikwp'),
        ]);
        
        // Content width
        if (!isset($content_width)) {
            $content_width = 1200;
        }
    }

    /**
     * Initialize theme classes - SADECE TEMEL CLASSLAR
     */
    public function init_theme_classes() {
        // Temel sistemler
        new PratikWp_Elementor();
        new PratikWp_Admin();
        new PratikWp_WhatsApp();
        
        // Korunacak sistemler (gereksinimler)
        global $slider_settings;
        $slider_settings = new PratikWp_SliderSettings();
        
        // Meta Boxes - basitleştirilmiş
        require_once PRATIKWP_INC . '/admin/meta-boxes/page-settings.php';
        require_once PRATIKWP_INC . '/admin/meta-boxes/post-settings.php';
        new PratikWp_Page_Settings();
        new PratikWp_Post_Settings();
    }

    /**
     * Enqueue assets - TEK CSS VE JS
     */
    public function enqueue_assets() {
        // Tek CSS dosyası - framework.css kaldırıldı
        wp_enqueue_style(
            'pratikwp-style',
            get_stylesheet_uri(),
            [],
            PRATIKWP_VERSION
        );
        
        // Tek JS dosyası - tüm fonksiyonlar birleştirildi
        wp_enqueue_script(
            'pratikwp-main',
            PRATIKWP_ASSETS . '/js/main.js',
            ['jquery'],
            PRATIKWP_VERSION,
            true
        );
        
        // AJAX localize
        wp_localize_script('pratikwp-main', 'pratikwp_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('pratikwp_nonce'),
        ]);
        
        // Dynamic CSS from customizer
        $this->add_dynamic_styles();
        
        // Comment reply
        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * Admin assets - sadeleştirildi
     */
    public function admin_assets($hook) {
        // Sadece tema admin sayfalarında yükle
        if (strpos($hook, 'pratikwp') !== false) {
            wp_enqueue_media();
            wp_enqueue_style('wp-color-picker');
            
            wp_enqueue_style(
                'pratikwp-admin',
                PRATIKWP_ASSETS . '/css/admin.css',
                ['wp-color-picker'],
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
            'name'          => __('Ana Sidebar', 'pratikwp'),
            'id'            => 'main-sidebar',
            'description'   => __('Ana kenar çubuğu', 'pratikwp'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ]);
        
        // Footer widget areas - 4 kolon
        for ($i = 1; $i <= 4; $i++) {
            register_sidebar([
                'name'          => sprintf(__('Footer %d. Kolon', 'pratikwp'), $i),
                'id'            => 'footer-' . $i,
                'description'   => sprintf(__('Footer %d. kolon widget alanı', 'pratikwp'), $i),
                'before_widget' => '<div id="%1$s" class="widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ]);
        }
    }

    /**
     * Register Elementor locations
     */
    public function register_elementor_locations($elementor_theme_manager) {
        $elementor_theme_manager->register_location('header');
        $elementor_theme_manager->register_location('footer'); 
        $elementor_theme_manager->register_location('single');
        $elementor_theme_manager->register_location('archive');
    }

    /**
     * Dynamic CSS from customizer - basitleştirildi
     */
    private function add_dynamic_styles() {
        $css = '';
        
        // Sadece primary color
        $primary_color = get_theme_mod('primary_color', '#007cba');
        if ($primary_color !== '#007cba') {
            $css .= ":root { --primary-color: {$primary_color}; }";
        }
        
        // Logo width
        $logo_width = get_theme_mod('logo_width', 200);
        if ($logo_width !== 200) {
            $css .= ".site-logo { max-width: {$logo_width}px; }";
        }
        
        if (!empty($css)) {
            wp_add_inline_style('pratikwp-style', $css);
        }
    }
}

// Initialize theme
new PratikWp_Theme();

/**
 * Required files - SADECE TEMEL DOSYALAR
 */
require_once PRATIKWP_INC . '/template-functions.php';
require_once PRATIKWP_INC . '/template-helpers.php';
require_once PRATIKWP_INC . '/template-hooks.php';
require_once PRATIKWP_INC . '/walker-nav-menu.php';
require_once PRATIKWP_INC . '/widgets/company-info.php';
require_once PRATIKWP_INC . '/widgets/social-media.php';