<?php
/**
 * Footer Layout Section for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Footer_Layout_Section {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_section']);
    }
    
    public function register_section($wp_customize) {
        // Footer Layout Section
        $wp_customize->add_section('pratikwp_footer_layout_section', [
            'title' => __('Footer Layout Builder', 'pratikwp'),
            'priority' => 45,
            'capability' => 'edit_theme_options',
            'description' => __('Footer düzenini özelleştirin ve bileşenleri organize edin', 'pratikwp'),
        ]);
        
        // Footer Layout Templates
        $this->add_layout_templates($wp_customize);
        
        // Footer Components
        $this->add_footer_components($wp_customize);
        
        // Footer Rows
        $this->add_footer_rows($wp_customize);
        
        // Responsive Settings
        $this->add_responsive_settings($wp_customize);
        
        // Advanced Layout Settings
        $this->add_advanced_layout_settings($wp_customize);
    }
    
    /**
     * Footer Layout Templates
     */
    private function add_layout_templates($wp_customize) {
        // Layout Templates Heading
        $wp_customize->add_setting('footer_layout_templates_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_layout_templates_heading', [
            'label' => __('Footer Layout Şablonları', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'hidden',
        ]));
        
        // Predefined Layout Template
        $wp_customize->add_setting('footer_layout_template', [
            'default' => 'classic',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_layout_template', [
            'label' => __('Hazır Layout Şablonu', 'pratikwp'),
            'description' => __('Hızlı başlangıç için hazır şablonlardan birini seçin', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'select',
            'choices' => [
                'classic' => __('Classic - 4 Eşit Kolon + Copyright', 'pratikwp'),
                'modern' => __('Modern - 3 Kolon + Sosyal Medya', 'pratikwp'),
                'minimal' => __('Minimal - Tek Kolon Orta', 'pratikwp'),
                'business' => __('Business - Company Info + Links', 'pratikwp'),
                'magazine' => __('Magazine - Çoklu Widget Area', 'pratikwp'),
                'contact' => __('Contact - İletişim Odaklı', 'pratikwp'),
                'custom' => __('Özel - Manuel Düzen', 'pratikwp'),
            ],
        ]);
        
        // Template Preview
        $wp_customize->add_setting('footer_template_preview', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_template_preview', [
            'description' => __('Seçili şablonun önizlemesi burada görünecek', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'hidden',
        ]));
    }
    
    /**
     * Footer Components
     */
    private function add_footer_components($wp_customize) {
        // Components Heading
        $wp_customize->add_setting('footer_components_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_components_heading', [
            'label' => __('Footer Bileşenleri', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'hidden',
        ]));
        
        // Available Components
        $components = [
            'widgets' => 'Widget Alanları',
            'logo' => 'Site Logosu',
            'menu' => 'Footer Menüsü',
            'social' => 'Sosyal Medya',
            'contact' => 'İletişim Bilgileri',
            'newsletter' => 'Newsletter',
            'search' => 'Arama Kutusu',
            'recent_posts' => 'Son Yazılar',
            'categories' => 'Kategoriler',
            'copyright' => 'Copyright Metni',
        ];
        
        foreach ($components as $key => $label) {
            $wp_customize->add_setting("footer_show_{$key}", [
                'default' => in_array($key, ['widgets', 'copyright']),
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control("footer_show_{$key}", [
                'label' => $label,
                'section' => 'pratikwp_footer_layout_section',
                'type' => 'checkbox',
            ]);
        }
    }
    
    /**
     * Footer Rows Configuration
     */
    private function add_footer_rows($wp_customize) {
        // Rows Heading
        $wp_customize->add_setting('footer_rows_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_rows_heading', [
            'label' => __('Footer Satırları', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'hidden',
        ]));
        
        // Number of Rows
        $wp_customize->add_setting('footer_rows_count', [
            'default' => 2,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_rows_count', [
            'label' => __('Footer Satır Sayısı', 'pratikwp'),
            'description' => __('1-4 arası footer satırı oluşturabilirsiniz', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'number',
            'input_attrs' => [
                'min' => 1,
                'max' => 4,
                'step' => 1,
            ],
        ]);
        
        // Row Configurations
        for ($i = 1; $i <= 4; $i++) {
            // Row Enable/Disable
            $wp_customize->add_setting("footer_row_{$i}_enable", [
                'default' => $i <= 2,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control("footer_row_{$i}_enable", [
                'label' => sprintf(__('Satır %d Aktif', 'pratikwp'), $i),
                'section' => 'pratikwp_footer_layout_section',
                'type' => 'checkbox',
            ]);
            
            // Row Columns
            $wp_customize->add_setting("footer_row_{$i}_columns", [
                'default' => $i == 1 ? 4 : 1,
                'sanitize_callback' => 'absint',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control("footer_row_{$i}_columns", [
                'label' => sprintf(__('Satır %d Kolon Sayısı', 'pratikwp'), $i),
                'section' => 'pratikwp_footer_layout_section',
                'type' => 'select',
                'choices' => [
                    1 => '1 Kolon',
                    2 => '2 Kolon',
                    3 => '3 Kolon',
                    4 => '4 Kolon',
                    5 => '5 Kolon',
                    6 => '6 Kolon',
                ],
                'active_callback' => function() use ($i) {
                    return get_theme_mod("footer_row_{$i}_enable", $i <= 2);
                },
            ]);
            
            // Row Column Widths
            $wp_customize->add_setting("footer_row_{$i}_widths", [
                'default' => 'equal',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control("footer_row_{$i}_widths", [
                'label' => sprintf(__('Satır %d Kolon Genişlikleri', 'pratikwp'), $i),
                'section' => 'pratikwp_footer_layout_section',
                'type' => 'select',
                'choices' => [
                    'equal' => 'Eşit Genişlik',
                    '2-1' => '2/3 - 1/3',
                    '1-2' => '1/3 - 2/3',
                    '1-1-2' => '1/4 - 1/4 - 1/2',
                    '2-1-1' => '1/2 - 1/4 - 1/4',
                    'custom' => 'Özel Genişlik',
                ],
                'active_callback' => function() use ($i) {
                    return get_theme_mod("footer_row_{$i}_enable", $i <= 2) && 
                           get_theme_mod("footer_row_{$i}_columns", $i == 1 ? 4 : 1) > 1;
                },
            ]);
            
            // Custom Widths Input
            $wp_customize->add_setting("footer_row_{$i}_custom_widths", [
                'default' => '',
                'sanitize_callback' => 'sanitize_text_field',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control("footer_row_{$i}_custom_widths", [
                'label' => sprintf(__('Satır %d Özel Genişlikler', 'pratikwp'), $i),
                'description' => __('Bootstrap grid sistemi kullanın (örn: 6,3,3 veya 4,4,2,2)', 'pratikwp'),
                'section' => 'pratikwp_footer_layout_section',
                'type' => 'text',
                'active_callback' => function() use ($i) {
                    return get_theme_mod("footer_row_{$i}_enable", $i <= 2) && 
                           get_theme_mod("footer_row_{$i}_widths", 'equal') === 'custom';
                },
            ]);
        }
    }
    
    /**
     * Responsive Settings
     */
    private function add_responsive_settings($wp_customize) {
        // Responsive Heading
        $wp_customize->add_setting('footer_responsive_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_responsive_heading', [
            'label' => __('Responsive Ayarları', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'hidden',
        ]));
        
        // Mobile Stack Columns
        $wp_customize->add_setting('footer_mobile_stack', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_mobile_stack', [
            'label' => __('Mobilde Kolonları Alt Alta Sırala', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'checkbox',
        ]);
        
        // Mobile Stack Breakpoint
        $wp_customize->add_setting('footer_mobile_breakpoint', [
            'default' => 768,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_mobile_breakpoint', [
            'label' => __('Mobil Breakpoint (px)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'number',
            'input_attrs' => [
                'min' => 320,
                'max' => 1024,
                'step' => 1,
            ],
        ]);
        
        // Hide Elements on Mobile
        $wp_customize->add_setting('footer_mobile_hide_elements', [
            'default' => [],
            'sanitize_callback' => [$this, 'sanitize_multiselect'],
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_mobile_hide_elements', [
            'label' => __('Mobilde Gizlenecek Bileşenler', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'select',
            'input_attrs' => ['multiple' => true],
            'choices' => [
                'widgets' => 'Widget Alanları',
                'social' => 'Sosyal Medya',
                'menu' => 'Footer Menüsü',
                'contact' => 'İletişim Bilgileri',
                'newsletter' => 'Newsletter',
            ],
        ]));
    }
    
    /**
     * Advanced Layout Settings
     */
    private function add_advanced_layout_settings($wp_customize) {
        // Advanced Heading
        $wp_customize->add_setting('footer_advanced_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'footer_advanced_heading', [
            'label' => __('Gelişmiş Layout Ayarları', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'hidden',
        ]));
        
        // Footer Container Type
        $wp_customize->add_setting('footer_container_type', [
            'default' => 'container',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_container_type', [
            'label' => __('Container Tipi', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'select',
            'choices' => [
                'container' => 'Container (Sınırlı Genişlik)',
                'container-fluid' => 'Container Fluid (Tam Genişlik)',
                'custom' => 'Özel Genişlik',
            ],
        ]);
        
        // Custom Container Width
        $wp_customize->add_setting('footer_custom_width', [
            'default' => 1200,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_custom_width', [
            'label' => __('Özel Container Genişliği (px)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'number',
            'input_attrs' => [
                'min' => 320,
                'max' => 1920,
                'step' => 10,
            ],
            'active_callback' => function() {
                return get_theme_mod('footer_container_type', 'container') === 'custom';
            },
        ]);
        
        // Footer Padding
        $wp_customize->add_setting('footer_padding_top', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_padding_top', [
            'label' => __('Üst Padding (px)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'range',
            'input_attrs' => [
                'min' => 0,
                'max' => 120,
                'step' => 5,
            ],
        ]);
        
        $wp_customize->add_setting('footer_padding_bottom', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_padding_bottom', [
            'label' => __('Alt Padding (px)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'range',
            'input_attrs' => [
                'min' => 0,
                'max' => 120,
                'step' => 5,
            ],
        ]);
        
        // Row Spacing
        $wp_customize->add_setting('footer_row_spacing', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_row_spacing', [
            'label' => __('Satırlar Arası Boşluk (px)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'range',
            'input_attrs' => [
                'min' => 0,
                'max' => 80,
                'step' => 5,
            ],
        ]);
        
        // Column Spacing
        $wp_customize->add_setting('footer_column_spacing', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_column_spacing', [
            'label' => __('Kolonlar Arası Boşluk (px)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'range',
            'input_attrs' => [
                'min' => 15,
                'max' => 60,
                'step' => 5,
            ],
        ]);
        
        // Footer Dividers
        $wp_customize->add_setting('footer_show_dividers', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_show_dividers', [
            'label' => __('Satırlar Arası Ayırıcı Çizgi', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'type' => 'checkbox',
        ]);
        
        // Divider Color
        $wp_customize->add_setting('footer_divider_color', [
            'default' => '#444444',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_divider_color', [
            'label' => __('Ayırıcı Çizgi Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_layout_section',
            'active_callback' => function() {
                return get_theme_mod('footer_show_dividers', false);
            },
        ]));
    }
    
    /**
     * Sanitize multiselect values
     */
    public function sanitize_multiselect($values) {
        if (is_array($values)) {
            return array_map('sanitize_text_field', $values);
        }
        return [];
    }
}

// Initialize the class
new PratikWp_Customizer_Footer_Layout_Section();