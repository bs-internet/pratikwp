<?php
/**
 * Performance Panel for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Customizer_Performance_Panel {
    
    public function __construct() {
        add_action('customize_register', [$this, 'register_panel']);
    }
    
    public function register_panel($wp_customize) {
        // Performance Panel
        $wp_customize->add_panel('pratikwp_performance_panel', [
            'title' => __('Performans Ayarları', 'pratikwp'),
            'description' => __('Site hızı ve performans optimizasyon ayarları', 'pratikwp'),
            'priority' => 80,
            'capability' => 'manage_options',
        ]);
        
        // Asset Optimization Section
        $this->add_asset_optimization_section($wp_customize);
        
        // Image Optimization Section
        $this->add_image_optimization_section($wp_customize);
        
        // Database Optimization Section
        $this->add_database_optimization_section($wp_customize);
        
        // Caching Section
        $this->add_caching_section($wp_customize);
        
        // Code Optimization Section
        $this->add_code_optimization_section($wp_customize);
        
        // Performance Monitoring Section
        $this->add_performance_monitoring_section($wp_customize);
    }
    
    /**
     * Asset Optimization Section
     */
    private function add_asset_optimization_section($wp_customize) {
        $wp_customize->add_section('pratikwp_asset_optimization', [
            'title' => __('Varlık Optimizasyonu', 'pratikwp'),
            'panel' => 'pratikwp_performance_panel',
            'priority' => 10,
        ]);
        
        // Enable CSS Minification
        $wp_customize->add_setting('enable_css_minification', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_css_minification', [
            'label' => __('CSS Sıkıştırma', 'pratikwp'),
            'description' => __('CSS dosyalarını küçültür ve hızlandırır', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Enable JS Minification
        $wp_customize->add_setting('enable_js_minification', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_js_minification', [
            'label' => __('JavaScript Sıkıştırma', 'pratikwp'),
            'description' => __('JavaScript dosyalarını küçültür', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Enable Asset Combination
        $wp_customize->add_setting('enable_asset_combination', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_asset_combination', [
            'label' => __('Dosya Birleştirme', 'pratikwp'),
            'description' => __('CSS ve JS dosyalarını birleştirerek HTTP isteklerini azaltır', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Remove Query Strings
        $wp_customize->add_setting('remove_query_strings', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'remove_query_strings', [
            'label' => __('Query String\'leri Kaldır', 'pratikwp'),
            'description' => __('CSS/JS dosyalarından ?ver= parametrelerini kaldırır', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Enable Critical CSS
        $wp_customize->add_setting('enable_critical_css', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_critical_css', [
            'label' => __('Critical CSS', 'pratikwp'),
            'description' => __('Kritik CSS\'i inline olarak yükler', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Defer Non-Critical CSS
        $wp_customize->add_setting('defer_non_critical_css', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'defer_non_critical_css', [
            'label' => __('Critical Olmayan CSS\'i Ertele', 'pratikwp'),
            'description' => __('Önemli olmayan CSS\'leri sayfa yükendikten sonra yükler', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Enable Script Async/Defer
        $wp_customize->add_setting('enable_script_optimization', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_script_optimization', [
            'label' => __('Script Optimizasyonu', 'pratikwp'),
            'description' => __('JavaScript dosyalarına async/defer ekler', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
        
        // Preload Key Resources
        $wp_customize->add_setting('enable_preloading', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_preloading', [
            'label' => __('Kaynak Ön Yükleme', 'pratikwp'),
            'description' => __('Kritik kaynakları önceden yükler', 'pratikwp'),
            'section' => 'pratikwp_asset_optimization',
        ]));
    }
    
    /**
     * Image Optimization Section
     */
    private function add_image_optimization_section($wp_customize) {
        $wp_customize->add_section('pratikwp_image_optimization', [
            'title' => __('Görsel Optimizasyonu', 'pratikwp'),
            'panel' => 'pratikwp_performance_panel',
            'priority' => 20,
        ]);
        
        // Enable Lazy Loading
        $wp_customize->add_setting('enable_lazy_loading', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_lazy_loading', [
            'label' => __('Lazy Loading', 'pratikwp'),
            'description' => __('Görselleri gecikmeli yükler', 'pratikwp'),
            'section' => 'pratikwp_image_optimization',
        ]));
        
        // Lazy Loading Threshold
        $wp_customize->add_setting('lazy_loading_threshold', [
            'default' => 300,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'lazy_loading_threshold', [
            'label' => __('Lazy Loading Eşiği', 'pratikwp'),
            'description' => __('Görsel yüklenmeden önce viewport\'tan olan mesafe (px)', 'pratikwp'),
            'section' => 'pratikwp_image_optimization',
            'min' => 100,
            'max' => 1000,
            'step' => 50,
            'suffix' => 'px',
            'active_callback' => function() {
                return get_theme_mod('enable_lazy_loading', true);
            },
        ]));
        
        // WebP Support
        $wp_customize->add_setting('enable_webp_support', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_webp_support', [
            'label' => __('WebP Desteği', 'pratikwp'),
            'description' => __('Destekleyen tarayıcılarda WebP formatını kullanır', 'pratikwp'),
            'section' => 'pratikwp_image_optimization',
        ]));
        
        // Image Compression Quality
        $wp_customize->add_setting('image_compression_quality', [
            'default' => 85,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'image_compression_quality', [
            'label' => __('Görsel Sıkıştırma Kalitesi', 'pratikwp'),
            'section' => 'pratikwp_image_optimization',
            'min' => 60,
            'max' => 100,
            'step' => 5,
            'suffix' => '%',
        ]));
        
        // Disable Image Srcset
        $wp_customize->add_setting('disable_image_srcset', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'disable_image_srcset', [
            'label' => __('Srcset\'i Devre Dışı Bırak', 'pratikwp'),
            'description' => __('WordPress\'in otomatik srcset oluşturmasını engeller', 'pratikwp'),
            'section' => 'pratikwp_image_optimization',
        ]));
        
        // Remove Image Metadata
        $wp_customize->add_setting('remove_image_metadata', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'remove_image_metadata', [
            'label' => __('Görsel Meta Verilerini Kaldır', 'pratikwp'),
            'description' => __('EXIF verilerini temizleyerek dosya boyutunu azaltır', 'pratikwp'),
            'section' => 'pratikwp_image_optimization',
        ]));
    }
    
    /**
     * Database Optimization Section
     */
    private function add_database_optimization_section($wp_customize) {
        $wp_customize->add_section('pratikwp_database_optimization', [
            'title' => __('Veritabanı Optimizasyonu', 'pratikwp'),
            'panel' => 'pratikwp_performance_panel',
            'priority' => 30,
        ]);
        
        // Limit Post Revisions
        $wp_customize->add_setting('limit_post_revisions', [
            'default' => 3,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'limit_post_revisions', [
            'label' => __('Yazı Revizyon Limiti', 'pratikwp'),
            'description' => __('0 = sınırsız, 1+ = belirtilen sayıda revizyon', 'pratikwp'),
            'section' => 'pratikwp_database_optimization',
            'min' => 0,
            'max' => 10,
            'step' => 1,
        ]));
        
        // Autosave Interval
        $wp_customize->add_setting('autosave_interval', [
            'default' => 300,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'autosave_interval', [
            'label' => __('Otomatik Kaydetme Aralığı', 'pratikwp'),
            'description' => __('Saniye cinsinden otomatik kaydetme sıklığı', 'pratikwp'),
            'section' => 'pratikwp_database_optimization',
            'min' => 60,
            'max' => 600,
            'step' => 30,
            'suffix' => 's',
        ]));
        
        // Auto Cleanup Database
        $wp_customize->add_setting('auto_cleanup_database', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'auto_cleanup_database', [
            'label' => __('Otomatik Veritabanı Temizliği', 'pratikwp'),
            'description' => __('Spam yorumları ve gereksiz verileri otomatik temizler', 'pratikwp'),
            'section' => 'pratikwp_database_optimization',
        ]));
        
        // Optimize Database Tables
        $wp_customize->add_setting('optimize_database_tables', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'optimize_database_tables', [
            'label' => __('Veritabanı Tablolarını Optimize Et', 'pratikwp'),
            'description' => __('Haftalık olarak veritabanı tablolarını optimize eder', 'pratikwp'),
            'section' => 'pratikwp_database_optimization',
        ]));
        
        // Remove Unused Plugins Data
        $wp_customize->add_setting('remove_unused_plugins_data', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'remove_unused_plugins_data', [
            'label' => __('Kullanılmayan Eklenti Verilerini Kaldır', 'pratikwp'),
            'description' => __('Kaldırılan eklentilerin bıraktığı verileri temizler', 'pratikwp'),
            'section' => 'pratikwp_database_optimization',
        ]));
    }
    
    /**
     * Caching Section
     */
    private function add_caching_section($wp_customize) {
        $wp_customize->add_section('pratikwp_caching', [
            'title' => __('Önbellekleme', 'pratikwp'),
            'panel' => 'pratikwp_performance_panel',
            'priority' => 40,
        ]);
        
        // Enable Browser Caching
        $wp_customize->add_setting('enable_browser_caching', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_browser_caching', [
            'label' => __('Tarayıcı Önbellekleme', 'pratikwp'),
            'description' => __('Statik dosyalar için cache header\'ları ekler', 'pratikwp'),
            'section' => 'pratikwp_caching',
        ]));
        
        // Browser Cache Time
        $wp_customize->add_setting('browser_cache_time', [
            'default' => 30,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'browser_cache_time', [
            'label' => __('Tarayıcı Cache Süresi', 'pratikwp'),
            'section' => 'pratikwp_caching',
            'min' => 7,
            'max' => 365,
            'step' => 7,
            'suffix' => ' gün',
            'active_callback' => function() {
                return get_theme_mod('enable_browser_caching', true);
            },
        ]));
        
        // Enable GZIP Compression
        $wp_customize->add_setting('enable_gzip_compression', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_gzip_compression', [
            'label' => __('GZIP Sıkıştırma', 'pratikwp'),
            'description' => __('Dosyaları sıkıştırarak transfer boyutunu azaltır', 'pratikwp'),
            'section' => 'pratikwp_caching',
        ]));
        
        // Object Caching
        $wp_customize->add_setting('enable_object_caching', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_object_caching', [
            'label' => __('Object Caching', 'pratikwp'),
            'description' => __('Veritabanı sorgularını önbelleğe alır', 'pratikwp'),
            'section' => 'pratikwp_caching',
        ]));
        
        // Cache Preloading
        $wp_customize->add_setting('enable_cache_preloading', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_cache_preloading', [
            'label' => __('Cache Ön Yükleme', 'pratikwp'),
            'description' => __('Sayfaları önceden önbelleğe alır', 'pratikwp'),
            'section' => 'pratikwp_caching',
        ]));
    }
    
    /**
     * Code Optimization Section
     */
    private function add_code_optimization_section($wp_customize) {
        $wp_customize->add_section('pratikwp_code_optimization', [
            'title' => __('Kod Optimizasyonu', 'pratikwp'),
            'panel' => 'pratikwp_performance_panel',
            'priority' => 50,
        ]);
        
        // Remove WordPress Version
        $wp_customize->add_setting('remove_wp_version', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'remove_wp_version', [
            'label' => __('WordPress Sürümünü Gizle', 'pratikwp'),
            'description' => __('HTML\'den WP sürüm bilgisini kaldırır', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
        
        // Disable Emojis
        $wp_customize->add_setting('disable_emojis', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'disable_emojis', [
            'label' => __('Emoji\'leri Devre Dışı Bırak', 'pratikwp'),
            'description' => __('WordPress emoji script\'lerini kaldırır', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
        
        // Disable Embeds
        $wp_customize->add_setting('disable_embeds', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'disable_embeds', [
            'label' => __('Embed\'leri Devre Dışı Bırak', 'pratikwp'),
            'description' => __('WordPress embed özelliklerini kaldırır', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
        
        // Remove RSS Feeds
        $wp_customize->add_setting('remove_rss_feeds', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'remove_rss_feeds', [
            'label' => __('RSS Feed\'leri Kaldır', 'pratikwp'),
            'description' => __('RSS feed link\'lerini head\'den kaldırır', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
        
        // Disable XML-RPC
        $wp_customize->add_setting('disable_xmlrpc', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'disable_xmlrpc', [
            'label' => __('XML-RPC\'yi Devre Dışı Bırak', 'pratikwp'),
            'description' => __('Güvenlik için XML-RPC\'yi kapatır', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
        
        // Remove Shortlink
        $wp_customize->add_setting('remove_shortlink', [
            'default' => true,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'remove_shortlink', [
            'label' => __('Shortlink\'i Kaldır', 'pratikwp'),
            'description' => __('HTML head\'den shortlink\'i kaldırır', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
        
        // Minify HTML
        $wp_customize->add_setting('minify_html', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'minify_html', [
            'label' => __('HTML Küçültme', 'pratikwp'),
            'description' => __('HTML çıktısını küçültür ve temizler', 'pratikwp'),
            'section' => 'pratikwp_code_optimization',
        ]));
    }
    
    /**
     * Performance Monitoring Section
     */
    private function add_performance_monitoring_section($wp_customize) {
        $wp_customize->add_section('pratikwp_performance_monitoring', [
            'title' => __('Performans İzleme', 'pratikwp'),
            'panel' => 'pratikwp_performance_panel',
            'priority' => 60,
        ]);
        
        // Enable Performance Monitoring
        $wp_customize->add_setting('enable_performance_monitoring', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_performance_monitoring', [
            'label' => __('Performans İzlemeyi Etkinleştir', 'pratikwp'),
            'description' => __('Sayfa yükleme sürelerini ve performans metriklerini izler', 'pratikwp'),
            'section' => 'pratikwp_performance_monitoring',
        ]));
        
        // Query Monitor Integration
        $wp_customize->add_setting('enable_query_monitoring', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'enable_query_monitoring', [
            'label' => __('Sorgu İzleme', 'pratikwp'),
            'description' => __('Veritabanı sorgularını izler ve raporlar', 'pratikwp'),
            'section' => 'pratikwp_performance_monitoring',
            'active_callback' => function() {
                return get_theme_mod('enable_performance_monitoring', false);
            },
        ]));
        
        // Performance Budget
        $wp_customize->add_setting('performance_budget', [
            'default' => 3,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Range_Control($wp_customize, 'performance_budget', [
            'label' => __('Performans Bütçesi', 'pratikwp'),
            'description' => __('Maksimum sayfa yükleme süresi (saniye)', 'pratikwp'),
            'section' => 'pratikwp_performance_monitoring',
            'min' => 1,
            'max' => 10,
            'step' => 1,
            'suffix' => 's',
            'active_callback' => function() {
                return get_theme_mod('enable_performance_monitoring', false);
            },
        ]));
        
        // Show Performance Bar
        $wp_customize->add_setting('show_performance_bar', [
            'default' => false,
            'sanitize_callback' => 'rest_sanitize_boolean',
            'transport' => 'refresh',
        ]);
        
        $wp_customize->add_control(new PratikWp_Toggle_Control($wp_customize, 'show_performance_bar', [
            'label' => __('Performans Çubuğunu Göster', 'pratikwp'),
            'description' => __('Admin kullanıcılarına performans bilgilerini gösterir', 'pratikwp'),
            'section' => 'pratikwp_performance_monitoring',
            'active_callback' => function() {
                return get_theme_mod('enable_performance_monitoring', false);
            },
        ]));
    }
}