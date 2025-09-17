<?php
/**
 * Header Layout Section for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Header_Layout_Section {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_section']);
    }
    
    public function register_section($wp_customize) {
        // Header Layout Section
        $wp_customize->add_section('pratikwp_header_layout_section', [
            'title' => __('Header Layout Builder', 'pratikwp'),
            'priority' => 35,
            'capability' => 'edit_theme_options',
            'description' => __('Header düzenini özelleştirin ve bileşenleri organize edin', 'pratikwp'),
        ]);
        
        // Header Layout Templates
        $this->add_layout_templates($wp_customize);
        
        // Header Components
        $this->add_header_components($wp_customize);
        
        // Header Rows
        $this->add_header_rows($wp_customize);
        
        // Responsive Settings
        $this->add_responsive_settings($wp_customize);
        
        // Advanced Layout Settings
        $this->add_advanced_layout_settings($wp_customize);
    }
    
    /**
     * Header Layout Templates
     */
    private function add_layout_templates($wp_customize) {
        // Layout Templates Heading
        $wp_customize->add_setting('header_layout_templates_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_layout_templates_heading', [
            'label' => __('Header Layout Şablonları', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'hidden',
        ]));
        
        // Predefined Layout Template
        $wp_customize->add_setting('header_layout_template', [
            'default' => 'classic',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_layout_template', [
            'label' => __('Hazır Layout Şablonu', 'pratikwp'),
            'description' => __('Hızlı başlangıç için hazır şablonlardan birini seçin', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'classic' => __('Classic - Logo Sol, Menü Sağ', 'pratikwp'),
                'center' => __('Center - Logo Orta, Menü Alt', 'pratikwp'),
                'minimal' => __('Minimal - Sadece Menü', 'pratikwp'),
                'corporate' => __('Corporate - Logo Sol, Menü Orta, Butonlar Sağ', 'pratikwp'),
                'ecommerce' => __('E-commerce - Logo Sol, Arama Orta, Sepet Sağ', 'pratikwp'),
                'magazine' => __('Magazine - Üst Bar + Ana Header', 'pratikwp'),
                'creative' => __('Creative - Dikey Layout', 'pratikwp'),
                'custom' => __('Custom - Özel Düzen', 'pratikwp'),
            ],
        ]);
        
        // Import/Export Layout
        $wp_customize->add_setting('header_layout_import_export', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control('header_layout_import_export', [
            'label' => __('Layout İçe/Dışa Aktarma', 'pratikwp'),
            'description' => __('Header layout\'unuzu kaydedin veya başka bir layout yükleyin', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'textarea',
            'input_attrs' => [
                'placeholder' => __('Layout JSON verisini buraya yapıştırın...', 'pratikwp'),
                'rows' => 3,
            ],
        ]);
    }
    
    /**
     * Header Components
     */
    private function add_header_components($wp_customize) {
        // Components Separator
        $wp_customize->add_setting('header_components_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_components_separator', [
            'label' => __('Header Bileşenleri', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'hidden',
        ]));
        
        // Available Components
        $wp_customize->add_setting('available_header_components', [
            'default' => json_encode([
                'logo' => ['enabled' => true, 'position' => 'left'],
                'menu' => ['enabled' => true, 'position' => 'center'],
                'search' => ['enabled' => true, 'position' => 'right'],
                'button' => ['enabled' => false, 'position' => 'right'],
                'social' => ['enabled' => false, 'position' => 'right'],
                'contact' => ['enabled' => false, 'position' => 'right'],
                'language' => ['enabled' => false, 'position' => 'right'],
                'cart' => ['enabled' => false, 'position' => 'right'],
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'refresh',
        ]);
        
        // Logo Component
        $wp_customize->add_setting('header_logo_enabled', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_logo_enabled', [
            'label' => __('Logo Bileşeni', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Logo Position
        $wp_customize->add_setting('header_logo_position', [
            'default' => 'left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_logo_position', [
            'label' => __('Logo Konumu', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'left' => __('Sol', 'pratikwp'),
                'center' => __('Orta', 'pratikwp'),
                'right' => __('Sağ', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('header_logo_enabled', true);
            },
        ]);
        
        // Menu Component
        $wp_customize->add_setting('header_menu_enabled', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_menu_enabled', [
            'label' => __('Ana Menü Bileşeni', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Menu Position
        $wp_customize->add_setting('header_menu_position', [
            'default' => 'center',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_menu_position', [
            'label' => __('Menü Konumu', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'left' => __('Sol', 'pratikwp'),
                'center' => __('Orta', 'pratikwp'),
                'right' => __('Sağ', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('header_menu_enabled', true);
            },
        ]);
        
        // Search Component
        $wp_customize->add_setting('header_search_enabled', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_search_enabled', [
            'label' => __('Arama Bileşeni', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Search Style
        $wp_customize->add_setting('header_search_style', [
            'default' => 'icon',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_search_style', [
            'label' => __('Arama Stili', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'icon' => __('Sadece İkon', 'pratikwp'),
                'form' => __('Arama Formu', 'pratikwp'),
                'overlay' => __('Overlay Modal', 'pratikwp'),
                'dropdown' => __('Dropdown Form', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('header_search_enabled', true);
            },
        ]);
        
        // CTA Button Component
        $wp_customize->add_setting('header_cta_enabled', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_cta_enabled', [
            'label' => __('CTA Buton Bileşeni', 'pratikwp'),
            'description' => __('Call-to-Action butonu ekler', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // CTA Button Text
        $wp_customize->add_setting('header_cta_text', [
            'default' => __('İletişime Geç', 'pratikwp'),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_cta_text', [
            'label' => __('CTA Buton Metni', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'text',
            'active_callback' => function() {
                return get_theme_mod('header_cta_enabled', false);
            },
        ]);
        
        // CTA Button URL
        $wp_customize->add_setting('header_cta_url', [
            'default' => '#',
            'sanitize_callback' => 'esc_url_raw',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_cta_url', [
            'label' => __('CTA Buton URL', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'url',
            'active_callback' => function() {
                return get_theme_mod('header_cta_enabled', false);
            },
        ]);
        
        // Social Links Component
        $wp_customize->add_setting('header_social_enabled', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_social_enabled', [
            'label' => __('Sosyal Medya Bileşeni', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Contact Info Component
        $wp_customize->add_setting('header_contact_enabled', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_contact_enabled', [
            'label' => __('İletişim Bilgisi Bileşeni', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Cart Component (WooCommerce)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_setting('header_cart_enabled', [
                'default' => false,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_cart_enabled', [
                'label' => __('Sepet Bileşeni', 'pratikwp'),
                'section' => 'pratikwp_header_layout_section',
            ]));
        }
    }
    
    /**
     * Header Rows
     */
    private function add_header_rows($wp_customize) {
        // Header Rows Separator
        $wp_customize->add_setting('header_rows_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_rows_separator', [
            'label' => __('Header Satırları', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'hidden',
        ]));
        
        // Enable Top Bar
        $wp_customize->add_setting('header_top_bar_enabled', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_top_bar_enabled', [
            'label' => __('Üst Bar Satırı', 'pratikwp'),
            'description' => __('Header üstünde ince bilgi barı', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Top Bar Height
        $wp_customize->add_setting('header_top_bar_height', [
            'default' => 35,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_top_bar_height', [
            'label' => __('Üst Bar Yüksekliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'min' => 25,
            'max' => 60,
            'step' => 5,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('header_top_bar_enabled', false);
            },
        ]));
        
        // Top Bar Content
        $wp_customize->add_setting('header_top_bar_content', [
            'default' => 'contact',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_top_bar_content', [
            'label' => __('Üst Bar İçeriği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'contact' => __('İletişim Bilgileri', 'pratikwp'),
                'social' => __('Sosyal Medya', 'pratikwp'),
                'text' => __('Özel Metin', 'pratikwp'),
                'menu' => __('Üst Menü', 'pratikwp'),
                'mixed' => __('Karışık İçerik', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('header_top_bar_enabled', false);
            },
        ]);
        
        // Main Header Row Height
        $wp_customize->add_setting('header_main_height', [
            'default' => 80,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_main_height', [
            'label' => __('Ana Header Yüksekliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'min' => 60,
            'max' => 150,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Enable Bottom Bar
        $wp_customize->add_setting('header_bottom_bar_enabled', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_bottom_bar_enabled', [
            'label' => __('Alt Bar Satırı', 'pratikwp'),
            'description' => __('Header altında ek satır', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
        ]));
        
        // Bottom Bar Content
        $wp_customize->add_setting('header_bottom_bar_content', [
            'default' => 'menu',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_bottom_bar_content', [
            'label' => __('Alt Bar İçeriği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'menu' => __('İkincil Menü', 'pratikwp'),
                'breadcrumbs' => __('Breadcrumb', 'pratikwp'),
                'page_title' => __('Sayfa Başlığı', 'pratikwp'),
                'custom' => __('Özel İçerik', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('header_bottom_bar_enabled', false);
            },
        ]);
    }
    
    /**
     * Responsive Settings
     */
    private function add_responsive_settings($wp_customize) {
        // Responsive Settings Separator
        $wp_customize->add_setting('header_responsive_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_responsive_separator', [
            'label' => __('Responsive Ayarlar', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'hidden',
        ]));
        
        // Mobile Breakpoint
        $wp_customize->add_setting('header_mobile_breakpoint', [
            'default' => 768,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_mobile_breakpoint', [
            'label' => __('Mobil Kırılma Noktası', 'pratikwp'),
            'description' => __('Bu genişlikten küçük ekranlarda mobil header gösterilir', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'min' => 480,
            'max' => 1024,
            'step' => 24,
            'suffix' => 'px',
        ]));
        
        // Hide Components on Mobile
        $wp_customize->add_setting('header_mobile_hide_components', [
            'default' => ['social', 'contact'],
            'sanitize_callback' => [$this, 'sanitize_multicheck'],
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_mobile_hide_components', [
            'label' => __('Mobilde Gizlenecek Bileşenler', 'pratikwp'),
            'description' => __('Seçilen bileşenler mobil cihazlarda gizlenir', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'multiple' => true,
            'choices' => [
                'search' => __('Arama', 'pratikwp'),
                'social' => __('Sosyal Medya', 'pratikwp'),
                'contact' => __('İletişim', 'pratikwp'),
                'cta' => __('CTA Buton', 'pratikwp'),
                'top_bar' => __('Üst Bar', 'pratikwp'),
            ],
        ]);
        
        // Mobile Header Height
        $wp_customize->add_setting('header_mobile_height', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_mobile_height', [
            'label' => __('Mobil Header Yüksekliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'min' => 50,
            'max' => 100,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Mobile Menu Style
        $wp_customize->add_setting('header_mobile_menu_style', [
            'default' => 'slide',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_mobile_menu_style', [
            'label' => __('Mobil Menü Stili', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'slide' => __('Yan Kaydırma', 'pratikwp'),
                'dropdown' => __('Dropdown', 'pratikwp'),
                'fullscreen' => __('Tam Ekran', 'pratikwp'),
                'push' => __('İtme Efekti', 'pratikwp'),
            ],
        ]);
    }
    
    /**
     * Advanced Layout Settings
     */
    private function add_advanced_layout_settings($wp_customize) {
        // Advanced Settings Separator
        $wp_customize->add_setting('header_advanced_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'header_advanced_separator', [
            'label' => __('Gelişmiş Layout Ayarları', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'hidden',
        ]));
        
        // Header Container Width
        $wp_customize->add_setting('header_container_width', [
            'default' => 'container',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('header_container_width', [
            'label' => __('Header Container Genişliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'container' => __('Container (Sınırlı)', 'pratikwp'),
                'container-fluid' => __('Tam Genişlik', 'pratikwp'),
                'custom' => __('Özel Genişlik', 'pratikwp'),
            ],
        ]);
        
        // Custom Container Width
        $wp_customize->add_setting('header_custom_container_width', [
            'default' => 1200,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_custom_container_width', [
            'label' => __('Özel Container Genişliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'min' => 960,
            'max' => 1920,
            'step' => 20,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('header_container_width', 'container') === 'custom';
            },
        ]));
        
        // Header Alignment
        $wp_customize->add_setting('header_alignment', [
            'default' => 'space-between',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('header_alignment', [
            'label' => __('Header Bileşen Hizalama', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'flex-start' => __('Sola Hizalı', 'pratikwp'),
                'center' => __('Ortala', 'pratikwp'),
                'flex-end' => __('Sağa Hizalı', 'pratikwp'),
                'space-between' => __('Aralarında Boşluk', 'pratikwp'),
                'space-around' => __('Çevresinde Boşluk', 'pratikwp'),
                'space-evenly' => __('Eşit Boşluk', 'pratikwp'),
            ],
        ]);
        
        // Vertical Alignment
        $wp_customize->add_setting('header_vertical_alignment', [
            'default' => 'center',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('header_vertical_alignment', [
            'label' => __('Dikey Hizalama', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'type' => 'select',
            'choices' => [
                'flex-start' => __('Üst', 'pratikwp'),
                'center' => __('Orta', 'pratikwp'),
                'flex-end' => __('Alt', 'pratikwp'),
                'baseline' => __('Temel Çizgi', 'pratikwp'),
                'stretch' => __'Uzat', 'pratikwp'),
            ],
        ]);
        
        // Header Z-Index
        $wp_customize->add_setting('header_z_index', [
            'default' => 999,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_z_index', [
            'label' => __('Header Z-Index', 'pratikwp'),
            'description' => __('Diğer öğelerin üzerinde görünmesi için', 'pratikwp'),
            'section' => 'pratikwp_header_layout_section',
            'min' => 1,
            'max' => 9999,
            'step' => 1,
        ]));
    }
    
    /**
     * Sanitize multicheck values
     */
    public function sanitize_multicheck($values) {
        if (!is_array($values)) {
            return [];
        }
        
        $valid_options = ['search', 'social', 'contact', 'cta', 'top_bar'];
        $sanitized = [];
        
        foreach ($values as $value) {
            if (in_array($value, $valid_options)) {
                $sanitized[] = $value;
            }
        }
        
        return $sanitized;
    }
}