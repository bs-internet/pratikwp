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
    }

    /**
     * Register Elementor theme locations - TEMEL LOKASYONLAR
     */
    public function register_locations($elementor_theme_manager) {
        $elementor_theme_manager->register_location('header');
        $elementor_theme_manager->register_location('footer'); 
        $elementor_theme_manager->register_location('single');
        $elementor_theme_manager->register_location('archive');
    }

    /**
     * Register custom widgets - SADECE 6 TEMEL WİDGET
     */
    public function register_widgets($widgets_manager) {
        // 1. Site Logo Widget
        require_once PRATIKWP_INC . '/elementor/widgets/site-logo.php';
        if (class_exists('PratikWp_Site_Logo_Widget')) {
            $widgets_manager->register_widget_type(new \PratikWp_Site_Logo_Widget());
        }
        
        // 2. Navigation Menu Widget  
        require_once PRATIKWP_INC . '/elementor/widgets/nav-menu.php';
        if (class_exists('PratikWp_Nav_Menu_Widget')) {
            $widgets_manager->register_widget_type(new \PratikWp_Nav_Menu_Widget());
        }
        
        // 3. Breadcrumbs Widget
        require_once PRATIKWP_INC . '/elementor/widgets/breadcrumbs.php';
        if (class_exists('PratikWp_Breadcrumbs_Widget')) {
            $widgets_manager->register_widget_type(new \PratikWp_Breadcrumbs_Widget());
        }
        
        // 4. Company Info Widget (GEREKSİNİM)
        require_once PRATIKWP_INC . '/elementor/widgets/company-info.php';
        if (class_exists('PratikWp_Company_Info_Widget')) {
            $widgets_manager->register_widget_type(new \PratikWp_Company_Info_Widget());
        }
        
        // 5. Social Media Widget (GEREKSİNİM)
        require_once PRATIKWP_INC . '/elementor/widgets/social-media.php';
        if (class_exists('PratikWp_Social_Media_Widget')) {
            $widgets_manager->register_widget_type(new \PratikWp_Social_Media_Widget());
        }
        
        // 6. Slider Widget (GEREKSİNİM)
        require_once PRATIKWP_INC . '/elementor/widgets/slider.php';
        if (class_exists('PratikWp_Slider_Widget')) {
            $widgets_manager->register_widget_type(new \PratikWp_Slider_Widget());
        }
    }

    /**
     * Register widget categories - BASİT KATEGORİ
     */
    public function register_widget_categories($elements_manager) {
        $elements_manager->add_category('pratikwp-theme', [
            'title' => __('PratikWp', 'pratikwp'),
            'icon' => 'fa fa-plug',
        ]);
    }

    /**
     * Enqueue Elementor styles - TEK CSS DOSYASI
     */
    public function enqueue_elementor_styles() {
        wp_enqueue_style(
            'pratikwp-elementor',
            PRATIKWP_ASSETS . '/css/elementor.css',
            [],
            PRATIKWP_VERSION
        );
    }
}