<?php
/**
 * Typography Section for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Typography_Section {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_section']);
        add_action('wp_head', [$this, 'output_google_fonts']);
    }
    
    public function register_section($wp_customize) {
        // Typography Section
        $wp_customize->add_section('pratikwp_typography', [
            'title' => __('Tipografi', 'pratikwp'),
            'priority' => 50,
            'capability' => 'edit_theme_options',
            'description' => __('Font ayarları ve tipografi düzenlemeleri', 'pratikwp'),
        ]);
        
        // Body Typography
        $this->add_body_typography($wp_customize);
        
        // Heading Typography
        $this->add_heading_typography($wp_customize);
        
        // Menu Typography
        $this->add_menu_typography($wp_customize);
        
        // Button Typography
        $this->add_button_typography($wp_customize);
        
        // Widget Typography
        $this->add_widget_typography($wp_customize);
        
        // Advanced Typography
        $this->add_advanced_typography($wp_customize);
    }
    
    /**
     * Body Typography
     */
    private function add_body_typography($wp_customize) {
        // Body Typography Heading
        $wp_customize->add_setting('body_typography_heading', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'body_typography_heading', [
            'label' => __('Genel Metin Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'hidden',
        ]));
        
        // Body Typography
        $wp_customize->add_setting('body_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 16,
                'font_weight' => '400',
                'line_height' => 1.6,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'body_typography', [
            'label' => __('Ana Metin Tipografisi', 'pratikwp'),
            'description' => __('Sitenin genel metin ayarları', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => true,
            'show_text_transform' => false,
            'preview_text' => __('Bu bir örnek paragraf metnidir. Tipografi ayarlarınızın nasıl göründüğünü buradan görebilirsiniz.', 'pratikwp'),
        ]));
        
        // Paragraph Spacing
        $wp_customize->add_setting('paragraph_spacing', [
            'default' => 1.2,
            'sanitize_callback' => [$this, 'sanitize_float'],
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'paragraph_spacing', [
            'label' => __('Paragraf Aralığı', 'pratikwp'),
            'description' => __('Paragraflar arası boşluk (em)', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'min' => 0.5,
            'max' => 3,
            'step' => 0.1,
            'suffix' => 'em',
        ]));
    }
    
    /**
     * Heading Typography
     */
    private function add_heading_typography($wp_customize) {
        // Heading Typography Separator
        $wp_customize->add_setting('heading_typography_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'heading_typography_separator', [
            'label' => __('Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'hidden',
        ]));
        
        // H1 Typography
        $wp_customize->add_setting('h1_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 32,
                'font_weight' => '700',
                'line_height' => 1.2,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'h1_typography', [
            'label' => __('H1 Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => true,
            'show_text_transform' => true,
            'preview_text' => __('Ana Başlık Örneği', 'pratikwp'),
        ]));
        
        // H2 Typography
        $wp_customize->add_setting('h2_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 28,
                'font_weight' => '600',
                'line_height' => 1.3,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'h2_typography', [
            'label' => __('H2 Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => true,
            'show_text_transform' => true,
            'preview_text' => __('İkinci Seviye Başlık', 'pratikwp'),
        ]));
        
        // H3 Typography
        $wp_customize->add_setting('h3_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 24,
                'font_weight' => '600',
                'line_height' => 1.4,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'h3_typography', [
            'label' => __('H3 Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => false,
            'show_text_transform' => true,
            'preview_text' => __('Üçüncü Seviye Başlık', 'pratikwp'),
        ]));
        
        // H4-H6 Combined Typography
        $wp_customize->add_setting('h456_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 18,
                'font_weight' => '500',
                'line_height' => 1.4,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'h456_typography', [
            'label' => __('H4, H5, H6 Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => false,
            'show_text_transform' => true,
            'preview_text' => __('Alt Seviye Başlık', 'pratikwp'),
        ]));
    }
    
    /**
     * Menu Typography
     */
    private function add_menu_typography($wp_customize) {
        // Menu Typography Separator
        $wp_customize->add_setting('menu_typography_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'menu_typography_separator', [
            'label' => __('Menü Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'hidden',
        ]));
        
        // Main Menu Typography
        $wp_customize->add_setting('main_menu_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 16,
                'font_weight' => '500',
                'line_height' => 1.5,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'main_menu_typography', [
            'label' => __('Ana Menü Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => false,
            'show_letter_spacing' => true,
            'show_text_transform' => true,
            'preview_text' => __('Menü Öğesi', 'pratikwp'),
        ]));
        
        // Submenu Typography
        $wp_customize->add_setting('submenu_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 14,
                'font_weight' => '400',
                'line_height' => 1.5,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'submenu_typography', [
            'label' => __('Alt Menü Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => false,
            'show_letter_spacing' => true,
            'show_text_transform' => true,
            'preview_text' => __('Alt Menü', 'pratikwp'),
        ]));
    }
    
    /**
     * Button Typography
     */
    private function add_button_typography($wp_customize) {
        // Button Typography Separator
        $wp_customize->add_setting('button_typography_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'button_typography_separator', [
            'label' => __('Buton Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'hidden',
        ]));
        
        // Button Typography
        $wp_customize->add_setting('button_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 16,
                'font_weight' => '500',
                'line_height' => 1.2,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'button_typography', [
            'label' => __('Buton Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => false,
            'show_letter_spacing' => true,
            'show_text_transform' => true,
            'preview_text' => __('Buton Metni', 'pratikwp'),
        ]));
    }
    
    /**
     * Widget Typography
     */
    private function add_widget_typography($wp_customize) {
        // Widget Typography Separator
        $wp_customize->add_setting('widget_typography_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'widget_typography_separator', [
            'label' => __('Widget Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'hidden',
        ]));
        
        // Widget Title Typography
        $wp_customize->add_setting('widget_title_typography', [
            'default' => json_encode([
                'font_family' => '',
                'font_size' => 18,
                'font_weight' => '600',
                'line_height' => 1.3,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            ]),
            'sanitize_callback' => 'wp_kses_post',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'widget_title_typography', [
            'label' => __('Widget Başlık Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => false,
            'show_text_transform' => true,
            'preview_text' => __('Widget Başlığı', 'pratikwp'),
        ]));
        
        // Widget Content Typography
        $wp_customize->add_setting('widget_content_typography', [
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
        
        $wp_customize->add_control(new PratikWp_Typography_Control($wp_customize, 'widget_content_typography', [
            'label' => __('Widget İçerik Tipografisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'show_font_family' => true,
            'show_font_size' => true,
            'show_font_weight' => true,
            'show_line_height' => true,
            'show_letter_spacing' => false,
            'show_text_transform' => false,
            'preview_text' => __('Widget içeriği örnek metin.', 'pratikwp'),
        ]));
    }
    
    /**
     * Advanced Typography
     */
    private function add_advanced_typography($wp_customize) {
        // Advanced Typography Separator
        $wp_customize->add_setting('advanced_typography_separator', [
            'sanitize_callback' => 'wp_kses_post',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'advanced_typography_separator', [
            'label' => __('Gelişmiş Ayarlar', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'hidden',
        ]));
        
        // Font Display Strategy
        $wp_customize->add_setting('font_display_strategy', [
            'default' => 'swap',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('font_display_strategy', [
            'label' => __('Font Display Stratejisi', 'pratikwp'),
            'description' => __('Google Fonts yükleme stratejisi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'select',
            'choices' => [
                'auto' => __('Auto - Tarayıcı karar verir', 'pratikwp'),
                'block' => __('Block - Font yüklenene kadar bekle', 'pratikwp'),
                'swap' => __('Swap - Hemen sistem fontu göster (Önerilen)', 'pratikwp'),
                'fallback' => __('Fallback - Kısa süre bekle sonra sistem fontu', 'pratikwp'),
                'optional' => __('Optional - Sadece cache\'de varsa kullan', 'pratikwp'),
            ],
        ]);
        
        // Preload Google Fonts
        $wp_customize->add_setting('preload_google_fonts', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'preload_google_fonts', [
            'label' => __('Google Fonts Ön Yükleme', 'pratikwp'),
            'description' => __('Google Fonts\'u öncelikli olarak yükler', 'pratikwp'),
            'section' => 'pratikwp_typography',
        ]));
        
        // Local Google Fonts
        $wp_customize->add_setting('local_google_fonts', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'local_google_fonts', [
            'label' => __('Yerel Google Fonts', 'pratikwp'),
            'description' => __('Google Fonts\'u yerel olarak barındır (GDPR uyumlu)', 'pratikwp'),
            'section' => 'pratikwp_typography',
        ]));
        
        // Disable Google Fonts
        $wp_customize->add_setting('disable_google_fonts', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'disable_google_fonts', [
            'label' => __('Google Fonts\'u Devre Dışı Bırak', 'pratikwp'),
            'description' => __('Sadece sistem fontları kullan', 'pratikwp'),
            'section' => 'pratikwp_typography',
        ]));
        
        // Font Smoothing
        $wp_customize->add_setting('enable_font_smoothing', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_font_smoothing', [
            'label' => __('Font Yumuşatma', 'pratikwp'),
            'description' => __('Antialiasing ile daha pürüzsüz fontlar', 'pratikwp'),
            'section' => 'pratikwp_typography',
        ]));
        
        // Custom CSS for Typography
        $wp_customize->add_setting('custom_typography_css', [
            'default' => '',
            'sanitize_callback' => 'wp_strip_all_tags',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('custom_typography_css', [
            'label' => __('Özel Tipografi CSS', 'pratikwp'),
            'description' => __('Ek CSS kodları buraya yazabilirsiniz', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'textarea',
            'input_attrs' => [
                'placeholder' => 'body { font-feature-settings: "liga"; }',
                'rows' => 5,
            ],
        ]);
    }
    
    /**
     * Output Google Fonts in head
     */
    public function output_google_fonts() {
        if (get_theme_mod('disable_google_fonts', false)) {
            return;
        }
        
        $google_fonts = $this->get_google_fonts_list();
        
        if (empty($google_fonts)) {
            return;
        }
        
        $font_families = [];
        $font_display = get_theme_mod('font_display_strategy', 'swap');
        
        foreach ($google_fonts as $font_family => $weights) {
            $weights_string = implode(',', array_unique($weights));
            $font_families[] = $font_family . ':' . $weights_string;
        }
        
        if (!empty($font_families)) {
            $google_fonts_url = 'https://fonts.googleapis.com/css?family=' . 
                              urlencode(implode('|', $font_families)) . 
                              '&display=' . $font_display;
            
            if (get_theme_mod('preload_google_fonts', true)) {
                echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
                echo '<link rel="preload" as="style" href="' . esc_url($google_fonts_url) . '">' . "\n";
                echo '<link rel="stylesheet" href="' . esc_url($google_fonts_url) . '" media="print" onload="this.media=\'all\'">' . "\n";
                echo '<noscript><link rel="stylesheet" href="' . esc_url($google_fonts_url) . '"></noscript>' . "\n";
            } else {
                echo '<link rel="stylesheet" href="' . esc_url($google_fonts_url) . '">' . "\n";
            }
        }
    }
    
    /**
     * Get list of Google Fonts used
     */
    private function get_google_fonts_list() {
        $google_fonts = [];
        $google_font_names = [
            'Open Sans', 'Roboto', 'Lato', 'Montserrat', 'Oswald', 'Source Sans Pro',
            'Raleway', 'PT Sans', 'Ubuntu', 'Merriweather', 'Playfair Display',
            'Nunito', 'Poppins', 'Work Sans', 'Fira Sans', 'Inter', 'Roboto Slab', 'Crimson Text'
        ];
        
        $typography_settings = [
            'body_typography', 'h1_typography', 'h2_typography', 'h3_typography',
            'h456_typography', 'main_menu_typography', 'submenu_typography',
            'button_typography', 'widget_title_typography', 'widget_content_typography'
        ];
        
        foreach ($typography_settings as $setting) {
            $typography = get_theme_mod($setting, '');
            if ($typography) {
                $typography_data = json_decode($typography, true);
                if (isset($typography_data['font_family']) && !empty($typography_data['font_family'])) {
                    $font_family = $typography_data['font_family'];
                    
                    // Check if it's a Google Font
                    if (in_array($font_family, $google_font_names)) {
                        if (!isset($google_fonts[$font_family])) {
                            $google_fonts[$font_family] = [];
                        }
                        
                        $weight = isset($typography_data['font_weight']) ? $typography_data['font_weight'] : '400';
                        $google_fonts[$font_family][] = $weight;
                    }
                }
            }
        }
        
        return $google_fonts;
    }
    
    /**
     * Sanitize float values
     */
    public function sanitize_float($value) {
        return (float) $value;
    }
}