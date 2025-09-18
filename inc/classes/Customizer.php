<?php
/**
 * Customizer Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_customizer']);
        add_action('customize_preview_init', [$this, 'preview_scripts']);
        add_action('wp_head', [$this, 'customizer_css']);
        add_action('customize_controls_enqueue_scripts', [$this, 'controls_scripts']);
    }

    /**
     * Register customizer panels and sections
     */
    public function register_customizer($wp_customize) {
        // Remove default sections we don't need
        $wp_customize->remove_section('colors');
        $wp_customize->remove_section('background_image');
        
        // Add main theme panel
        $wp_customize->add_panel('pratikwp_theme', [
            'title' => __('PratikWp Tema', 'pratikwp'),
            'description' => __('Ana tema ayarları', 'pratikwp'),
            'priority' => 30,
        ]);
        
        // General Settings
        $this->add_general_section($wp_customize);
        
        // Header Settings
        $this->add_header_section($wp_customize);
        
        // Layout Settings
        $this->add_layout_section($wp_customize);
        
        // Colors Settings
        $this->add_colors_section($wp_customize);
        
        // Typography Settings
        $this->add_typography_section($wp_customize);
        
        // Footer Settings
        $this->add_footer_section($wp_customize);
    }

    /**
     * General settings section
     */
    private function add_general_section($wp_customize) {
        $wp_customize->add_section('pratikwp_general', [
            'title' => __('Genel Ayarlar', 'pratikwp'),
            'panel' => 'pratikwp_theme',
            'priority' => 10,
        ]);
        
        // Show breadcrumbs
        $wp_customize->add_setting('show_breadcrumbs', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('show_breadcrumbs', [
            'label' => __('Breadcrumb Göster', 'pratikwp'),
            'section' => 'pratikwp_general',
            'type' => 'checkbox',
        ]);
        
        // Show page title
        $wp_customize->add_setting('show_page_title', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('show_page_title', [
            'label' => __('Sayfa Başlığı Göster', 'pratikwp'),
            'section' => 'pratikwp_general',
            'type' => 'checkbox',
        ]);
        
        // Show post meta
        $wp_customize->add_setting('show_post_meta', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('show_post_meta', [
            'label' => __('Yazı Meta Bilgileri Göster', 'pratikwp'),
            'section' => 'pratikwp_general',
            'type' => 'checkbox',
        ]);
        
        // Excerpt length
        $wp_customize->add_setting('excerpt_length', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('excerpt_length', [
            'label' => __('Özet Uzunluğu (kelime)', 'pratikwp'),
            'section' => 'pratikwp_general',
            'type' => 'number',
            'input_attrs' => [
                'min' => 10,
                'max' => 100,
                'step' => 5,
            ],
        ]);
    }

    /**
     * Header settings section
     */
    private function add_header_section($wp_customize) {
        $wp_customize->add_section('pratikwp_header', [
            'title' => __('Header Ayarları', 'pratikwp'),
            'panel' => 'pratikwp_theme',
            'priority' => 20,
        ]);
        
        // Show top bar
        $wp_customize->add_setting('show_top_bar', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('show_top_bar', [
            'label' => __('Üst Bar Göster', 'pratikwp'),
            'section' => 'pratikwp_header',
            'type' => 'checkbox',
        ]);
        
        // Sticky header
        $wp_customize->add_setting('enable_sticky_header', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('enable_sticky_header', [
            'label' => __('Sabit Header', 'pratikwp'),
            'description' => __('Header\'ın sayfa kaydırıldığında sabit kalması', 'pratikwp'),
            'section' => 'pratikwp_header',
            'type' => 'checkbox',
        ]);
        
        // Header background color
        $wp_customize->add_setting('header_background_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_background_color', [
            'label' => __('Header Arka Plan Rengi', 'pratikwp'),
            'section' => 'pratikwp_header',
        ]));
        
        // Header text color
        $wp_customize->add_setting('header_text_color', [
            'default' => '#000000',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_text_color', [
            'label' => __('Header Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_header',
        ]));
        
        // Logo max height
        $wp_customize->add_setting('logo_max_height', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('logo_max_height', [
            'label' => __('Logo Maksimum Yükseklik (px)', 'pratikwp'),
            'section' => 'pratikwp_header',
            'type' => 'number',
            'input_attrs' => [
                'min' => 30,
                'max' => 150,
                'step' => 5,
            ],
        ]);
    }

    /**
     * Layout settings section
     */
    private function add_layout_section($wp_customize) {
        $wp_customize->add_section('pratikwp_layout', [
            'title' => __('Layout Ayarları', 'pratikwp'),
            'panel' => 'pratikwp_theme',
            'priority' => 30,
        ]);
        
        // Container width
        $wp_customize->add_setting('container_width', [
            'default' => 'container',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('container_width', [
            'label' => __('Container Genişliği', 'pratikwp'),
            'section' => 'pratikwp_layout',
            'type' => 'select',
            'choices' => [
                'container' => __('Sabit Genişlik', 'pratikwp'),
                'container-fluid' => __('Tam Genişlik', 'pratikwp'),
            ],
        ]);
        
        // Sidebar visibility
        $wp_customize->add_setting('sidebar_visibility', [
            'default' => 'show',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('sidebar_visibility', [
            'label' => __('Sidebar Görünürlüğü', 'pratikwp'),
            'section' => 'pratikwp_layout',
            'type' => 'select',
            'choices' => [
                'show' => __('Her yerde göster', 'pratikwp'),
                'posts_only' => __('Sadece yazılarda göster', 'pratikwp'),
                'none' => __('Hiçbir yerde gösterme', 'pratikwp'),
            ],
        ]);
        
        // Archive layout
        $wp_customize->add_setting('archive_layout', [
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('archive_layout', [
            'label' => __('Arşiv Sayfası Düzeni', 'pratikwp'),
            'section' => 'pratikwp_layout',
            'type' => 'select',
            'choices' => [
                'grid' => __('Grid (Izgara)', 'pratikwp'),
                'list' => __('Liste', 'pratikwp'),
                'masonry' => __('Masonry', 'pratikwp'),
            ],
        ]);
    }

    /**
     * Colors settings section
     */
    private function add_colors_section($wp_customize) {
        $wp_customize->add_section('pratikwp_colors', [
            'title' => __('Renkler', 'pratikwp'),
            'panel' => 'pratikwp_theme',
            'priority' => 40,
        ]);
        
        // Primary color
        $wp_customize->add_setting('primary_color', [
            'default' => '#007cba',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'primary_color', [
            'label' => __('Ana Renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Secondary color
        $wp_customize->add_setting('secondary_color', [
            'default' => '#6c757d',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_color', [
            'label' => __('İkincil Renk', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Text color
        $wp_customize->add_setting('text_color', [
            'default' => '#212529',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'text_color', [
            'label' => __('Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
        
        // Link color
        $wp_customize->add_setting('link_color', [
            'default' => '#007cba',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', [
            'label' => __('Link Rengi', 'pratikwp'),
            'section' => 'pratikwp_colors',
        ]));
    }

    /**
     * Typography settings section
     */
    private function add_typography_section($wp_customize) {
        $wp_customize->add_section('pratikwp_typography', [
            'title' => __('Tipografi', 'pratikwp'),
            'panel' => 'pratikwp_theme',
            'priority' => 50,
        ]);
        
        // Body font family
        $wp_customize->add_setting('body_font_family', [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('body_font_family', [
            'label' => __('Gövde Font Ailesi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'select',
            'choices' => $this->get_font_choices(),
        ]);
        
        // Heading font family
        $wp_customize->add_setting('heading_font_family', [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('heading_font_family', [
            'label' => __('Başlık Font Ailesi', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'select',
            'choices' => $this->get_font_choices(),
        ]);
        
        // Body font size
        $wp_customize->add_setting('body_font_size', [
            'default' => 16,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control('body_font_size', [
            'label' => __('Gövde Font Boyutu (px)', 'pratikwp'),
            'section' => 'pratikwp_typography',
            'type' => 'number',
            'input_attrs' => [
                'min' => 12,
                'max' => 24,
                'step' => 1,
            ],
        ]);
    }

    /**
     * Footer settings section
     */
    private function add_footer_section($wp_customize) {
        $wp_customize->add_section('pratikwp_footer', [
            'title' => __('Footer Ayarları', 'pratikwp'),
            'panel' => 'pratikwp_theme',
            'priority' => 60,
        ]);
        
        // Show footer social links
        $wp_customize->add_setting('show_footer_social', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('show_footer_social', [
            'label' => __('Footer Sosyal Medya Linkleri', 'pratikwp'),
            'section' => 'pratikwp_footer',
            'type' => 'checkbox',
        ]);
        
        // Footer background color
        $wp_customize->add_setting('footer_background_color', [
            'default' => '#343a40',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color', [
            'label' => __('Footer Arka Plan Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer',
        ]));
        
        // Footer text color
        $wp_customize->add_setting('footer_text_color', [
            'default' => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_text_color', [
            'label' => __('Footer Yazı Rengi', 'pratikwp'),
            'section' => 'pratikwp_footer',
        ]));
        
        // Copyright text
        $wp_customize->add_setting('copyright_text', [
            'default' => sprintf(__('© %s Tüm hakları saklıdır.', 'pratikwp'), date('Y')),
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('copyright_text', [
            'label' => __('Telif Hakkı Metni', 'pratikwp'),
            'section' => 'pratikwp_footer',
            'type' => 'text',
        ]);
    }

    /**
     * Get font choices
     */
    private function get_font_choices() {
        return [
            '' => __('Varsayılan', 'pratikwp'),
            'Arial, sans-serif' => 'Arial',
            'Helvetica, sans-serif' => 'Helvetica',
            '"Times New Roman", serif' => 'Times New Roman',
            'Georgia, serif' => 'Georgia',
            '"Courier New", monospace' => 'Courier New',
            '"Trebuchet MS", sans-serif' => 'Trebuchet MS',
            'Verdana, sans-serif' => 'Verdana',
            '"Comic Sans MS", cursive' => 'Comic Sans MS',
            'Impact, sans-serif' => 'Impact',
            '"Lucida Console", monospace' => 'Lucida Console',
        ];
    }

    /**
     * Preview scripts
     */
    public function preview_scripts() {
        wp_enqueue_script(
            'pratikwp-customizer-preview',
            PRATIKWP_ASSETS . '/js/customizer-preview.js',
            ['customize-preview'],
            PRATIKWP_VERSION,
            true
        );
    }

    /**
     * Controls scripts
     */
    public function controls_scripts() {
        wp_enqueue_script(
            'pratikwp-customizer-controls',
            PRATIKWP_ASSETS . '/js/customizer-controls.js',
            ['customize-controls'],
            PRATIKWP_VERSION,
            true
        );
    }

    /**
     * Output customizer CSS
     */
    public function customizer_css() {
        ?>
        <style type="text/css" id="pratikwp-customizer-css">
        <?php
        // Primary color
        $primary_color = get_theme_mod('primary_color', '#007cba');
        if ($primary_color !== '#007cba') {
            echo ":root { --bs-primary: {$primary_color}; }";
            echo ".btn-primary { background-color: {$primary_color}; border-color: {$primary_color}; }";
            echo ".text-primary { color: {$primary_color} !important; }";
        }
        
        // Secondary color
        $secondary_color = get_theme_mod('secondary_color', '#6c757d');
        if ($secondary_color !== '#6c757d') {
            echo ":root { --bs-secondary: {$secondary_color}; }";
            echo ".btn-secondary { background-color: {$secondary_color}; border-color: {$secondary_color}; }";
        }
        
        // Text color
        $text_color = get_theme_mod('text_color', '#212529');
        if ($text_color !== '#212529') {
            echo "body { color: {$text_color}; }";
        }
        
        // Link color
        $link_color = get_theme_mod('link_color', '#007cba');
        if ($link_color !== '#007cba') {
            echo "a { color: {$link_color}; }";
        }
        
        // Header background color
        $header_bg = get_theme_mod('header_background_color', '#ffffff');
        if ($header_bg !== '#ffffff') {
            echo ".site-header { background-color: {$header_bg}; }";
        }
        
        // Header text color
        $header_text = get_theme_mod('header_text_color', '#000000');
        if ($header_text !== '#000000') {
            echo ".site-header { color: {$header_text}; }";
            echo ".site-header .navbar-nav .nav-link { color: {$header_text}; }";
        }
        
        // Footer background color
        $footer_bg = get_theme_mod('footer_background_color', '#343a40');
        if ($footer_bg !== '#343a40') {
            echo ".site-footer { background-color: {$footer_bg}; }";
        }
        
        // Footer text color
        $footer_text = get_theme_mod('footer_text_color', '#ffffff');
        if ($footer_text !== '#ffffff') {
            echo ".site-footer { color: {$footer_text}; }";
        }
        
        // Logo max height
        $logo_height = get_theme_mod('logo_max_height', 60);
        if ($logo_height !== 60) {
            echo ".custom-logo { max-height: {$logo_height}px; }";
        }
        
        // Body font family
        $body_font = get_theme_mod('body_font_family', '');
        if (!empty($body_font)) {
            echo "body { font-family: {$body_font}; }";
        }
        
        // Heading font family
        $heading_font = get_theme_mod('heading_font_family', '');
        if (!empty($heading_font)) {
            echo "h1, h2, h3, h4, h5, h6 { font-family: {$heading_font}; }";
        }
        
        // Body font size
        $body_font_size = get_theme_mod('body_font_size', 16);
        if ($body_font_size !== 16) {
            echo "body { font-size: {$body_font_size}px; }";
        }
        ?>
        </style>
        <?php
    }
}