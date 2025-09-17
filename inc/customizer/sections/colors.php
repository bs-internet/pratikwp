<?php
/**
 * Colors Section for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Colors_Section {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_section']);
    }
    
    public function register_section($wp_customize) {
        // Colors Section
        $wp_customize->add_section('pratikwp_colors', [
            'title' => __('Renkler', 'pratikwp'),
            'priority' => 40,
            'capability' => 'edit_theme_options',
            'description' => __('Site genelinde kullanılan renk ayarları', 'pratikwp'),
        ]);
        
        // Brand Colors
        $this->add_brand_colors($wp_customize);
        
        // Text Colors
        $this->add_text_colors($wp_customize);
        
        // Background Colors
        $this->add_background_colors($wp_customize);
        
        // Button Colors
        $this->add_button_colors($wp_customize);
        
        // Link Colors
        $this->add_link_colors($wp_customize);
        
        // Status Colors
        $this->add_status_colors($wp_customize);
        
        // Color Schemes
        $this->add_color_schemes($wp_customize);
    }
    
    /**
     * Brand Colors
     */
    private function add_brand_colors($wp_customize) {
        // Brand Colors Heading
        $wp_customize->add_setting('brand_colors_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'brand_colors_heading', [
            'label' => '',
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Primary Color
        $wp_customize->add_setting('primary_color', [
            'default' => '#007cba',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
            'label' => __('Ana Renk (Primary)', 'pratikwp'),
            'description' => __('Tema genelinde kullanılan ana renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Secondary Color
        $wp_customize->add_setting('secondary_color', [
            'default' => '#6c757d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', [
            'label' => __('İkincil Renk (Secondary)', 'pratikwp'),
            'description' => __('Destekleyici öğeler için kullanılan renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Accent Color
        $wp_customize->add_setting('accent_color', [
            'default' => '#17a2b8',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'accent_color', [
            'label' => __('Vurgu Rengi (Accent)', 'pratikwp'),
            'description' => __('Dikkat çekici öğeler için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Dark Color
        $wp_customize->add_setting('dark_color', [
            'default' => '#343a40',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'dark_color', [
            'label' => __('Koyu Renk (Dark)', 'pratikwp'),
            'description' => __('Koyu tema ve öğeler için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Light Color
        $wp_customize->add_setting('light_color', [
            'default' => '#f8f9fa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'light_color', [
            'label' => __('Açık Renk (Light)', 'pratikwp'),
            'description' => __('Açık tema ve arka planlar için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }
    
    /**
     * Text Colors
     */
    private function add_text_colors($wp_customize) {
        // Text Colors Separator
        $wp_customize->add_setting('text_colors_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'text_colors_separator', [
            'label' => __('Metin Renkleri', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Body Text Color
        $wp_customize->add_setting('body_text_color', [
            'default' => '#212529',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_text_color', [
            'label' => __('Ana Metin Rengi', 'pratikwp'),
            'description' => __('Genel metin içeriği rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Heading Text Color
        $wp_customize->add_setting('heading_text_color', [
            'default' => '#212529',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'heading_text_color', [
            'label' => __('Başlık Rengi', 'pratikwp'),
            'description' => __('H1, H2, H3... başlık renkleri', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Meta Text Color
        $wp_customize->add_setting('meta_text_color', [
            'default' => '#6c757d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'meta_text_color', [
            'label' => __('Meta Bilgi Rengi', 'pratikwp'),
            'description' => __('Tarih, yazar, kategori vb. bilgiler', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Muted Text Color
        $wp_customize->add_setting('muted_text_color', [
            'default' => '#868e96',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'muted_text_color', [
            'label' => __('Soluk Metin Rengi', 'pratikwp'),
            'description' => __('İkincil önemdeki metinler için', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }
    
    /**
     * Background Colors
     */
    private function add_background_colors($wp_customize) {
        // Background Colors Separator
        $wp_customize->add_setting('background_colors_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'background_colors_separator', [
            'label' => __('Arka Plan Renkleri', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Body Background Color
        $wp_customize->add_setting('body_background_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_background_color', [
            'label' => __('Ana Arka Plan Rengi', 'pratikwp'),
            'description' => __('Sitenin genel arka plan rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Content Background Color
        $wp_customize->add_setting('content_background_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'content_background_color', [
            'label' => __('İçerik Arka Plan Rengi', 'pratikwp'),
            'description' => __('Ana içerik alanının arka plan rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Sidebar Background Color
        $wp_customize->add_setting('sidebar_background_color', [
            'default' => '#f8f9fa',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sidebar_background_color', [
            'label' => __('Sidebar Arka Plan Rengi', 'pratikwp'),
            'description' => __('Kenar çubuğu arka plan rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Border Color
        $wp_customize->add_setting('border_color', [
            'default' => '#dee2e6',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'border_color', [
            'label' => __('Kenarlık Rengi', 'pratikwp'),
            'description' => __('Genel kenarlık ve ayırıcı rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }
    
    /**
     * Button Colors
     */
    private function add_button_colors($wp_customize) {
        // Button Colors Separator
        $wp_customize->add_setting('button_colors_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'button_colors_separator', [
            'label' => __('Buton Renkleri', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Primary Button Background
        $wp_customize->add_setting('button_primary_bg', [
            'default' => '#007cba',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_primary_bg', [
            'label' => __('Ana Buton Arka Plan', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Primary Button Text
        $wp_customize->add_setting('button_primary_text', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_primary_text', [
            'label' => __('Ana Buton Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Primary Button Hover Background
        $wp_customize->add_setting('button_primary_hover_bg', [
            'default' => '#005a87',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_primary_hover_bg', [
            'label' => __('Ana Buton Hover Arka Plan', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Secondary Button Background
        $wp_customize->add_setting('button_secondary_bg', [
            'default' => '#6c757d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_secondary_bg', [
            'label' => __('İkincil Buton Arka Plan', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Secondary Button Text
        $wp_customize->add_setting('button_secondary_text', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'button_secondary_text', [
            'label' => __('İkincil Buton Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }
    
    /**
     * Link Colors
     */
    private function add_link_colors($wp_customize) {
        // Link Colors Separator
        $wp_customize->add_setting('link_colors_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'link_colors_separator', [
            'label' => __('Link Renkleri', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Link Color
        $wp_customize->add_setting('link_color', [
            'default' => '#007cba',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', [
            'label' => __('Link Rengi', 'pratikwp'),
            'description' => __('Normal durumdaki link rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Link Hover Color
        $wp_customize->add_setting('link_hover_color', [
            'default' => '#005a87',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_hover_color', [
            'label' => __('Link Hover Rengi', 'pratikwp'),
            'description' => __('Mouse ile üzerine gelindiğinde link rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Link Visited Color
        $wp_customize->add_setting('link_visited_color', [
            'default' => '#6f42c1',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_visited_color', [
            'label' => __('Ziyaret Edilen Link Rengi', 'pratikwp'),
            'description' => __('Daha önce ziyaret edilen link rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }
    
    /**
     * Status Colors
     */
    private function add_status_colors($wp_customize) {
        // Status Colors Separator
        $wp_customize->add_setting('status_colors_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'status_colors_separator', [
            'label' => __('Durum Renkleri', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Success Color
        $wp_customize->add_setting('success_color', [
            'default' => '#28a745',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'success_color', [
            'label' => __('Başarı Rengi', 'pratikwp'),
            'description' => __('Başarılı işlemler için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Warning Color
        $wp_customize->add_setting('warning_color', [
            'default' => '#ffc107',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'warning_color', [
            'label' => __('Uyarı Rengi', 'pratikwp'),
            'description' => __('Uyarı mesajları için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Error/Danger Color
        $wp_customize->add_setting('error_color', [
            'default' => '#dc3545',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'error_color', [
            'label' => __('Hata Rengi', 'pratikwp'),
            'description' => __('Hata mesajları için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Info Color
        $wp_customize->add_setting('info_color', [
            'default' => '#17a2b8',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'info_color', [
            'label' => __('Bilgi Rengi', 'pratikwp'),
            'description' => __('Bilgilendirme mesajları için renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }
    
    /**
     * Color Schemes
     */
    private function add_color_schemes($wp_customize) {
        // Color Schemes Separator
        $wp_customize->add_setting('color_schemes_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'color_schemes_separator', [
            'label' => __('Renk Şemaları', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'hidden',
        ]));
        
        // Predefined Color Scheme
        $wp_customize->add_setting('predefined_color_scheme', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('predefined_color_scheme', [
            'label' => __('Hazır Renk Şeması', 'pratikwp'),
            'description' => __('Hazır renk şemalarından birini seçin', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'type' => 'select',
            'choices' => [
                'default' => __('Varsayılan', 'pratikwp'),
                'blue' => __('Mavi Tema', 'pratikwp'),
                'green' => __('Yeşil Tema', 'pratikwp'),
                'purple' => __('Mor Tema', 'pratikwp'),
                'orange' => __('Turuncu Tema', 'pratikwp'),
                'red' => __('Kırmızı Tema', 'pratikwp'),
                'dark' => __('Koyu Tema', 'pratikwp'),
                'minimal' => __('Minimal Tema', 'pratikwp'),
                'custom' => __('Özel Ayarlar', 'pratikwp'),
            ],
        ]);
        
        // Dark Mode Toggle
        $wp_customize->add_setting('enable_dark_mode', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_dark_mode', [
            'label' => __('Koyu Mod Desteği', 'pratikwp'),
            'description' => __('Kullanıcıların koyu/açık mod arası geçiş yapabilmesi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Auto Dark Mode
        $wp_customize->add_setting('auto_dark_mode', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'auto_dark_mode', [
            'label' => __('Otomatik Koyu Mod', 'pratikwp'),
            'description' => __('Sistem tercihine göre otomatik koyu/açık mod', 'pratikwp'),
            'section' => 'pratikwp_colors',
            'active_callback' => function() {
                return get_theme_mod('enable_dark_mode', false);
            },
        ]));
    }
}