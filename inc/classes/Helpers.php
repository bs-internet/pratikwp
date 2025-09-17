<?php
/**
 * Helpers Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Helpers {
    
    public function __construct() {
        add_shortcode('firma', [$this, 'firma_shortcode']);
        add_shortcode('sosyal', [$this, 'sosyal_shortcode']);
        add_shortcode('slider', [$this, 'slider_shortcode']);
        add_shortcode('breadcrumbs', [$this, 'breadcrumbs_shortcode']);
        add_shortcode('post_meta', [$this, 'post_meta_shortcode']);
        
        // Widget support
        add_action('widgets_init', [$this, 'register_custom_widgets']);
        
        // AJAX handlers
        add_action('wp_ajax_pratikwp_get_posts', [$this, 'ajax_get_posts']);
        add_action('wp_ajax_nopriv_pratikwp_get_posts', [$this, 'ajax_get_posts']);
    }

    /**
     * Get company information
     */
    public static function get_company_info($key, $default = '') {
        $value = get_option($key, $default);
        return !empty($value) ? $value : $default;
    }

    /**
     * Get social media link
     */
    public static function get_social_link($key, $format = 'url') {
        $value = get_option($key);
        
        if (empty($value)) {
            return '';
        }
        
        switch ($format) {
            case 'link':
                $platform = str_replace(['sosyal_', '_'], ['', ' '], $key);
                $platform = ucfirst($platform);
                return sprintf('<a href="%s" title="%s" target="_blank" rel="noopener">%s</a>', 
                    esc_url($value), 
                    esc_attr($platform), 
                    esc_html($platform)
                );
                
            case 'icon_link':
                $platform = str_replace('sosyal_', '', $key);
                $icon = self::get_social_icon($platform);
                return sprintf('<a href="%s" title="%s" target="_blank" rel="noopener" class="social-link social-%s">%s</a>', 
                    esc_url($value), 
                    esc_attr(ucfirst($platform)), 
                    esc_attr($platform),
                    $icon
                );
                
            default:
                return esc_url($value);
        }
    }

    /**
     * Get social media icon
     */
    public static function get_social_icon($platform) {
        $icons = [
            'facebook' => '📘',
            'x' => '❌',
            'linkedin' => '💼',
            'youtube' => '📺',
            'instagram' => '📷',
            'tiktok' => '🎵',
            'pinterest' => '📌',
            'whatsapp' => '💬'
        ];
        
        return isset($icons[$platform]) ? $icons[$platform] : '🔗';
    }

    /**
     * Get all social links
     */
    public static function get_all_social_links($format = 'icon_link') {
        $social_platforms = ['facebook', 'x', 'linkedin', 'youtube', 'instagram', 'tiktok', 'pinterest', 'whatsapp'];
        $links = [];
        
        foreach ($social_platforms as $platform) {
            $link = self::get_social_link("sosyal_$platform", $format);
            if (!empty($link)) {
                $links[$platform] = $link;
            }
        }
        
        return $links;
    }

    /**
     * Format phone number
     */
    public static function format_phone($phone, $format = 'link') {
        if (empty($phone)) {
            return '';
        }
        
        $clean_phone = preg_replace('/[^+\d]/', '', $phone);
        
        switch ($format) {
            case 'link':
                return sprintf('<a href="tel:%s">%s</a>', esc_attr($clean_phone), esc_html($phone));
            case 'clean':
                return $clean_phone;
            default:
                return $phone;
        }
    }

    /**
     * Format email
     */
    public static function format_email($email, $format = 'link') {
        if (empty($email) || !is_email($email)) {
            return '';
        }
        
        switch ($format) {
            case 'link':
                return sprintf('<a href="mailto:%s">%s</a>', esc_attr($email), esc_html($email));
            case 'obfuscated':
                return antispambot($email);
            default:
                return $email;
        }
    }

    /**
     * Get post reading time
     */
    public static function get_reading_time($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $content = get_post_field('post_content', $post_id);
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 words per minute
        
        return $reading_time;
    }

    /**
     * Get estimated reading time text
     */
    public static function get_reading_time_text($post_id = null) {
        $minutes = self::get_reading_time($post_id);
        
        if ($minutes < 1) {
            return __('1 dakikadan az', 'pratikwp');
        }
        
        return sprintf(_n('%d dakika', '%d dakika', $minutes, 'pratikwp'), $minutes);
    }

    /**
     * Get related posts
     */
    public static function get_related_posts($post_id = null, $limit = 3) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $categories = wp_get_post_categories($post_id);
        
        if (empty($categories)) {
            return [];
        }
        
        $args = [
            'post_type' => 'post',
            'posts_per_page' => $limit,
            'post__not_in' => [$post_id],
            'category__in' => $categories,
            'orderby' => 'rand',
            'meta_query' => [
                [
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ]
            ]
        ];
        
        return get_posts($args);
    }

    /**
     * Get breadcrumb items
     */
    public static function get_breadcrumb_items() {
        $items = [];
        
        // Home
        $items[] = [
            'title' => __('Ana Sayfa', 'pratikwp'),
            'url' => home_url('/'),
            'current' => false
        ];
        
        if (is_category() || is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $items[] = [
                    'title' => $category->name,
                    'url' => get_category_link($category->term_id),
                    'current' => is_category()
                ];
            }
            
            if (is_single()) {
                $items[] = [
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'current' => true
                ];
            }
        } elseif (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $items[] = [
                    'title' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                    'current' => false
                ];
            }
            
            $items[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'current' => true
            ];
        } elseif (is_archive()) {
            $items[] = [
                'title' => get_the_archive_title(),
                'url' => '',
                'current' => true
            ];
        } elseif (is_search()) {
            $items[] = [
                'title' => sprintf(__('Arama: %s', 'pratikwp'), get_search_query()),
                'url' => '',
                'current' => true
            ];
        } elseif (is_404()) {
            $items[] = [
                'title' => __('404 - Sayfa Bulunamadı', 'pratikwp'),
                'url' => '',
                'current' => true
            ];
        }
        
        return $items;
    }

    /**
     * Truncate text
     */
    public static function truncate_text($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Generate excerpt
     */
    public static function generate_excerpt($content, $length = 30, $more = '...') {
        $content = strip_tags($content);
        $content = strip_shortcodes($content);
        $words = explode(' ', $content);
        
        if (count($words) <= $length) {
            return $content;
        }
        
        return implode(' ', array_slice($words, 0, $length)) . $more;
    }

    /**
     * Get image sizes
     */
    public static function get_image_sizes() {
        global $_wp_additional_image_sizes;
        
        $sizes = [];
        
        foreach (get_intermediate_image_sizes() as $size) {
            if (in_array($size, ['thumbnail', 'medium', 'medium_large', 'large'])) {
                $sizes[$size] = [
                    'width' => get_option("{$size}_size_w"),
                    'height' => get_option("{$size}_size_h"),
                    'crop' => (bool) get_option("{$size}_crop")
                ];
            } elseif (isset($_wp_additional_image_sizes[$size])) {
                $sizes[$size] = $_wp_additional_image_sizes[$size];
            }
        }
        
        return $sizes;
    }

    /**
     * Check if page is using Elementor
     */
    public static function is_elementor_page($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        if (!class_exists('\Elementor\Plugin')) {
            return false;
        }
        
        return \Elementor\Plugin::$instance->documents->get($post_id)->is_built_with_elementor();
    }

    /**
     * Get post views count
     */
    public static function get_post_views($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $views = get_post_meta($post_id, '_post_views', true);
        return $views ? (int) $views : 0;
    }

    /**
     * Set post views count
     */
    public static function set_post_views($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }
        
        $views = self::get_post_views($post_id);
        update_post_meta($post_id, '_post_views', $views + 1);
    }

    /**
     * Shortcode: [firma key="adres"]
     */
    public function firma_shortcode($atts) {
        $atts = shortcode_atts(['key' => ''], $atts);
        
        if (empty($atts['key'])) {
            return '';
        }
        
        return esc_html(self::get_company_info($atts['key']));
    }

    /**
     * Shortcode: [sosyal key="facebook" format="link"]
     */
    public function sosyal_shortcode($atts) {
        $atts = shortcode_atts([
            'key' => '',
            'format' => 'link'
        ], $atts);
        
        if (empty($atts['key'])) {
            return '';
        }
        
        return self::get_social_link($atts['key'], $atts['format']);
    }

    /**
     * Shortcode: [slider]
     */
    public function slider_shortcode($atts) {
        global $slider_settings;
        
        if ($slider_settings && method_exists($slider_settings, 'slider_shortcode')) {
            return $slider_settings->slider_shortcode($atts);
        }
        
        return '';
    }

    /**
     * Shortcode: [breadcrumbs]
     */
    public function breadcrumbs_shortcode($atts) {
        $atts = shortcode_atts([
            'separator' => ' / ',
            'show_home' => true
        ], $atts);
        
        if (is_front_page()) {
            return '';
        }
        
        $items = self::get_breadcrumb_items();
        $output = '<nav class="breadcrumbs" aria-label="Breadcrumb"><ol class="breadcrumb">';
        
        foreach ($items as $item) {
            if ($item['current']) {
                $output .= '<li class="breadcrumb-item active" aria-current="page">' . esc_html($item['title']) . '</li>';
            } else {
                $output .= '<li class="breadcrumb-item"><a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a></li>';
            }
        }
        
        $output .= '</ol></nav>';
        
        return $output;
    }

    /**
     * Shortcode: [post_meta]
     */
    public function post_meta_shortcode($atts) {
        $atts = shortcode_atts([
            'show_date' => true,
            'show_author' => true,
            'show_category' => true,
            'show_reading_time' => false
        ], $atts);
        
        if (!is_singular('post')) {
            return '';
        }
        
        $output = '<div class="post-meta text-muted">';
        
        if ($atts['show_date']) {
            $output .= '<span class="post-date">📅 ' . get_the_date() . '</span>';
        }
        
        if ($atts['show_author']) {
            $output .= '<span class="post-author ms-3">👤 ' . get_the_author() . '</span>';
        }
        
        if ($atts['show_category'] && has_category()) {
            $output .= '<span class="post-category ms-3">📁 ';
            $categories = get_the_category();
            $cat_links = [];
            foreach ($categories as $category) {
                $cat_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            }
            $output .= implode(', ', $cat_links);
            $output .= '</span>';
        }
        
        if ($atts['show_reading_time']) {
            $output .= '<span class="reading-time ms-3">⏱️ ' . self::get_reading_time_text() . '</span>';
        }
        
        $output .= '</div>';
        
        return $output;
    }

    /**
     * Register custom widgets
     */
    public function register_custom_widgets() {
        // Company info widget would be registered here
        // register_widget('PratikWp_Company_Widget');
    }

    /**
     * AJAX handler for getting posts
     */
    public function ajax_get_posts() {
        check_ajax_referer('pratikwp_nonce', 'nonce');
        
        $args = [
            'post_type' => sanitize_text_field($_POST['post_type'] ?? 'post'),
            'posts_per_page' => absint($_POST['posts_per_page'] ?? 10),
            'paged' => absint($_POST['page'] ?? 1),
            'post_status' => 'publish'
        ];
        
        if (!empty($_POST['category'])) {
            $args['category__in'] = array_map('absint', $_POST['category']);
        }
        
        if (!empty($_POST['exclude'])) {
            $args['post__not_in'] = array_map('absint', $_POST['exclude']);
        }
        
        $query = new WP_Query($args);
        $posts = [];
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $posts[] = [
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'permalink' => get_permalink(),
                    'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                    'date' => get_the_date(),
                    'author' => get_the_author()
                ];
            }
            wp_reset_postdata();
        }
        
        wp_send_json_success([
            'posts' => $posts,
            'max_pages' => $query->max_num_pages,
            'found_posts' => $query->found_posts
        ]);
    }

    /**
     * Get theme option with default
     */
    public static function get_theme_option($option, $default = '') {
        return get_theme_mod($option, get_option($option, $default));
    }

    /**
     * Check if development mode
     */
    public static function is_dev_mode() {
        return defined('WP_DEBUG') && WP_DEBUG;
    }

    /**
     * Generate cache key
     */
    public static function generate_cache_key($prefix, $data) {
        return $prefix . '_' . md5(serialize($data));
    }

    /**
     * Get cached data
     */
    public static function get_cache($key, $default = false) {
        return get_transient($key) ?: $default;
    }

    /**
     * Set cached data
     */
    public static function set_cache($key, $data, $expiration = DAY_IN_SECONDS) {
        return set_transient($key, $data, $expiration);
    }

    /**
     * Delete cached data
     */
    public static function delete_cache($key) {
        return delete_transient($key);
    }
}