<?php
/**
 * Elementor Integration Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Elementor {
    
    public function __construct() {
        add_action('elementor/theme/register_locations', [$this, 'register_locations']);
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_categories']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_elementor_styles']);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles']);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_styles']);
        
        // Theme builder hooks
        add_action('init', [$this, 'init_theme_builder_support']);
        add_filter('elementor/theme/posts_archive/query_posts/query_vars', [$this, 'modify_archive_query']);
        
        // Custom controls
        add_action('elementor/controls/controls_registered', [$this, 'register_controls']);
    }

    /**
     * Register Elementor theme locations
     */
    public function register_locations($elementor_theme_manager) {
        $elementor_theme_manager->register_location('header');
        $elementor_theme_manager->register_location('footer'); 
        $elementor_theme_manager->register_location('single');
        $elementor_theme_manager->register_location('archive');
        $elementor_theme_manager->register_location('search-results');
        $elementor_theme_manager->register_location('404');
    }

    /**
     * Register custom widgets
     */
    public function register_widgets($widgets_manager) {
        // Site Logo Widget
        require_once PRATIKWP_INC . '/elementor/widgets/site-logo.php';
        $widgets_manager->register_widget_type(new \PratikWp_Site_Logo_Widget());
        
        // Navigation Menu Widget
        require_once PRATIKWP_INC . '/elementor/widgets/nav-menu.php';
        $widgets_manager->register_widget_type(new \PratikWp_Nav_Menu_Widget());
        
        // Post Meta Widget
        require_once PRATIKWP_INC . '/elementor/widgets/post-meta.php';
        $widgets_manager->register_widget_type(new \PratikWp_Post_Meta_Widget());
        
        // Breadcrumbs Widget
        require_once PRATIKWP_INC . '/elementor/widgets/breadcrumbs.php';
        $widgets_manager->register_widget_type(new \PratikWp_Breadcrumbs_Widget());
        
        // Company Info Widget
        require_once PRATIKWP_INC . '/elementor/widgets/firma-bilgileri.php';
        $widgets_manager->register_widget_type(new \PratikWp_Company_Info_Widget());
        
        // Social Media Widget
        require_once PRATIKWP_INC . '/elementor/widgets/sosyal-medya.php';
        $widgets_manager->register_widget_type(new \PratikWp_Social_Media_Widget());
        
        // Slider Widget (if not using shortcode)
        if (class_exists('PratikWp_SliderSettings')) {
            require_once PRATIKWP_INC . '/elementor/widgets/slider.php';
            $widgets_manager->register_widget_type(new \PratikWp_Slider_Widget());
        }
        
        // Posts Grid Widget
        require_once PRATIKWP_INC . '/elementor/widgets/posts-grid.php';
        $widgets_manager->register_widget_type(new \PratikWp_Posts_Grid_Widget());
        
        // Search Form Widget
        require_once PRATIKWP_INC . '/elementor/widgets/search-form.php';
        $widgets_manager->register_widget_type(new \PratikWp_Search_Form_Widget());
    }

    /**
     * Register widget categories
     */
    public function register_widget_categories($elements_manager) {
        $elements_manager->add_category('pratikwp-theme', [
            'title' => __('PratikWp Tema', 'pratikwp'),
            'icon' => 'fa fa-plug',
        ]);
        
        $elements_manager->add_category('pratikwp-post', [
            'title' => __('PratikWp Yazı', 'pratikwp'),
            'icon' => 'fa fa-file-text-o',
        ]);
        
        $elements_manager->add_category('pratikwp-site', [
            'title' => __('PratikWp Site', 'pratikwp'),
            'icon' => 'fa fa-home',
        ]);
    }

    /**
     * Enqueue Elementor frontend styles
     */
    public function enqueue_elementor_styles() {
        wp_enqueue_style(
            'pratikwp-elementor',
            PRATIKWP_ASSETS . '/css/elementor.css',
            [],
            PRATIKWP_VERSION
        );
    }

    /**
     * Enqueue Elementor editor styles
     */
    public function enqueue_editor_styles() {
        wp_enqueue_style(
            'pratikwp-elementor-editor',
            PRATIKWP_ASSETS . '/css/elementor-editor.css',
            [],
            PRATIKWP_VERSION
        );
    }

    /**
     * Enqueue Elementor preview styles
     */
    public function enqueue_preview_styles() {
        wp_enqueue_style(
            'pratikwp-elementor-preview',
            PRATIKWP_ASSETS . '/css/elementor-preview.css',
            [],
            PRATIKWP_VERSION
        );
    }

    /**
     * Initialize theme builder support
     */
    public function init_theme_builder_support() {
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }
        
        // Add theme support for Elementor Pro features
        add_theme_support('elementor');
        
        // Remove theme locations from default areas
        remove_action('wp_head', 'wp_generator');
        
        // Add custom post types support
        add_post_type_support('page', 'elementor');
        add_post_type_support('post', 'elementor');
    }

    /**
     * Modify archive query for Elementor
     */
    public function modify_archive_query($query_vars) {
        // Custom posts per page for archive
        if (get_theme_mod('posts_per_page_archive')) {
            $query_vars['posts_per_page'] = get_theme_mod('posts_per_page_archive', 10);
        }
        
        return $query_vars;
    }

    /**
     * Register custom controls
     */
    public function register_controls($controls_manager) {
        // Image Select Control
        require_once PRATIKWP_INC . '/elementor/controls/image-select.php';
        $controls_manager->register_control('image_select', new \PratikWp_Image_Select_Control());
        
        // Icon Select Control
        require_once PRATIKWP_INC . '/elementor/controls/icon-select.php';
        $controls_manager->register_control('icon_select', new \PratikWp_Icon_Select_Control());
    }

    /**
     * Check if Elementor is active
     */
    public static function is_elementor_active() {
        return did_action('elementor/loaded');
    }

    /**
     * Check if Elementor Pro is active
     */
    public static function is_elementor_pro_active() {
        return function_exists('elementor_pro_load_plugin');
    }

    /**
     * Check if current page is built with Elementor
     */
    public static function is_built_with_elementor($post_id = null) {
        if (!self::is_elementor_active()) {
            return false;
        }
        
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        return \Elementor\Plugin::$instance->documents->get($post_id)->is_built_with_elementor();
    }

    /**
     * Get Elementor page content
     */
    public static function get_elementor_content($post_id = null) {
        if (!self::is_elementor_active()) {
            return '';
        }
        
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        if (!self::is_built_with_elementor($post_id)) {
            return '';
        }
        
        return \Elementor\Plugin::$instance->frontend->get_builder_content($post_id);
    }

    /**
     * Register theme location conditions
     */
    public function register_location_conditions() {
        if (!self::is_elementor_pro_active()) {
            return;
        }
        
        // Custom conditions can be added here
        // For example: specific post types, custom taxonomies, etc.
    }

    /**
     * Add custom CSS for Elementor widgets
     */
    public static function add_custom_css() {
        ?>
        <style>
        /* PratikWp Elementor Widget Styles */
        .pratikwp-widget {
            margin-bottom: 1rem;
        }
        
        .pratikwp-logo img {
            max-width: 100%;
            height: auto;
        }
        
        .pratikwp-nav-menu ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .pratikwp-nav-menu li {
            display: inline-block;
            margin-right: 1rem;
        }
        
        .pratikwp-nav-menu a {
            text-decoration: none;
            padding: 0.5rem;
            display: block;
        }
        
        .pratikwp-social-media {
            display: flex;
            gap: 0.5rem;
        }
        
        .pratikwp-social-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-decoration: none;
            font-size: 1.2rem;
        }
        
        .pratikwp-company-info .info-item {
            margin-bottom: 0.5rem;
        }
        
        .pratikwp-company-info .info-label {
            font-weight: bold;
            margin-right: 0.5rem;
        }
        
        .pratikwp-posts-grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .pratikwp-posts-grid.cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .pratikwp-posts-grid.cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .pratikwp-posts-grid.cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }
        
        @media (max-width: 768px) {
            .pratikwp-posts-grid.cols-2,
            .pratikwp-posts-grid.cols-3,
            .pratikwp-posts-grid.cols-4 {
                grid-template-columns: 1fr;
            }
        }
        
        .pratikwp-post-item {
            border: 1px solid #e9ecef;
            border-radius: 0.375rem;
            overflow: hidden;
            transition: transform 0.2s;
        }
        
        .pratikwp-post-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .pratikwp-post-thumbnail img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        
        .pratikwp-post-content {
            padding: 1rem;
        }
        
        .pratikwp-post-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .pratikwp-post-title a {
            color: inherit;
            text-decoration: none;
        }
        
        .pratikwp-post-excerpt {
            color: #6c757d;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }
        
        .pratikwp-post-meta {
            font-size: 0.75rem;
            color: #adb5bd;
        }
        
        .pratikwp-breadcrumbs {
            font-size: 0.875rem;
        }
        
        .pratikwp-breadcrumbs a {
            color: var(--bs-primary);
            text-decoration: none;
        }
        
        .pratikwp-breadcrumbs .separator {
            margin: 0 0.5rem;
            color: #6c757d;
        }
        
        .pratikwp-search-form .search-input {
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }
        
        .pratikwp-search-form .search-button {
            background: var(--bs-primary);
            color: white;
            border: 1px solid var(--bs-primary);
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
        }
        
        .pratikwp-search-form .search-button:hover {
            background: var(--bs-primary);
            opacity: 0.9;
        }
        </style>
        <?php
    }

    /**
     * Get widget default settings
     */
    public static function get_widget_defaults($widget_name) {
        $defaults = [
            'site_logo' => [
                'max_width' => ['size' => 200, 'unit' => 'px'],
                'alignment' => 'left'
            ],
            'nav_menu' => [
                'menu' => '',
                'layout' => 'horizontal',
                'submenu_indicator' => 'arrow'
            ],
            'social_media' => [
                'style' => 'icon',
                'size' => 'medium',
                'layout' => 'horizontal'
            ],
            'company_info' => [
                'show_fields' => ['name', 'address', 'phone', 'email'],
                'layout' => 'vertical'
            ],
            'posts_grid' => [
                'posts_per_page' => 6,
                'columns' => 3,
                'show_thumbnail' => 'yes',
                'show_excerpt' => 'yes',
                'show_meta' => 'yes'
            ]
        ];
        
        return isset($defaults[$widget_name]) ? $defaults[$widget_name] : [];
    }

    /**
     * Add custom CSS classes to Elementor elements
     */
    public static function add_element_classes($element) {
        $element->add_render_attribute('_wrapper', 'class', 'pratikwp-element');
        
        if ($element->get_name() === 'widget') {
            $element->add_render_attribute('_wrapper', 'class', 'pratikwp-widget');
        }
    }
}