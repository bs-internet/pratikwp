<?php
/**
 * Layout Panel for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Layout_Panel {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_panel']);
    }
    
    public function register_panel($wp_customize) {
        // Layout Panel
        $wp_customize->add_panel('pratikwp_layout_panel', [
            'title' => __('Layout Ayarları', 'pratikwp'),
            'description' => __('Sayfa düzeni ve container ayarları', 'pratikwp'),
            'priority' => 50,
            'capability' => 'edit_theme_options',
        ]);
        
        // Container Settings Section
        $this->add_container_section($wp_customize);
        
        // Sidebar Settings Section
        $this->add_sidebar_section($wp_customize);
        
        // Archive Layout Section
        $this->add_archive_layout_section($wp_customize);
        
        // Single Post Layout Section
        $this->add_single_layout_section($wp_customize);
        
        // Page Layout Section
        $this->add_page_layout_section($wp_customize);
        
        // Content Spacing Section
        $this->add_content_spacing_section($wp_customize);
    }
    
    /**
     * Container Settings Section
     */
    private function add_container_section($wp_customize) {
        $wp_customize->add_section('pratikwp_container_settings', [
            'title' => __('Container Ayarları', 'pratikwp'),
            'panel' => 'pratikwp_layout_panel',
            'priority' => 10,
        ]);
        
        // Site Layout Type
        $wp_customize->add_setting('site_layout_type', [
            'default' => 'wide',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('site_layout_type', [
            'label' => __('Site Layout Tipi', 'pratikwp'),
            'section' => 'pratikwp_container_settings',
            'type' => 'select',
            'choices' => [
                'boxed' => __('Boxed - Kutulu Tasarım', 'pratikwp'),
                'wide' => __('Wide - Geniş Tasarım', 'pratikwp'),
                'full_width' => __('Full Width - Tam Genişlik', 'pratikwp'),
                'fluid' => __('Fluid - Akışkan', 'pratikwp'),
            ],
        ]);
        
        // Container Max Width
        $wp_customize->add_setting('container_max_width', [
            'default' => 1200,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'container_max_width', [
            'label' => __('Container Maksimum Genişlik', 'pratikwp'),
            'section' => 'pratikwp_container_settings',
            'min' => 960,
            'max' => 1920,
            'step' => 20,
            'suffix' => 'px',
            'active_callback' => function() {
                return !in_array(get_theme_mod('site_layout_type', 'wide'), ['full_width', 'fluid']);
            },
        ]));
        
        // Boxed Layout Max Width
        $wp_customize->add_setting('boxed_layout_max_width', [
            'default' => 1400,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'boxed_layout_max_width', [
            'label' => __('Boxed Layout Maksimum Genişlik', 'pratikwp'),
            'section' => 'pratikwp_container_settings',
            'min' => 1200,
            'max' => 1600,
            'step' => 20,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('site_layout_type', 'wide') === 'boxed';
            },
        ]));
        
        // Container Padding
        $wp_customize->add_setting('container_padding', [
            'default' => 15,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'container_padding', [
            'label' => __('Container Yan Padding', 'pratikwp'),
            'section' => 'pratikwp_container_settings',
            'min' => 0,
            'max' => 50,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Boxed Background
        $wp_customize->add_setting('boxed_background_color', [
            'default' => '#f5f5f5',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'boxed_background_color', [
            'label' => __('Boxed Arka Plan Rengi', 'pratikwp'),
            'section' => 'pratikwp_container_settings',
            'active_callback' => function() {
                return get_theme_mod('site_layout_type', 'wide') === 'boxed';
            },
        ]));
        
        // Boxed Shadow
        $wp_customize->add_setting('boxed_shadow', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'boxed_shadow', [
            'label' => __('Boxed Gölge Efekti', 'pratikwp'),
            'section' => 'pratikwp_container_settings',
            'active_callback' => function() {
                return get_theme_mod('site_layout_type', 'wide') === 'boxed';
            },
        ]));
    }
    
    /**
     * Sidebar Settings Section
     */
    private function add_sidebar_section($wp_customize) {
        $wp_customize->add_section('pratikwp_sidebar_settings', [
            'title' => __('Sidebar Ayarları', 'pratikwp'),
            'panel' => 'pratikwp_layout_panel',
            'priority' => 20,
        ]);
        
        // Global Sidebar Layout
        $wp_customize->add_setting('global_sidebar_layout', [
            'default' => 'right',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('global_sidebar_layout', [
            'label' => __('Genel Sidebar Düzeni', 'pratikwp'),
            'section' => 'pratikwp_sidebar_settings',
            'type' => 'select',
            'choices' => [
                'no_sidebar' => __('Sidebar Yok', 'pratikwp'),
                'left' => __('Sol Sidebar', 'pratikwp'),
                'right' => __('Sağ Sidebar', 'pratikwp'),
                'both' => __('Çift Sidebar', 'pratikwp'),
            ],
        ]);
        
        // Sidebar Width
        $wp_customize->add_setting('sidebar_width', [
            'default' => 300,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'sidebar_width', [
            'label' => __('Sidebar Genişliği', 'pratikwp'),
            'section' => 'pratikwp_sidebar_settings',
            'min' => 250,
            'max' => 400,
            'step' => 10,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('global_sidebar_layout', 'right') !== 'no_sidebar';
            },
        ]));
        
        // Sidebar Gap
        $wp_customize->add_setting('sidebar_gap', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'sidebar_gap', [
            'label' => __('Content - Sidebar Aralığı', 'pratikwp'),
            'section' => 'pratikwp_sidebar_settings',
            'min' => 20,
            'max' => 80,
            'step' => 5,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('global_sidebar_layout', 'right') !== 'no_sidebar';
            },
        ]));
        
        // Sidebar Visibility
        $wp_customize->add_setting('sidebar_visibility', [
            'default' => 'all',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('sidebar_visibility', [
            'label' => __('Sidebar Görünürlüğü', 'pratikwp'),
            'section' => 'pratikwp_sidebar_settings',
            'type' => 'select',
            'choices' => [
                'all' => __('Tüm Sayfalarda', 'pratikwp'),
                'posts_only' => __('Sadece Blog Yazılarında', 'pratikwp'),
                'blog_archive' => __('Blog ve Arşiv Sayfalarında', 'pratikwp'),
                'custom' => __('Özel Ayarlar', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('global_sidebar_layout', 'right') !== 'no_sidebar';
            },
        ]);
        
        // Sidebar Mobile Behavior
        $wp_customize->add_setting('sidebar_mobile_behavior', [
            'default' => 'below',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('sidebar_mobile_behavior', [
            'label' => __('Mobilde Sidebar Davranışı', 'pratikwp'),
            'section' => 'pratikwp_sidebar_settings',
            'type' => 'select',
            'choices' => [
                'below' => __('İçeriğin Altında', 'pratikwp'),
                'hidden' => __('Gizli', 'pratikwp'),
                'offcanvas' => __('Off-canvas (Kaydırmalı)', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('global_sidebar_layout', 'right') !== 'no_sidebar';
            },
        ]);
        
        // Sidebar Sticky
        $wp_customize->add_setting('sidebar_sticky', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'sidebar_sticky', [
            'label' => __('Sticky Sidebar', 'pratikwp'),
            'description' => __('Sidebar kaydırma sırasında sabit kalır', 'pratikwp'),
            'section' => 'pratikwp_sidebar_settings',
            'active_callback' => function() {
                return get_theme_mod('global_sidebar_layout', 'right') !== 'no_sidebar';
            },
        ]));
    }
    
    /**
     * Archive Layout Section
     */
    private function add_archive_layout_section($wp_customize) {
        $wp_customize->add_section('pratikwp_archive_layout', [
            'title' => __('Arşiv Sayfası Düzeni', 'pratikwp'),
            'panel' => 'pratikwp_layout_panel',
            'priority' => 30,
        ]);
        
        // Archive Layout Style
        $wp_customize->add_setting('archive_layout_style', [
            'default' => 'grid',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('archive_layout_style', [
            'label' => __('Arşiv Layout Stili', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
            'type' => 'select',
            'choices' => [
                'list' => __('Liste Görünümü', 'pratikwp'),
                'grid' => __('Grid Görünümü', 'pratikwp'),
                'masonry' => __('Masonry Görünümü', 'pratikwp'),
                'card' => __('Card Görünümü', 'pratikwp'),
                'magazine' => __('Magazin Stili', 'pratikwp'),
            ],
        ]);
        
        // Archive Columns
        $wp_customize->add_setting('archive_columns', [
            'default' => 3,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'archive_columns', [
            'label' => __('Grid Kolon Sayısı', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
            'min' => 1,
            'max' => 4,
            'step' => 1,
            'active_callback' => function() {
                return in_array(get_theme_mod('archive_layout_style', 'grid'), ['grid', 'masonry', 'card']);
            },
        ]));
        
        // Archive Posts Per Page
        $wp_customize->add_setting('archive_posts_per_page', [
            'default' => 12,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'archive_posts_per_page', [
            'label' => __('Sayfa Başına Yazı Sayısı', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
            'min' => 6,
            'max' => 24,
            'step' => 3,
        ]));
        
        // Archive Content Type
        $wp_customize->add_setting('archive_content_type', [
            'default' => 'excerpt',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('archive_content_type', [
            'label' => __('İçerik Gösterimi', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
            'type' => 'select',
            'choices' => [
                'excerpt' => __('Özet', 'pratikwp'),
                'full' => __('Tam İçerik', 'pratikwp'),
                'none' => __('Sadece Başlık', 'pratikwp'),
            ],
        ]);
        
        // Archive Excerpt Length
        $wp_customize->add_setting('archive_excerpt_length', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'archive_excerpt_length', [
            'label' => __('Özet Uzunluğu (kelime)', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
            'min' => 10,
            'max' => 100,
            'step' => 5,
            'active_callback' => function() {
                return get_theme_mod('archive_content_type', 'excerpt') === 'excerpt';
            },
        ]));
        
        // Show Featured Image
        $wp_customize->add_setting('archive_show_featured_image', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'archive_show_featured_image', [
            'label' => __('Öne Çıkan Görsel Göster', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
        ]));
        
        // Show Post Meta
        $wp_customize->add_setting('archive_show_post_meta', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'archive_show_post_meta', [
            'label' => __('Yazı Meta Bilgileri Göster', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
        ]));
        
        // Show Read More Button
        $wp_customize->add_setting('archive_show_read_more', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'archive_show_read_more', [
            'label' => __('Devamını Oku Butonu Göster', 'pratikwp'),
            'section' => 'pratikwp_archive_layout',
        ]));
    }
    
    /**
     * Single Post Layout Section
     */
    private function add_single_layout_section($wp_customize) {
        $wp_customize->add_section('pratikwp_single_layout', [
            'title' => __('Tekil Yazı Düzeni', 'pratikwp'),
            'panel' => 'pratikwp_layout_panel',
            'priority' => 40,
        ]);
        
        // Single Post Layout
        $wp_customize->add_setting('single_post_layout', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('single_post_layout', [
            'label' => __('Tekil Yazı Layout', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
            'type' => 'select',
            'choices' => [
                'default' => __('Varsayılan', 'pratikwp'),
                'wide' => __('Geniş İçerik', 'pratikwp'),
                'full_width' => __('Tam Genişlik', 'pratikwp'),
                'centered' => __('Ortalanmış', 'pratikwp'),
                'minimal' => __('Minimal', 'pratikwp'),
            ],
        ]);
        
        // Single Content Width
        $wp_customize->add_setting('single_content_width', [
            'default' => 100,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'single_content_width', [
            'label' => __('İçerik Genişliği', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
            'min' => 60,
            'max' => 100,
            'step' => 5,
            'suffix' => '%',
            'active_callback' => function() {
                return in_array(get_theme_mod('single_post_layout', 'default'), ['wide', 'centered']);
            },
        ]));
        
        // Show Featured Image
        $wp_customize->add_setting('single_show_featured_image', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'single_show_featured_image', [
            'label' => __('Öne Çıkan Görsel Göster', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
        ]));
        
        // Featured Image Style
        $wp_customize->add_setting('single_featured_image_style', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('single_featured_image_style', [
            'label' => __('Öne Çıkan Görsel Stili', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
            'type' => 'select',
            'choices' => [
                'default' => __('Varsayılan', 'pratikwp'),
                'full_width' => __('Tam Genişlik', 'pratikwp'),
                'hero' => __('Hero Banner', 'pratikwp'),
                'overlay' => __('Başlık Overlay', 'pratikwp'),
            ],
            'active_callback' => function() {
                return get_theme_mod('single_show_featured_image', true);
            },
        ]));
        
        // Show Post Meta
        $wp_customize->add_setting('single_show_post_meta', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'single_show_post_meta', [
            'label' => __('Yazı Meta Bilgileri Göster', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
        ]));
        
        // Show Author Bio
        $wp_customize->add_setting('single_show_author_bio', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'single_show_author_bio', [
            'label' => __('Yazar Bilgisi Göster', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
        ]));
        
        // Show Post Navigation
        $wp_customize->add_setting('single_show_post_navigation', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'single_show_post_navigation', [
            'label' => __('Yazı Navigasyonu Göster', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
        ]));
        
        // Show Related Posts
        $wp_customize->add_setting('single_show_related_posts', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'single_show_related_posts', [
            'label' => __('İlgili Yazılar Göster', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
        ]));
        
        // Related Posts Count
        $wp_customize->add_setting('single_related_posts_count', [
            'default' => 3,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'single_related_posts_count', [
            'label' => __('İlgili Yazı Sayısı', 'pratikwp'),
            'section' => 'pratikwp_single_layout',
            'min' => 2,
            'max' => 6,
            'step' => 1,
            'active_callback' => function() {
                return get_theme_mod('single_show_related_posts', true);
            },
        ]));
    }
    
    /**
     * Page Layout Section
     */
    private function add_page_layout_section($wp_customize) {
        $wp_customize->add_section('pratikwp_page_layout', [
            'title' => __('Sayfa Düzeni', 'pratikwp'),
            'panel' => 'pratikwp_layout_panel',
            'priority' => 50,
        ]);
        
        // Page Layout
        $wp_customize->add_setting('page_layout', [
            'default' => 'default',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control('page_layout', [
            'label' => __('Sayfa Layout', 'pratikwp'),
            'section' => 'pratikwp_page_layout',
            'type' => 'select',
            'choices' => [
                'default' => __('Varsayılan', 'pratikwp'),
                'wide' => __('Geniş İçerik', 'pratikwp'),
                'full_width' => __('Tam Genişlik', 'pratikwp'),
                'centered' => __('Ortalanmış', 'pratikwp'),
                'boxed' => __('Kutulu', 'pratikwp'),
            ],
        ]);
        
        // Page Content Width
        $wp_customize->add_setting('page_content_width', [
            'default' => 100,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'page_content_width', [
            'label' => __('İçerik Genişliği', 'pratikwp'),
            'section' => 'pratikwp_page_layout',
            'min' => 60,
            'max' => 100,
            'step' => 5,
            'suffix' => '%',
            'active_callback' => function() {
                return in_array(get_theme_mod('page_layout', 'default'), ['wide', 'centered']);
            },
        ]));
        
        // Show Page Title
        $wp_customize->add_setting('page_show_title', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'page_show_title', [
            'label' => __('Sayfa Başlığı Göster', 'pratikwp'),
            'section' => 'pratikwp_page_layout',
        ]));
        
        // Show Featured Image
        $wp_customize->add_setting('page_show_featured_image', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'page_show_featured_image', [
            'label' => __('Öne Çıkan Görsel Göster', 'pratikwp'),
            'section' => 'pratikwp_page_layout',
        ]));
        
        // Show Comments
        $wp_customize->add_setting('page_show_comments', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'page_show_comments', [
            'label' => __('Sayfalarda Yorum Sistemi', 'pratikwp'),
            'section' => 'pratikwp_page_layout',
        ]));
    }
    
    /**
     * Content Spacing Section
     */
    private function add_content_spacing_section($wp_customize) {
        $wp_customize->add_section('pratikwp_content_spacing', [
            'title' => __('İçerik Aralıkları', 'pratikwp'),
            'panel' => 'pratikwp_layout_panel',
            'priority' => 60,
        ]);
        
        // Content Top Spacing
        $wp_customize->add_setting('content_top_spacing', [
            'default' => 40,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'content_top_spacing', [
            'label' => __('İçerik Üst Aralığı', 'pratikwp'),
            'section' => 'pratikwp_content_spacing',
            'min' => 0,
            'max' => 100,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Content Bottom Spacing
        $wp_customize->add_setting('content_bottom_spacing', [
            'default' => 40,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'content_bottom_spacing', [
            'label' => __('İçerik Alt Aralığı', 'pratikwp'),
            'section' => 'pratikwp_content_spacing',
            'min' => 0,
            'max' => 100,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Element Spacing
        $wp_customize->add_setting('element_spacing', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'element_spacing', [
            'label' => __('Elementler Arası Aralık', 'pratikwp'),
            'section' => 'pratikwp_content_spacing',
            'min' => 10,
            'max' => 80,
            'step' => 5,
            'suffix' => 'px',
        ]));
        
        // Section Spacing
        $wp_customize->add_setting('section_spacing', [
            'default' => 60,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'section_spacing', [
            'label' => __('Bölümler Arası Aralık', 'pratikwp'),
            'section' => 'pratikwp_content_spacing',
            'min' => 30,
            'max' => 120,
            'step' => 10,
            'suffix' => 'px',
        ]));
        
        // Mobile Spacing Reduction
        $wp_customize->add_setting('mobile_spacing_reduction', [
            'default' => 50,
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'mobile_spacing_reduction', [
            'label' => __('Mobil Aralık Azaltma', 'pratikwp'),
            'description' => __('Mobil cihazlarda aralıkların ne kadar azaltılacağı (%)', 'pratikwp'),
            'section' => 'pratikwp_content_spacing',
            'min' => 20,
            'max' => 80,
            'step' => 10,
            'suffix' => '%',
        ]));
    }
}