<?php
/**
 * Footer Panel for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Footer_Panel {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_panel']);
    }
    
    public function register_panel($wp_customize) {
        // Footer Panel
        $wp_customize->add_panel('pratikwp_footer_panel', [
            'title' => __('Footer Ayarları', 'pratikwp'),
            'description' => __('Footer bölümü görünüm ve içerik ayarları', 'pratikwp'),
            'priority' => 40,
            'capability' => 'edit_theme_options',
        ]);
        
        // Footer Layout Section
        $this->add_footer_layout_section($wp_customize);
        
        // Footer Widgets Section
        $this->add_footer_widgets_section($wp_customize);
        
        // Footer Styling Section
        $this->add_footer_styling_section($wp_customize);
        
        // Footer Copyright Section
        $this->add_footer_copyright_section($wp_customize);
        
        // Footer Social Section
        $this->add_footer_social_section($wp_customize);
    }
    
    /**
     * Footer Layout Section
     */
    private function add_footer_layout_section($wp_customize) {
        $wp_customize->add_section('pratikwp_footer_layout', [
            'title' => __('Footer Düzeni', 'pratikwp'),
            'panel' => 'pratikwp_footer_panel',
            'priority' => 10,
        ]);
        
        // Footer Layout Type
        $wp_customize->add_setting('footer_layout_type', [
            'default' => 'layout_1',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_layout_type', [
            'label' => __('Footer Düzen Tipi', 'pratikwp'),
            'section' => 'pratikwp_footer_layout',
            'type' => 'select',
            'choices' => [
                'layout_1' => __('Layout 1 - 4 Kolon Eşit', 'pratikwp'),
                'layout_2' => __('Layout 2 - 3 Kolon Eşit', 'pratikwp'),
                'layout_3' => __('Layout 3 - 2 Kolon Eşit', 'pratikwp'),
                'layout_4' => __('Layout 4 - 2/1 Oranında', 'pratikwp'),
                'layout_5' => __('Layout 5 - 1/2 Oranında', 'pratikwp'),
                'layout_6' => __('Layout 6 - Tek Kolon', 'pratikwp'),
                'custom' => __('Özel Düzen', 'pratikwp'),
            ],
        ]);
        
        // Custom Column Widths (only for custom layout)
        $wp_customize->add_setting('footer_custom_columns', [
            'default' => '3,3,3,3',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_custom_columns', [
            'label' => __('Özel Kolon Genişlikleri', 'pratikwp'),
            'description' => __('Virgülle ayrılmış değerler (örn: 4,4,2,2)', 'pratikwp'),
            'section' => 'pratikwp_footer_layout',
            'type' => 'text',
            'active_callback' => function() {
                return get_theme_mod('footer_layout_type', 'layout_1') === 'custom';
            },
        ]);
        
        // Footer Width
        $wp_customize->add_setting('footer_width', [
            'default' => 'container',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_width', [
            'label' => __('Footer Genişliği', 'pratikwp'),
            'section' => 'pratikwp_footer_layout',
            'type' => 'select',
            'choices' => [
                'container' => __('Container (Sınırlı)', 'pratikwp'),
                'container-fluid' => __('Tam Genişlik', 'pratikwp'),
            ],
        ]);
        
        // Footer Reveal Effect
        $wp_customize->add_setting('footer_reveal_effect', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'footer_reveal_effect', [
            'label' => __('Footer Reveal Efekti', 'pratikwp'),
            'description' => __('Sayfa kaydırıldığında footer alttan çıkar', 'pratikwp'),
            'section' => 'pratikwp_footer_layout',
        ]));
        
        // Footer Sticky Bottom
        $wp_customize->add_setting('footer_sticky_bottom', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'footer_sticky_bottom', [
            'label' => __('Footer Sabit Alt', 'pratikwp'),
            'description' => __('Kısa sayfalarda footer en altta kalır', 'pratikwp'),
            'section' => 'pratikwp_footer_layout',
        ]));
    }
    
    /**
     * Footer Widgets Section
     */
    private function add_footer_widgets_section($wp_customize) {
        $wp_customize->add_section('pratikwp_footer_widgets', [
            'title' => __('Footer Widget\'ları', 'pratikwp'),
            'panel' => 'pratikwp_footer_panel',
            'priority' => 20,
        ]);
        
        // Show Footer Widgets
        $wp_customize->add_setting('show_footer_widgets', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'show_footer_widgets', [
            'label' => __('Footer Widget\'larını Göster', 'pratikwp'),
            'section' => 'pratikwp_footer_widgets',
        ]));
        
        // Widget Area Padding
        $wp_customize->add_setting('footer_widget_padding', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'footer_widget_padding', [
            'label' => __('Widget Alanı Padding', 'pratikwp'),
            'section' => 'pratikwp_footer_widgets',
            'min' => 20,
            'max' => 120,
            'step' => 10,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('show_footer_widgets', true);
            },
        ]));
        
        // Widget Title Typography
        $wp_customize->add_setting('footer_widget_title_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 18,
                'font_weight' => '600',
                'line_height' => 1.4,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'footer_widget_title_typography', [
            'label' => __('Widget Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_footer_widgets',
            'show_line_height' => true,
            'show_letter_spacing' => false,
            'show_text_transform' => true,
            'preview_text' => __('Widget Başlığı', 'pratikwp'),
            'active_callback' => function() {
                return get_theme_mod('show_footer_widgets', true);
            },
        ]));
        
        // Widget Content Typography
        $wp_customize->add_setting('footer_widget_content_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 14,
                'font_weight' => '400',
                'line_height' => 1.6,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'footer_widget_content_typography', [
            'label' => __('Widget İçerik Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_footer_widgets',
            'show_line_height' => true,
            'show_letter_spacing' => false,
            'show_text_transform' => false,
            'preview_text' => __('Widget içerik metni örneği', 'pratikwp'),
            'active_callback' => function() {
                return get_theme_mod('show_footer_widgets', true);
            },
        ]));
        
        // Widget Link Color
        $wp_customize->add_setting('footer_widget_link_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_widget_link_color', [
            'label' => __('Widget Link Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_widgets',
            'active_callback' => function() {
                return get_theme_mod('show_footer_widgets', true);
            },
        ]));
        
        // Widget Link Hover Color
        $wp_customize->add_setting('footer_widget_link_hover_color', [
            'default' => '#cccccc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_widget_link_hover_color', [
            'label' => __('Widget Link Hover Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_widgets',
            'active_callback' => function() {
                return get_theme_mod('show_footer_widgets', true);
            },
        ]));
    }
    
    /**
     * Footer Styling Section
     */
    private function add_footer_styling_section($wp_customize) {
        $wp_customize->add_section('pratikwp_footer_styling', [
            'title' => __('Footer Stilleri', 'pratikwp'),
            'panel' => 'pratikwp_footer_panel',
            'priority' => 30,
        ]);
        
        // Footer Background Type
        $wp_customize->add_setting('footer_background_type', [
            'default' => 'color',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_background_type', [
            'label' => __('Footer Arka Plan Tipi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
            'type' => 'select',
            'choices' => [
                'color' => __('Düz Renk', 'pratikwp'),
                'gradient' => __('Gradient', 'pratikwp'),
                'image' => __('Resim', 'pratikwp'),
            ],
        ]);
        
        // Footer Background Color
        $wp_customize->add_setting('footer_background_color', [
            'default' => '#2c3e50',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color', [
            'label' => __('Footer Arka Plan Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
            'active_callback' => function() {
                return get_theme_mod('footer_background_type', 'color') === 'color';
            },
        ]));
        
        // Footer Gradient Start Color
        $wp_customize->add_setting('footer_gradient_start', [
            'default' => '#2c3e50',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_gradient_start', [
            'label' => __('Gradient Başlangıç Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
            'active_callback' => function() {
                return get_theme_mod('footer_background_type', 'color') === 'gradient';
            },
        ]));
        
        // Footer Gradient End Color
        $wp_customize->add_setting('footer_gradient_end', [
            'default' => '#34495e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_gradient_end', [
            'label' => __('Gradient Bitiş Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
            'active_callback' => function() {
                return get_theme_mod('footer_background_type', 'color') === 'gradient';
            },
        ]));
        
        // Footer Background Image
        $wp_customize->add_setting('footer_background_image', [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_background_image', [
            'label' => __('Footer Arka Plan Resmi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
            'active_callback' => function() {
                return get_theme_mod('footer_background_type', 'color') === 'image';
            },
        ]));
        
        // Footer Text Color
        $wp_customize->add_setting('footer_text_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_text_color', [
            'label' => __('Footer Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
        ]));
        
        // Footer Border Top
        $wp_customize->add_setting('footer_border_top', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'footer_border_top', [
            'label' => __('Üst Kenarlık', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
        ]));
        
        // Footer Border Color
        $wp_customize->add_setting('footer_border_color', [
            'default' => '#34495e',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_border_color', [
            'label' => __('Kenarlık Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_styling',
            'active_callback' => function() {
                return get_theme_mod('footer_border_top', false);
            },
        ]));
    }
    
    /**
     * Footer Copyright Section
     */
    private function add_footer_copyright_section($wp_customize) {
        $wp_customize->add_section('pratikwp_footer_copyright', [
            'title' => __('Copyright Alanı', 'pratikwp'),
            'panel' => 'pratikwp_footer_panel',
            'priority' => 40,
        ]);
        
        // Show Copyright Area
        $wp_customize->add_setting('show_footer_copyright', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'show_footer_copyright', [
            'label' => __('Copyright Alanını Göster', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
        ]));
        
        // Copyright Text
        $wp_customize->add_setting('footer_copyright_text', [
            'default' => sprintf(__('© %s Tüm hakları saklıdır.', 'pratikwp'), date('Y')),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_copyright_text', [
            'label' => __('Copyright Metni', 'pratikwp'),
            'description' => __('HTML etiketleri kullanabilirsiniz. {year} otomatik yıl, {site_name} site adı', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
            'type' => 'textarea',
            'active_callback' => function() {
                return get_theme_mod('show_footer_copyright', true);
            },
        ]);
        
        // Copyright Layout
        $wp_customize->add_setting('footer_copyright_layout', [
            'default' => 'center',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_copyright_layout', [
            'label' => __('Copyright Düzeni', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
            'type' => 'select',
            'choices' => [
                'left' => __('Sol - Copyright | Sağ - Menü', 'pratikwp'),
                'center' => __('Orta - Sadece Copyright', 'pratikwp'),
                'right' => __('Sol - Menü | Sağ - Copyright', 'pratikwp'),
                'center_stack' => __('İkisi de Orta (Alt alta)', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('show_footer_copyright', true);
            },
        ]);
        
        // Copyright Background
        $wp_customize->add_setting('footer_copyright_background', [
            'default' => '#1a252f',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_copyright_background', [
            'label' => __('Copyright Arka Plan Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
            'active_callback' => function() {
                return get_theme_mod('show_footer_copyright', true);
            },
        ]));
        
        // Copyright Text Color
        $wp_customize->add_setting('footer_copyright_text_color', [
            'default' => '#cccccc',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_copyright_text_color', [
            'label' => __('Copyright Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
            'active_callback' => function() {
                return get_theme_mod('show_footer_copyright', true);
            },
        ]));
        
        // Copyright Padding
        $wp_customize->add_setting('footer_copyright_padding', [
            'default' => 20,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'footer_copyright_padding', [
            'label' => __('Copyright Alan Padding', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
            'min' => 10,
            'max' => 50,
            'step' => 5,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('show_footer_copyright', true);
            },
        ]));
        
        // Show Footer Menu
        $wp_customize->add_setting('show_footer_menu', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'show_footer_menu', [
            'label' => __('Footer Menüsü Göster', 'pratikwp'),
            'section' => 'pratikwp_footer_copyright',
            'active_callback' => function() {
                return get_theme_mod('show_footer_copyright', true) && 
                       in_array(get_theme_mod('footer_copyright_layout', 'center'), ['left', 'right', 'center_stack']);
            },
        ]));
    }
    
    /**
     * Footer Social Section
     */
    private function add_footer_social_section($wp_customize) {
        $wp_customize->add_section('pratikwp_footer_social', [
            'title' => __('Footer Sosyal Medya', 'pratikwp'),
            'panel' => 'pratikwp_footer_panel',
            'priority' => 50,
        ]);
        
        // Show Footer Social
        $wp_customize->add_setting('show_footer_social', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'show_footer_social', [
            'label' => __('Footer Sosyal Medya Göster', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
        ]));
        
        // Social Position
        $wp_customize->add_setting('footer_social_position', [
            'default' => 'widget_area',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('footer_social_position', [
            'label' => __('Sosyal Medya Konumu', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
            'type' => 'select',
            'choices' => [
                'widget_area' => __('Widget Alanında', 'pratikwp'),
                'copyright_area' => __('Copyright Alanında', 'pratikwp'),
                'both' => __('Her İkisinde de', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('show_footer_social', true);
            },
        ]);
        
        // Social Style
        $wp_customize->add_setting('footer_social_style', [
            'default' => 'icon',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('footer_social_style', [
            'label' => __('Sosyal Medya Stili', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
            'type' => 'select',
            'choices' => [
                'icon' => __('Sadece İkon', 'pratikwp'),
                'icon_text' => __('İkon + Metin', 'pratikwp'),
                'text' => __('Sadece Metin', 'pratikwp'),
                'button' => __('Buton Stili', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('show_footer_social', true);
            },
        ]);
        
        // Social Size
        $wp_customize->add_setting('footer_social_size', [
            'default' => 16,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'footer_social_size', [
            'label' => __('Sosyal Medya İkon Boyutu', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
            'min' => 12,
            'max' => 48,
            'step' => 2,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('show_footer_social', true) && 
                       in_array(get_theme_mod('footer_social_style', 'icon'), ['icon', 'icon_text', 'button']);
            },
        ]));
        
        // Social Color
        $wp_customize->add_setting('footer_social_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_social_color', [
            'label' => __('Sosyal Medya Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
            'active_callback' => function() {
                return get_theme_mod('show_footer_social', true);
            },
        ]));
        
        // Social Hover Color
        $wp_customize->add_setting('footer_social_hover_color', [
            'default' => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_social_hover_color', [
            'label' => __('Sosyal Medya Hover Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
            'active_callback' => function() {
                return get_theme_mod('show_footer_social', true);
            },
        ]));
        
        // Social Spacing
        $wp_customize->add_setting('footer_social_spacing', [
            'default' => 10,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'footer_social_spacing', [
            'label' => __('Sosyal Medya Aralığı', 'pratikwp'),
            'section' => 'pratikwp_footer_social',
            'min' => 5,
            'max' => 30,
            'step' => 5,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('show_footer_social', true);
            },
        ]));
    }
}