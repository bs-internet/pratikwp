<?php
/**
 * Performance Optimization Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Performance {
    
    public function __construct() {
        add_action('init', [$this, 'init_optimizations']);
        add_action('wp_enqueue_scripts', [$this, 'optimize_scripts'], 999);
        add_action('wp_head', [$this, 'add_dns_prefetch'], 1);
        add_action('wp_head', [$this, 'add_resource_hints'], 2);
        add_action('wp_footer', [$this, 'defer_non_critical_css'], 999);
        
        // Image optimizations
        add_filter('wp_get_attachment_image_attributes', [$this, 'add_lazy_loading'], 10, 2);
        add_filter('the_content', [$this, 'add_lazy_loading_to_content']);
        
        // Database optimizations
        add_action('wp_loaded', [$this, 'optimize_database_queries']);
        
        // Cache optimizations
        add_action('template_redirect', [$this, 'set_cache_headers']);
        
        // Clean up WordPress head
        add_action('init', [$this, 'cleanup_wp_head']);
        
        // Disable unnecessary features
        add_action('init', [$this, 'disable_unnecessary_features']);
        
        // Optimize heartbeat
        add_filter('heartbeat_settings', [$this, 'optimize_heartbeat']);
        
        // Limit post revisions
        add_filter('wp_revisions_to_keep', [$this, 'limit_post_revisions'], 10, 2);
    }

    /**
     * Initialize performance optimizations
     */
    public function init_optimizations() {
        // Enable gzip compression
        if (get_theme_mod('enable_gzip', true) && !ob_get_level()) {
            if (extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
                ob_start('ob_gzhandler');
            }
        }
        
        // Set autosave interval
        $autosave_interval = get_option('autosave_interval', 300);
        if ($autosave_interval && $autosave_interval !== 60) {
            define('AUTOSAVE_INTERVAL', $autosave_interval);
        }
        
        // Limit post revisions
        $revision_limit = get_option('limit_revisions', 3);
        if ($revision_limit >= 0) {
            define('WP_POST_REVISIONS', $revision_limit);
        }
    }

    /**
     * Optimize scripts and styles
     */
    public function optimize_scripts() {
        if (is_admin()) {
            return;
        }
        
        // Remove query strings from static resources
        if (get_theme_mod('remove_query_strings', true)) {
            add_filter('script_loader_src', [$this, 'remove_query_strings'], 15);
            add_filter('style_loader_src', [$this, 'remove_query_strings'], 15);
        }
        
        // Remove unused CSS/JS
        $this->remove_unused_assets();
        
        // Defer non-critical scripts
        $this->defer_non_critical_scripts();
    }

    /**
     * Remove query strings from static resources
     */
    public function remove_query_strings($src) {
        if (strpos($src, '?ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    }

    /**
     * Remove unused assets
     */
    private function remove_unused_assets() {
        // Remove block library CSS if no blocks are used
        if (!has_blocks() && get_theme_mod('remove_unused_css', true)) {
            wp_dequeue_style('wp-block-library');
            wp_dequeue_style('wp-block-library-theme');
            wp_dequeue_style('wc-blocks-style');
        }
        
        // Remove dashicons for non-admin users
        if (!is_admin_bar_showing() && get_theme_mod('remove_dashicons', true)) {
            wp_dequeue_style('dashicons');
        }
        
        // Remove jQuery Migrate
        if (get_theme_mod('remove_jquery_migrate', true)) {
            add_action('wp_default_scripts', [$this, 'remove_jquery_migrate']);
        }
        
        // Remove contact form 7 assets on non-contact pages
        if (class_exists('WPCF7') && !is_page_template('contact.php') && !has_shortcode(get_post()->post_content ?? '', 'contact-form-7')) {
            wp_dequeue_script('contact-form-7');
            wp_dequeue_style('contact-form-7');
        }
    }

    /**
     * Remove jQuery Migrate
     */
    public function remove_jquery_migrate($scripts) {
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];
            if ($script->deps) {
                $script->deps = array_diff($script->deps, ['jquery-migrate']);
            }
        }
    }

    /**
     * Defer non-critical scripts
     */
    private function defer_non_critical_scripts() {
        add_filter('script_loader_tag', function($tag, $handle, $src) {
            // Scripts to defer
            $defer_scripts = [
                'jquery',
                'pratikwp-main',
                'comment-reply'
            ];
            
            if (in_array($handle, $defer_scripts)) {
                return str_replace('<script ', '<script defer ', $tag);
            }
            
            return $tag;
        }, 10, 3);
    }

    /**
     * Add DNS prefetch
     */
    public function add_dns_prefetch() {
        $prefetch_domains = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//ajax.googleapis.com',
            '//cdnjs.cloudflare.com'
        ];
        
        $prefetch_domains = apply_filters('pratikwp_dns_prefetch_domains', $prefetch_domains);
        
        foreach ($prefetch_domains as $domain) {
            echo '<link rel="dns-prefetch" href="' . esc_url($domain) . '">' . "\n";
        }
    }

    /**
     * Add resource hints
     */
    public function add_resource_hints() {
        // Preconnect to external domains
        $preconnect_domains = apply_filters('pratikwp_preconnect_domains', []);
        
        foreach ($preconnect_domains as $domain) {
            echo '<link rel="preconnect" href="' . esc_url($domain) . '" crossorigin>' . "\n";
        }
        
        // Preload critical resources
        if (get_theme_mod('enable_preloading', true)) {
            $this->add_preload_resources();
        }
    }

    /**
     * Add preload resources
     */
    private function add_preload_resources() {
        // Preload critical CSS
        echo '<link rel="preload" href="' . esc_url(get_stylesheet_uri()) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
        echo '<noscript><link rel="stylesheet" href="' . esc_url(get_stylesheet_uri()) . '"></noscript>' . "\n";
        
        // Preload custom logo
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_url = wp_get_attachment_image_url($logo_id, 'full');
            if ($logo_url) {
                echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">' . "\n";
            }
        }
        
        // Preload hero image on front page
        if (is_front_page()) {
            $hero_image = get_theme_mod('hero_background_image');
            if ($hero_image) {
                echo '<link rel="preload" href="' . esc_url($hero_image) . '" as="image">' . "\n";
            }
        }
    }

    /**
     * Defer non-critical CSS
     */
    public function defer_non_critical_css() {
        if (!get_theme_mod('defer_non_critical_css', false)) {
            return;
        }
        
        ?>
        <script>
        // Load non-critical CSS after page load
        window.addEventListener('load', function() {
            var links = [
                // Add non-critical CSS files here
            ];
            
            links.forEach(function(href) {
                var link = document.createElement('link');
                link.rel = 'stylesheet';
                link.href = href;
                document.head.appendChild(link);
            });
        });
        </script>
        <?php
    }

    /**
     * Add lazy loading to images
     */
    public function add_lazy_loading($attr, $attachment) {
        if (!get_theme_mod('enable_lazy_loading', true)) {
            return $attr;
        }
        
        if (isset($attr['src'])) {
            $attr['loading'] = 'lazy';
        }
        
        return $attr;
    }

    /**
     * Add lazy loading to content images
     */
    public function add_lazy_loading_to_content($content) {
        if (!get_theme_mod('enable_lazy_loading', true) || is_admin()) {
            return $content;
        }
        
        // Add loading="lazy" to all images in content
        $content = preg_replace('/<img((?![^>]*loading=)[^>]*)>/i', '<img$1 loading="lazy">', $content);
        
        return $content;
    }

    /**
     * Optimize database queries
     */
    public function optimize_database_queries() {
        // Remove unnecessary queries
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
        
        // Optimize comment queries
        if (!get_theme_mod('enable_comments', true)) {
            add_filter('comments_open', '__return_false', 20, 2);
            add_filter('pings_open', '__return_false', 20, 2);
            add_filter('comments_array', '__return_empty_array', 10, 2);
        }
        
        // Disable unnecessary post type support
        $this->optimize_post_types();
    }

    /**
     * Optimize post types
     */
    private function optimize_post_types() {
        // Remove trackbacks/pingbacks support if not needed
        if (get_theme_mod('disable_trackbacks', true)) {
            add_filter('pings_open', '__return_false');
            add_filter('pre_ping', '__return_false');
        }
        
        // Remove post format support if not used
        if (!current_theme_supports('post-formats')) {
            remove_theme_support('post-formats');
        }
    }

    /**
     * Set cache headers
     */
    public function set_cache_headers() {
        if (is_admin() || is_user_logged_in()) {
            return;
        }
        
        $cache_time = get_theme_mod('browser_cache_time', 2592000); // 30 days default
        
        // Set cache headers for static pages
        if (is_page() || is_front_page()) {
            header('Cache-Control: public, max-age=' . $cache_time);
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $cache_time) . ' GMT');
        }
    }

    /**
     * Clean up WordPress head
     */
    public function cleanup_wp_head() {
        // Remove unnecessary items from wp_head
        if (get_theme_mod('remove_wp_version', true)) {
            remove_action('wp_head', 'wp_generator');
        }
        
        if (get_theme_mod('remove_rsd_link', true)) {
            remove_action('wp_head', 'rsd_link');
        }
        
        if (get_theme_mod('remove_wlw_manifest', true)) {
            remove_action('wp_head', 'wlwmanifest_link');
        }
        
        if (get_theme_mod('remove_shortlink', true)) {
            remove_action('wp_head', 'wp_shortlink_wp_head');
        }
        
        if (get_theme_mod('remove_feed_links', false)) {
            remove_action('wp_head', 'feed_links', 2);
            remove_action('wp_head', 'feed_links_extra', 3);
        }
        
        // Remove REST API links if not needed
        if (get_theme_mod('remove_rest_api_links', true)) {
            remove_action('wp_head', 'rest_output_link_wp_head');
            remove_action('wp_head', 'wp_oembed_add_discovery_links');
        }
    }

    /**
     * Disable unnecessary features
     */
    public function disable_unnecessary_features() {
        // Disable emojis
        if (get_theme_mod('disable_emojis', true)) {
            $this->disable_emojis();
        }
        
        // Disable embeds
        if (get_theme_mod('disable_embeds', false)) {
            $this->disable_embeds();
        }
        
        // Disable XML-RPC
        if (get_theme_mod('disable_xmlrpc', true)) {
            add_filter('xmlrpc_enabled', '__return_false');
        }
        
        // Disable file editing
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
    }

    /**
     * Disable emoji scripts and styles
     */
    private function disable_emojis() {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        
        add_filter('tiny_mce_plugins', function($plugins) {
            return array_diff($plugins, ['wpemoji']);
        });
        
        add_filter('wp_resource_hints', function($urls, $relation_type) {
            if ('dns-prefetch' === $relation_type) {
                $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/');
                $urls = array_diff($urls, [$emoji_svg_url]);
            }
            return $urls;
        }, 10, 2);
    }

    /**
     * Disable embeds
     */
    private function disable_embeds() {
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        remove_action('rest_api_init', 'wp_oembed_register_route');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        add_filter('embed_oembed_discover', '__return_false');
        add_filter('tiny_mce_plugins', function($plugins) {
            return array_diff($plugins, ['wpembed']);
        });
    }

    /**
     * Optimize heartbeat
     */
    public function optimize_heartbeat($settings) {
        // Slow down heartbeat to 60 seconds
        $settings['interval'] = 60;
        
        // Disable heartbeat on frontend
        if (!is_admin()) {
            wp_deregister_script('heartbeat');
        }
        
        return $settings;
    }

    /**
     * Limit post revisions
     */
    public function limit_post_revisions($num, $post) {
        $limit = get_option('limit_revisions', 3);
        return $limit >= 0 ? $limit : $num;
    }

    /**
     * Minify HTML output
     */
    public function minify_html($buffer) {
        if (!get_theme_mod('minify_html', false) || is_admin()) {
            return $buffer;
        }
        
        // Basic HTML minification
        $search = [
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        ];
        
        $replace = [
            '>',
            '<',
            '\\1',
            ''
        ];
        
        return preg_replace($search, $replace, $buffer);
    }

    /**
     * Enable output buffering for HTML minification
     */
    public function start_html_minification() {
        if (get_theme_mod('minify_html', false) && !is_admin()) {
            ob_start([$this, 'minify_html']);
        }
    }

    /**
     * Database cleanup
     */
    public function database_cleanup() {
        if (!get_theme_mod('auto_cleanup_database', false)) {
            return;
        }
        
        global $wpdb;
        
        // Clean up spam comments
        $wpdb->delete($wpdb->comments, ['comment_approved' => 'spam']);
        
        // Clean up trashed comments
        $wpdb->delete($wpdb->comments, ['comment_approved' => 'trash']);
        
        // Clean up expired transients
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%' AND option_value < UNIX_TIMESTAMP()");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%' AND option_name NOT LIKE '_transient_timeout_%' AND option_name NOT IN (SELECT DISTINCT REPLACE(option_name, '_transient_timeout_', '_transient_') FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%')");
        
        // Optimize database tables
        $wpdb->query("OPTIMIZE TABLE {$wpdb->posts}");
        $wpdb->query("OPTIMIZE TABLE {$wpdb->options}");
        $wpdb->query("OPTIMIZE TABLE {$wpdb->comments}");
    }

    /**
     * Get performance score
     */
    public static function get_performance_score() {
        $score = 100;
        
        // Check various performance factors
        if (!get_theme_mod('enable_gzip', true)) $score -= 10;
        if (!get_theme_mod('enable_lazy_loading', true)) $score -= 15;
        if (!get_theme_mod('disable_emojis', true)) $score -= 5;
        if (!get_theme_mod('remove_query_strings', true)) $score -= 5;
        if (get_option('WP_POST_REVISIONS', 10) > 5) $score -= 10;
        
        return max(0, $score);
    }

    /**
     * Get optimization recommendations
     */
    public static function get_optimization_recommendations() {
        $recommendations = [];
        
        if (!get_theme_mod('enable_gzip', true)) {
            $recommendations[] = __('GZIP sıkıştırmayı etkinleştirin', 'pratikwp');
        }
        
        if (!get_theme_mod('enable_lazy_loading', true)) {
            $recommendations[] = __('Görsel lazy loading\'i etkinleştirin', 'pratikwp');
        }
        
        if (!get_theme_mod('disable_emojis', true)) {
            $recommendations[] = __('WordPress emoji scriptlerini devre dışı bırakın', 'pratikwp');
        }
        
        if (get_option('WP_POST_REVISIONS', 10) > 5) {
            $recommendations[] = __('Yazı revizyon sayısını sınırlayın', 'pratikwp');
        }
        
        return $recommendations;
    }
}