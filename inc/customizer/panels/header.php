<?php
/**
 * Header Panel for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Header_Panel {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_panel']);
    }
    
    public function register_panel($wp_customize) {
        // Header Panel
        $wp_customize->add_panel('pratikwp_header_panel', [
            'title' => __('Header Ayarları', 'pratikwp'),
            'description' => __('Header bölümü görünüm ve davranış ayarları', 'pratikwp'),
            'priority' => 30,
            'capability' => 'edit_theme_options',
        ]);
        
        // Header Layout Section
        $this->add_header_layout_section($wp_customize);
        
        // Header Styling Section
        $this->add_header_styling_section($wp_customize);
        
        // Header Components Section
        $this->add_header_components_section($wp_customize);
        
        // Mobile Header Section
        $this->add_mobile_header_section($wp_customize);
        
        // Sticky Header Section
        $this->add_sticky_header_section($wp_customize);
    }
    
    /**
     * Header Layout Section
     */
    private function add_header_layout_section($wp_customize) {
        $wp_customize->add_section('pratikwp_header_layout', [
            'title' => __('Header Düzeni', 'pratikwp'),
            'panel' => 'pratikwp_header_panel',
            'priority' => 10,
        ]);
        
        // Header Layout Type
        $wp_customize->add_setting('header_layout_type', [
            'default' => 'layout_1',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_layout_type', [
            'label' => __('Header Düzen Tipi', 'pratikwp'),
            'section' => 'pratikwp_header_layout',
            'type' => 'select',
            'choices' => [
                'layout_1' => __('Layout 1 - Logo Sol, Menü Sağ', 'pratikwp'),
                'layout_2' => __('Layout 2 - Logo Orta, Menü Alt', 'pratikwp'),
                'layout_3' => __('Layout 3 - Logo Sağ, Menü Sol', 'pratikwp'),
                'layout_4' => __('Layout 4 - Logo Orta, Menü Çift Taraf', 'pratikwp'),
                'layout_5' => __('Layout 5 - Dikey Header', 'pratikwp'),
            ],
        ]);
        
        // Header Width
        $wp_customize->add_setting('header_width', [
            'default' => 'container',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('header_width', [
            'label' => __('Header Genişliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout',
            'type' => 'select',
            'choices' => [
                'container' => __('Container (Sınırlı)', 'pratikwp'),
                'container-fluid' => __('Tam Genişlik', 'pratikwp'),
            ],
        ]);
        
        // Header Height
        $wp_customize->add_setting('header_height', [
            'default' => 80,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'header_height', [
            'label' => __('Header Yüksekliği', 'pratikwp'),
            'section' => 'pratikwp_header_layout',
            'min' => 60,
            'max' => 150,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Logo Position
        $wp_customize->add_setting('logo_position', [
            'default' => 'left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('logo_position', [
            'label' => __('Logo Konumu', 'pratikwp'),
            'section' => 'pratikwp_header_layout',
            'type' => 'select',
            'choices' => [
                'left' => __('Sol', 'pratikwp'),
                'center' => __('Orta', 'pratikwp'),
                'right' => __('Sağ', 'pratikwp'),
            ],
        ]);
        
        // Menu Position
        $wp_customize->add_setting('menu_position', [
            'default' => 'right',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('menu_position', [
            'label' => __('Menü Konumu', 'pratikwp'),
            'section' => 'pratikwp_header_layout',
            'type' => 'select',
            'choices' => [
                'left' => __('Sol', 'pratikwp'),
                'center' => __('Orta', 'pratikwp'),
                'right' => __('Sağ', 'pratikwp'),
            ],
        ]);
    }
    
    /**
     * Header Styling Section
     */
    private function add_header_styling_section($wp_customize) {
        $wp_customize->add_section('pratikwp_header_styling', [
            'title' => __('Header Stilleri', 'pratikwp'),
            'panel' => 'pratikwp_header_panel',
            'priority' => 20,
        ]);
        
        // Header Background Color
        $wp_customize->add_setting('header_background_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', [
            'label' => __('Header Arka Plan Rengi', 'pratikwp'),
            'section' => 'pratikwp_header_styling',
        ]));
        
        // Header Text Color
        $wp_customize->add_setting('header_text_color', [
            'default' => '#333333',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_text_color', [
            'label' => __('Header Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_header_styling',
        ]));
        
        // Header Border
        $wp_customize->add_setting('header_border_bottom', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_border_bottom', [
            'label' => __('Alt Kenarlık', 'pratikwp'),
            'section' => 'pratikwp_header_styling',
        ]));
        
        // Header Border Color
        $wp_customize->add_setting('header_border_color', [
            'default' => '#e5e5e5',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_border_color', [
            'label' => __('Kenarlık Rengi', 'pratikwp'),
            'section' => 'pratikwp_header_styling',
            'active_callback' => function() {
                return get_theme_mod('header_border_bottom', true);
            },
        ]));
        
        // Header Shadow
        $wp_customize->add_setting('header_shadow', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_shadow', [
            'label' => __('Header Gölgesi', 'pratikwp'),
            'section' => 'pratikwp_header_styling',
        ]));
        
        // Logo Max Height
        $wp_customize->add_setting('logo_max_height', [
            'default' => 50,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'logo_max_height', [
            'label' => __('Logo Maksimum Yükseklik', 'pratikwp'),
            'section' => 'pratikwp_header_styling',
            'min' => 30,
            'max' => 100,
            'step' => 5,
            'suffix' => 'px',
        ]));
    }
    
    /**
     * Header Components Section
     */
    private function add_header_components_section($wp_customize) {
        $wp_customize->add_section('pratikwp_header_components', [
            'title' => __('Header Bileşenleri', 'pratikwp'),
            'panel' => 'pratikwp_header_panel',
            'priority' => 30,
        ]);
        
        // Show Top Bar
        $wp_customize->add_setting('show_top_bar', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'show_top_bar', [
            'label' => __('Üst Bar Göster', 'pratikwp'),
            'description' => __('Header üstünde ince bilgi barı', 'pratikwp'),
            'section' => 'pratikwp_header_components',
        ]));
        
        // Top Bar Content
        $wp_customize->add_setting('top_bar_content', [
            'default' => 'contact',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('top_bar_content', [
            'label' => __('Üst Bar İçeriği', 'pratikwp'),
            'section' => 'pratikwp_header_components',
            'type' => 'select',
            'choices' => [
                'contact' => __('İletişim Bilgileri', 'pratikwp'),
                'social' => __('Sosyal Medya', 'pratikwp'),
                'text' => __('Özel Metin', 'pratikwp'),
                'menu' => __('Ek Menü', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('show_top_bar', true);
            },
        ]);
        
        // Show Search
        $wp_customize->add_setting('header_show_search', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_show_search', [
            'label' => __('Arama Göster', 'pratikwp'),
            'section' => 'pratikwp_header_components',
        ]));
        
        // Search Style
        $wp_customize->add_setting('header_search_style', [
            'default' => 'icon',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('header_search_style', [
            'label' => __('Arama Stili', 'pratikwp'),
            'section' => 'pratikwp_header_components',
            'type' => 'select',
            'choices' => [
                'icon' => __('Sadece İkon', 'pratikwp'),
                'form' => __('Arama Formu', 'pratikwp'),
                'overlay' => __('Overlay Modal', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('header_show_search', true);
            },
        ]);
        
        // Show Cart (WooCommerce)
        if (class_exists('WooCommerce')) {
            $wp_customize->add_setting('header_show_cart', [
                'default' => true,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_show_cart', [
                'label' => __('Sepet İkonunu Göster', 'pratikwp'),
                'section' => 'pratikwp_header_components',
            ]));
        }
        
        // Show Language Switcher (WPML/Polylang)
        if (function_exists('icl_get_languages') || function_exists('pll_the_languages')) {
            $wp_customize->add_setting('header_show_language', [
                'default' => false,
                'sanitize_callback' => 'rest_sanitize_boolean',
                'transport' => 'refresh',
            ]);
            
            $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'header_show_language', [
                'label' => __('Dil Değiştirici Göster', 'pratikwp'),
                'section' => 'pratikwp_header_components',
            ]));
        }
    }
    
    /**
     * Mobile Header Section
     */
    private function add_mobile_header_section($wp_customize) {
        $wp_customize->add_section('pratikwp_mobile_header', [
            'title' => __('Mobil Header', 'pratikwp'),
            'panel' => 'pratikwp_header_panel',
            'priority' => 40,
        ]);
        
        // Mobile Menu Style
        $wp_customize->add_setting('mobile_menu_style', [
            'default' => 'slide',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('mobile_menu_style', [
            'label' => __('Mobil Menü Stili', 'pratikwp'),
            'section' => 'pratikwp_mobile_header',
            'type' => 'select',
            'choices' => [
                'slide' => __('Yan Kaydırma', 'pratikwp'),
                'dropdown' => __('Açılır Liste', 'pratikwp'),
                'fullscreen' => __('Tam Ekran', 'pratikwp'),
                'push' => __('İtme Efekti', 'pratikwp'),
            ],
        ]);
        
        // Mobile Menu Position
        $wp_customize->add_setting('mobile_menu_position', [
            'default' => 'left',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('mobile_menu_position', [
            'label' => __('Mobil Menü Konumu', 'pratikwp'),
            'section' => 'pratikwp_mobile_header',
            'type' => 'select',
            'choices' => [
                'left' => __('Sol', 'pratikwp'),
                'right' => __('Sağ', 'pratikwp'),
            ],
            'active_callback' => function() {
                return in_array(get_theme_mod('mobile_menu_style', 'slide'), ['slide', 'push']);
            },
        ]);
        
        // Mobile Header Height
        $wp_customize->add_setting('mobile_header_height', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'mobile_header_height', [
            'label' => __('Mobil Header Yüksekliği', 'pratikwp'),
            'section' => 'pratikwp_mobile_header',
            'min' => 50,
            'max' => 100,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Mobile Logo Max Height
        $wp_customize->add_setting('mobile_logo_max_height', [
            'default' => 35,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'mobile_logo_max_height', [
            'label' => __('Mobil Logo Maksimum Yükseklik', 'pratikwp'),
            'section' => 'pratikwp_mobile_header',
            'min' => 20,
            'max' => 60,
            'step' => 5,
            'suffix' => 'px',
        ]));
    }
    
    /**
     * Sticky Header Section
     */
    private function add_sticky_header_section($wp_customize) {
        $wp_customize->add_section('pratikwp_sticky_header', [
            'title' => __('Sabit Header', 'pratikwp'),
            'panel' => 'pratikwp_header_panel',
            'priority' => 50,
        ]);
        
        // Enable Sticky Header
        $wp_customize->add_setting('enable_sticky_header', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_sticky_header', [
            'label' => __('Sabit Header Etkinleştir', 'pratikwp'),
            'description' => __('Sayfa kaydırıldığında header sabit kalır', 'pratikwp'),
            'section' => 'pratikwp_sticky_header',
        ]));
        
        // Sticky Header Style
        $wp_customize->add_setting('sticky_header_style', [
            'default' => 'shrink',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('sticky_header_style', [
            'label' => __('Sabit Header Stili', 'pratikwp'),
            'section' => 'pratikwp_sticky_header',
            'type' => 'select',
            'choices' => [
                'normal' => __('Normal Boyut', 'pratikwp'),
                'shrink' => __('Küçültülmüş', 'pratikwp'),
                'transparent' => __('Şeffaf', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('enable_sticky_header', true);
            },
        ]);
        
        // Sticky Header Background
        $wp_customize->add_setting('sticky_header_background', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'sticky_header_background', [
            'label' => __('Sabit Header Arka Plan', 'pratikwp'),
            'section' => 'pratikwp_sticky_header',
            'active_callback' => function() {
                return get_theme_mod('enable_sticky_header', true);
            },
        ]));
        
        // Sticky Header Height
        $wp_customize->add_setting('sticky_header_height', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'sticky_header_height', [
            'label' => __('Sabit Header Yüksekliği', 'pratikwp'),
            'section' => 'pratikwp_sticky_header',
            'min' => 40,
            'max' => 100,
            'step' => 5,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('enable_sticky_header', true) && get_theme_mod('sticky_header_style', 'shrink') === 'shrink';
            },
        ]));
        
        // Sticky Header Animation
        $wp_customize->add_setting('sticky_header_animation', [
            'default' => 'slide_down',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('sticky_header_animation', [
            'label' => __('Animasyon Efekti', 'pratikwp'),
            'section' => 'pratikwp_sticky_header',
            'type' => 'select',
            'choices' => [
                'none' => __('Animasyon Yok', 'pratikwp'),
                'slide_down' => __('Aşağı Kayma', 'pratikwp'),
                'fade_in' => __('Belirme', 'pratikwp'),
                'zoom_in' => __('Yakınlaşma', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('enable_sticky_header', true);
            },
        ]);
    }
}