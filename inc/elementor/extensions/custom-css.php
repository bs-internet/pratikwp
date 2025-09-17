<?php
/**
 * Elementor Custom CSS Extension
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Custom CSS handler
 */
class PratikWp_Custom_CSS {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('elementor/element/after_section_end', [$this, 'add_custom_css_controls'], 10, 3);
        add_action('elementor/frontend/widget/before_render', [$this, 'add_widget_css']);
        add_action('elementor/frontend/section/before_render', [$this, 'add_section_css']);
        add_action('elementor/frontend/column/before_render', [$this, 'add_column_css']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_custom_css'], 999);
        add_action('elementor/css-file/post/enqueue', [$this, 'add_post_custom_css']);
    }

    /**
     * Add custom CSS controls to widgets and sections
     */
    public function add_custom_css_controls($element, $section_id, $args) {
        // Only add to advanced tab sections
        if ('section_custom_css' !== $section_id && 'section_custom_css_pro' !== $section_id) {
            return;
        }

        // Custom CSS section
        $element->start_controls_section(
            'pratikwp_custom_css_section',
            [
                'label' => __('PratikWp Özel CSS', 'pratikwp'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS textarea
        $element->add_control(
            'pratikwp_custom_css',
            [
                'label' => __('Özel CSS', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'render_type' => 'ui',
                'show_label' => false,
                'separator' => 'none',
                'description' => __('Bu elementa özel CSS ekleyin. {{WRAPPER}} kullanarak bu elementi hedefleyebilirsiniz.', 'pratikwp'),
                'default' => "/* CSS kodunuzu buraya yazın */\n{{WRAPPER}} {\n    \n}",
            ]
        );

        // CSS Minify option
        $element->add_control(
            'pratikwp_minify_css',
            [
                'label' => __('CSS Minify', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => __('CSS kodunu sıkıştırarak dosya boyutunu küçültür', 'pratikwp'),
            ]
        );

        // CSS Priority
        $element->add_control(
            'pratikwp_css_priority',
            [
                'label' => __('CSS Önceliği', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'normal',
                'options' => [
                    'high' => __('Yüksek (!important ekler)', 'pratikwp'),
                    'normal' => __('Normal', 'pratikwp'),
                    'low' => __('Düşük', 'pratikwp'),
                ],
            ]
        );

        // Responsive CSS
        $element->add_control(
            'pratikwp_responsive_css_heading',
            [
                'label' => __('Responsive CSS', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Desktop CSS
        $element->add_control(
            'pratikwp_desktop_css',
            [
                'label' => __('Desktop CSS', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'description' => __('Sadece desktop cihazlarda görünecek CSS', 'pratikwp'),
                'condition' => [
                    'pratikwp_custom_css!' => '',
                ],
            ]
        );

        // Tablet CSS
        $element->add_control(
            'pratikwp_tablet_css',
            [
                'label' => __('Tablet CSS', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'description' => __('Sadece tablet cihazlarda görünecek CSS', 'pratikwp'),
                'condition' => [
                    'pratikwp_custom_css!' => '',
                ],
            ]
        );

        // Mobile CSS
        $element->add_control(
            'pratikwp_mobile_css',
            [
                'label' => __('Mobile CSS', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'description' => __('Sadece mobil cihazlarda görünecek CSS', 'pratikwp'),
                'condition' => [
                    'pratikwp_custom_css!' => '',
                ],
            ]
        );

        // CSS Animation
        $element->add_control(
            'pratikwp_css_animation_heading',
            [
                'label' => __('CSS Animasyonları', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Custom Animation
        $element->add_control(
            'pratikwp_custom_animation',
            [
                'label' => __('Özel Animasyon', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::CODE,
                'language' => 'css',
                'description' => __('@keyframes ile özel animasyon tanımlayın', 'pratikwp'),
                'default' => "@keyframes customAnimation {\n    0% {\n        opacity: 0;\n        transform: translateY(20px);\n    }\n    100% {\n        opacity: 1;\n        transform: translateY(0);\n    }\n}",
            ]
        );

        // Animation Duration
        $element->add_control(
            'pratikwp_animation_duration',
            [
                'label' => __('Animasyon Süresi', 'pratikwp'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 's',
                    'size' => 0.5,
                ],
                'condition' => [
                    'pratikwp_custom_animation!' => '',
                ],
            ]
        );

        $element->end_controls_section();
    }

    /**
     * Add custom CSS to widgets
     */
    public function add_widget_css($widget) {
        $settings = $widget->get_settings_for_display();
        $css = $this->generate_element_css($widget, $settings);
        
        if (!empty($css)) {
            wp_add_inline_style('elementor-frontend', $css);
        }
    }

    /**
     * Add custom CSS to sections
     */
    public function add_section_css($section) {
        $settings = $section->get_settings_for_display();
        $css = $this->generate_element_css($section, $settings);
        
        if (!empty($css)) {
            wp_add_inline_style('elementor-frontend', $css);
        }
    }

    /**
     * Add custom CSS to columns
     */
    public function add_column_css($column) {
        $settings = $column->get_settings_for_display();
        $css = $this->generate_element_css($column, $settings);
        
        if (!empty($css)) {
            wp_add_inline_style('elementor-frontend', $css);
        }
    }

    /**
     * Generate CSS for element
     */
    private function generate_element_css($element, $settings) {
        $css = '';
        $element_id = $element->get_id();
        $wrapper_class = '.elementor-element-' . $element_id;

        // Main custom CSS
        if (!empty($settings['pratikwp_custom_css'])) {
            $custom_css = $settings['pratikwp_custom_css'];
            $custom_css = str_replace('{{WRAPPER}}', $wrapper_class, $custom_css);
            
            // Apply priority
            if (isset($settings['pratikwp_css_priority']) && $settings['pratikwp_css_priority'] === 'high') {
                $custom_css = $this->add_important_to_css($custom_css);
            }
            
            // Minify if enabled
            if (isset($settings['pratikwp_minify_css']) && $settings['pratikwp_minify_css'] === 'yes') {
                $custom_css = $this->minify_css($custom_css);
            }
            
            $css .= $custom_css;
        }

        // Responsive CSS
        $breakpoints = [
            'desktop' => '@media (min-width: 1025px)',
            'tablet' => '@media (max-width: 1024px) and (min-width: 768px)',
            'mobile' => '@media (max-width: 767px)',
        ];

        foreach ($breakpoints as $device => $media_query) {
            $device_css_key = 'pratikwp_' . $device . '_css';
            
            if (!empty($settings[$device_css_key])) {
                $device_css = $settings[$device_css_key];
                $device_css = str_replace('{{WRAPPER}}', $wrapper_class, $device_css);
                
                if (isset($settings['pratikwp_css_priority']) && $settings['pratikwp_css_priority'] === 'high') {
                    $device_css = $this->add_important_to_css($device_css);
                }
                
                if (isset($settings['pratikwp_minify_css']) && $settings['pratikwp_minify_css'] === 'yes') {
                    $device_css = $this->minify_css($device_css);
                }
                
                $css .= $media_query . ' { ' . $device_css . ' }';
            }
        }

        // Custom animations
        if (!empty($settings['pratikwp_custom_animation'])) {
            $animation_css = $settings['pratikwp_custom_animation'];
            
            // Add animation to wrapper
            $duration = isset($settings['pratikwp_animation_duration']['size']) ? 
                      $settings['pratikwp_animation_duration']['size'] . 's' : '0.5s';
            
            $css .= $wrapper_class . ' { animation: customAnimation ' . $duration . ' ease-in-out; }';
            $css .= $animation_css;
        }

        return $css;
    }

    /**
     * Add !important to CSS properties
     */
    private function add_important_to_css($css) {
        // Simple regex to add !important to CSS properties
        $css = preg_replace('/([^{}]+)\s*:\s*([^;{}]+);/', '$1: $2 !important;', $css);
        
        // Clean up any double !important
        $css = str_replace(' !important !important', ' !important', $css);
        
        return $css;
    }

    /**
     * Minify CSS
     */
    private function minify_css($css) {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove whitespace
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
        $css = preg_replace('/\s+/', ' ', $css);
        
        // Remove unnecessary spaces
        $css = str_replace(['; ', ' ;', '{ ', ' {', '} ', ' }', ': ', ' :'], [';', ';', '{', '{', '}', '}', ':', ':'], $css);
        
        return trim($css);
    }

    /**
     * Enqueue custom CSS
     */
    public function enqueue_custom_css() {
        // Global custom CSS from customizer
        $global_css = get_theme_mod('pratikwp_global_custom_css', '');
        
        if (!empty($global_css)) {
            wp_add_inline_style('elementor-frontend', $global_css);
        }

        // Page-specific CSS
        if (is_singular()) {
            $page_css = get_post_meta(get_the_ID(), '_pratikwp_page_custom_css', true);
            
            if (!empty($page_css)) {
                wp_add_inline_style('elementor-frontend', $page_css);
            }
        }
    }

    /**
     * Add post custom CSS to Elementor CSS file
     */
    public function add_post_custom_css($post_css) {
        $post_id = get_the_ID();
        
        if (!$post_id) {
            return;
        }

        // Get all elements with custom CSS
        $elements_css = get_post_meta($post_id, '_pratikwp_elements_css', true);
        
        if (empty($elements_css)) {
            return;
        }

        $css = '';
        foreach ($elements_css as $element_id => $element_css) {
            if (!empty($element_css)) {
                $css .= $element_css;
            }
        }

        if (!empty($css)) {
            $post_css->get_stylesheet()->add_raw_css($css);
        }
    }

    /**
     * Save element CSS to post meta for caching
     */
    public static function save_element_css($post_id, $element_id, $css) {
        $elements_css = get_post_meta($post_id, '_pratikwp_elements_css', true);
        
        if (!is_array($elements_css)) {
            $elements_css = [];
        }
        
        $elements_css[$element_id] = $css;
        
        update_post_meta($post_id, '_pratikwp_elements_css', $elements_css);
    }

    /**
     * Clear element CSS cache
     */
    public static function clear_css_cache($post_id = null) {
        if ($post_id) {
            delete_post_meta($post_id, '_pratikwp_elements_css');
        } else {
            // Clear all
            global $wpdb;
            $wpdb->delete(
                $wpdb->postmeta,
                ['meta_key' => '_pratikwp_elements_css']
            );
        }
    }

    /**
     * Get CSS snippets library
     */
    public static function get_css_snippets() {
        return [
            'hover-effects' => [
                'title' => __('Hover Efektleri', 'pratikwp'),
                'snippets' => [
                    'scale-hover' => [
                        'name' => __('Hover Büyütme', 'pratikwp'),
                        'css' => "{{WRAPPER}}:hover {\n    transform: scale(1.05);\n    transition: transform 0.3s ease;\n}",
                    ],
                    'rotate-hover' => [
                        'name' => __('Hover Döndürme', 'pratikwp'),
                        'css' => "{{WRAPPER}}:hover {\n    transform: rotate(5deg);\n    transition: transform 0.3s ease;\n}",
                    ],
                ],
            ],
            'animations' => [
                'title' => __('Animasyonlar', 'pratikwp'),
                'snippets' => [
                    'fade-in' => [
                        'name' => __('Fade In', 'pratikwp'),
                        'css' => "@keyframes fadeIn {\n    from { opacity: 0; }\n    to { opacity: 1; }\n}\n\n{{WRAPPER}} {\n    animation: fadeIn 1s ease-in;\n}",
                    ],
                    'slide-up' => [
                        'name' => __('Slide Up', 'pratikwp'),
                        'css' => "@keyframes slideUp {\n    from {\n        opacity: 0;\n        transform: translateY(30px);\n    }\n    to {\n        opacity: 1;\n        transform: translateY(0);\n    }\n}\n\n{{WRAPPER}} {\n    animation: slideUp 0.6s ease-out;\n}",
                    ],
                ],
            ],
            'layout' => [
                'title' => __('Layout', 'pratikwp'),
                'snippets' => [
                    'center-content' => [
                        'name' => __('İçeriği Ortala', 'pratikwp'),
                        'css' => "{{WRAPPER}} {\n    display: flex;\n    align-items: center;\n    justify-content: center;\n}",
                    ],
                    'sticky-element' => [
                        'name' => __('Yapışkan Element', 'pratikwp'),
                        'css' => "{{WRAPPER}} {\n    position: sticky;\n    top: 20px;\n    z-index: 999;\n}",
                    ],
                ],
            ],
        ];
    }
}