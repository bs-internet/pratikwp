<?php
/**
 * Elementor Theme Locations Extension
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Theme Locations handler
 */
class PratikWp_Theme_Locations {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('elementor/theme/register_locations', [$this, 'register_locations']);
        add_action('elementor/documents/register', [$this, 'register_document_types']);
        add_action('elementor/frontend/before_render', [$this, 'apply_location_css']);
        add_filter('elementor/theme/get_location_templates/template_id', [$this, 'get_template_id'], 10, 2);
        add_action('wp', [$this, 'override_theme_templates']);
    }

    /**
     * Register theme locations
     */
    public function register_locations($elementor_theme_manager) {
        $locations = [
            'header' => [
                'label' => __('Header', 'pratikwp'),
                'multiple' => true,
                'edit_in_content' => false,
            ],
            'footer' => [
                'label' => __('Footer', 'pratikwp'),
                'multiple' => true,
                'edit_in_content' => false,
            ],
            'single' => [
                'label' => __('Single Post', 'pratikwp'),
                'multiple' => true,
                'edit_in_content' => true,
            ],
            'page' => [
                'label' => __('Single Page', 'pratikwp'),
                'multiple' => true,
                'edit_in_content' => true,
            ],
            'archive' => [
                'label' => __('Archive', 'pratikwp'),
                'multiple' => true,
                'edit_in_content' => true,
            ],
            'search' => [
                'label' => __('Search Results', 'pratikwp'),
                'multiple' => false,
                'edit_in_content' => true,
            ],
            '404' => [
                'label' => __('404 Error', 'pratikwp'),
                'multiple' => false,
                'edit_in_content' => true,
            ],
        ];

        foreach ($locations as $location => $settings) {
            $elementor_theme_manager->register_location($location, $settings);
        }
    }

    /**
     * Register custom document types
     */
    public function register_document_types() {
        // Custom post type for theme templates
        $post_type_args = [
            'public' => false,
            'show_in_menu' => false,
            'show_in_admin_bar' => false,
            'rewrite' => false,
            'show_ui' => true,
            'can_export' => true,
            'supports' => ['title', 'elementor'],
            'capability_type' => 'page',
            'map_meta_cap' => true,
        ];

        if (!post_type_exists('pratikwp_template')) {
            register_post_type('pratikwp_template', $post_type_args);
        }
    }

    /**
     * Apply location-specific CSS
     */
    public function apply_location_css($element) {
        if (!$element instanceof \Elementor\Core\DocumentTypes\PageBase) {
            return;
        }

        $template_type = get_post_meta($element->get_main_id(), '_elementor_template_type', true);
        
        if (empty($template_type)) {
            return;
        }

        // Add body class for template type
        add_filter('body_class', function($classes) use ($template_type) {
            $classes[] = 'pratikwp-' . $template_type . '-template';
            return $classes;
        });

        // Location-specific styles
        $this->enqueue_location_styles($template_type);
    }

    /**
     * Enqueue location-specific styles
     */
    private function enqueue_location_styles($template_type) {
        $styles = [
            'header' => [
                '.pratikwp-header-template' => 'position: sticky; top: 0; z-index: 999;',
                '.pratikwp-header-template .elementor-section' => 'margin: 0;',
            ],
            'footer' => [
                '.pratikwp-footer-template' => 'margin-top: auto;',
                'body' => 'display: flex; flex-direction: column; min-height: 100vh;',
            ],
            'single' => [
                '.pratikwp-single-template .elementor-section' => 'margin-bottom: 0;',
            ],
            'archive' => [
                '.pratikwp-archive-template' => 'padding: 0;',
            ],
            '404' => [
                '.pratikwp-404-template' => 'text-align: center; padding: 60px 0;',
            ],
        ];

        if (isset($styles[$template_type])) {
            $css = '';
            foreach ($styles[$template_type] as $selector => $rules) {
                $css .= $selector . ' { ' . $rules . ' }';
            }
            
            wp_add_inline_style('elementor-frontend', $css);
        }
    }

    /**
     * Get template ID for location
     */
    public function get_template_id($template_id, $location) {
        // Check for conditional templates
        $conditions = $this->get_current_conditions();
        $templates = $this->get_location_templates($location);

        foreach ($templates as $template) {
            if ($this->check_template_conditions($template['id'], $conditions)) {
                return $template['id'];
            }
        }

        return $template_id;
    }

    /**
     * Get location templates
     */
    private function get_location_templates($location) {
        $templates = get_posts([
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => '_elementor_template_type',
                    'value' => $location,
                    'compare' => '=',
                ],
            ],
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        $formatted_templates = [];
        foreach ($templates as $template) {
            $formatted_templates[] = [
                'id' => $template->ID,
                'title' => $template->post_title,
                'conditions' => get_post_meta($template->ID, '_pratikwp_template_conditions', true),
            ];
        }

        return $formatted_templates;
    }

    /**
     * Get current page conditions
     */
    private function get_current_conditions() {
        $conditions = [];

        // Page type conditions
        if (is_front_page()) {
            $conditions[] = 'front_page';
        } elseif (is_home()) {
            $conditions[] = 'blog_page';
        } elseif (is_singular()) {
            $conditions[] = 'singular';
            $conditions[] = 'singular:' . get_post_type();
            $conditions[] = 'singular:' . get_post_type() . ':' . get_the_ID();
        } elseif (is_archive()) {
            $conditions[] = 'archive';
            if (is_category()) {
                $conditions[] = 'category';
                $conditions[] = 'category:' . get_queried_object_id();
            } elseif (is_tag()) {
                $conditions[] = 'tag';
                $conditions[] = 'tag:' . get_queried_object_id();
            } elseif (is_author()) {
                $conditions[] = 'author';
                $conditions[] = 'author:' . get_queried_object_id();
            } elseif (is_date()) {
                $conditions[] = 'date';
            }
        } elseif (is_search()) {
            $conditions[] = 'search';
        } elseif (is_404()) {
            $conditions[] = '404';
        }

        // User conditions
        if (is_user_logged_in()) {
            $conditions[] = 'logged_in';
            $user = wp_get_current_user();
            foreach ($user->roles as $role) {
                $conditions[] = 'user_role:' . $role;
            }
        } else {
            $conditions[] = 'logged_out';
        }

        // Device conditions
        if (wp_is_mobile()) {
            $conditions[] = 'mobile';
        } else {
            $conditions[] = 'desktop';
        }

        return apply_filters('pratikwp_template_conditions', $conditions);
    }

    /**
     * Check if template conditions match current page
     */
    private function check_template_conditions($template_id, $current_conditions) {
        $template_conditions = get_post_meta($template_id, '_pratikwp_template_conditions', true);
        
        if (empty($template_conditions)) {
            return true; // No conditions means show everywhere
        }

        foreach ($template_conditions as $condition_group) {
            $match = true;
            
            foreach ($condition_group as $condition) {
                if (!in_array($condition, $current_conditions)) {
                    $match = false;
                    break;
                }
            }
            
            if ($match) {
                return true;
            }
        }

        return false;
    }

    /**
     * Override theme templates with Elementor templates
     */
    public function override_theme_templates() {
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }

        // Get current location
        $location = $this->get_current_location();
        
        if (empty($location)) {
            return;
        }

        // Get template for location
        $template_id = \Elementor\Plugin::$instance->modules_manager->get_modules('theme-builder')->get_conditions_manager()->get_documents_for_location($location);
        
        if (empty($template_id)) {
            return;
        }

        // Override template actions
        $this->setup_template_hooks($location);
    }

    /**
     * Get current location based on page type
     */
    private function get_current_location() {
        if (is_singular()) {
            if (is_page()) {
                return 'page';
            }
            return 'single';
        } elseif (is_archive() || is_home()) {
            return 'archive';
        } elseif (is_search()) {
            return 'search';
        } elseif (is_404()) {
            return '404';
        }

        return '';
    }

    /**
     * Setup template hooks for location
     */
    private function setup_template_hooks($location) {
        switch ($location) {
            case 'header':
                remove_action('pratikwp_header', 'pratikwp_header_template');
                add_action('pratikwp_header', [$this, 'render_elementor_header']);
                break;

            case 'footer':
                remove_action('pratikwp_footer', 'pratikwp_footer_template');
                add_action('pratikwp_footer', [$this, 'render_elementor_footer']);
                break;

            case 'single':
            case 'page':
                add_filter('the_content', [$this, 'render_elementor_content']);
                break;

            case 'archive':
                add_action('pratikwp_archive_content', [$this, 'render_elementor_archive']);
                break;

            case 'search':
                add_action('pratikwp_search_content', [$this, 'render_elementor_search']);
                break;

            case '404':
                add_action('pratikwp_404_content', [$this, 'render_elementor_404']);
                break;
        }
    }

    /**
     * Render Elementor template for header
     */
    public function render_elementor_header() {
        $template_id = \Elementor\Plugin::$instance->modules_manager->get_modules('theme-builder')->get_conditions_manager()->get_documents_for_location('header');
        
        if ($template_id) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id[0]);
        }
    }

    /**
     * Render Elementor template for footer
     */
    public function render_elementor_footer() {
        $template_id = \Elementor\Plugin::$instance->modules_manager->get_modules('theme-builder')->get_conditions_manager()->get_documents_for_location('footer');
        
        if ($template_id) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id[0]);
        }
    }

    /**
     * Render Elementor content
     */
    public function render_elementor_content($content) {
        if (is_singular() && \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID())) {
            return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display(get_the_ID());
        }
        
        return $content;
    }

    /**
     * Render Elementor archive template
     */
    public function render_elementor_archive() {
        $template_id = \Elementor\Plugin::$instance->modules_manager->get_modules('theme-builder')->get_conditions_manager()->get_documents_for_location('archive');
        
        if ($template_id) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id[0]);
        }
    }

    /**
     * Render Elementor search template
     */
    public function render_elementor_search() {
        $template_id = \Elementor\Plugin::$instance->modules_manager->get_modules('theme-builder')->get_conditions_manager()->get_documents_for_location('search');
        
        if ($template_id) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id[0]);
        }
    }

    /**
     * Render Elementor 404 template
     */
    public function render_elementor_404() {
        $template_id = \Elementor\Plugin::$instance->modules_manager->get_modules('theme-builder')->get_conditions_manager()->get_documents_for_location('404');
        
        if ($template_id) {
            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template_id[0]);
        }
    }
}