<?php
/**
 * Template Hooks
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Header hooks
 */

// Top bar
add_action('pratikwp_header_before', 'pratikwp_render_top_bar', 10);
function pratikwp_render_top_bar() {
    if (!get_theme_mod('show_top_bar', true)) {
        return;
    }
    get_template_part('template-parts/header/top-bar');
}

// Main header
add_action('pratikwp_header', 'pratikwp_render_main_header', 10);
function pratikwp_render_main_header() {
    get_template_part('template-parts/header/main-header');
}

// Mobile menu
add_action('pratikwp_header_after', 'pratikwp_render_mobile_menu', 10);
function pratikwp_render_mobile_menu() {
    if (wp_is_mobile()) {
        get_template_part('template-parts/header/mobile-menu');
    }
}

/**
 * Content hooks
 */

// Page title
add_action('pratikwp_content_before', 'pratikwp_render_page_title', 10);
function pratikwp_render_page_title() {
    if (!is_front_page() && !function_exists('elementor_theme_do_location')) {
        pratikwp_page_title();
    }
}

// Breadcrumbs
add_action('pratikwp_content_before', 'pratikwp_render_breadcrumbs', 20);
function pratikwp_render_breadcrumbs() {
    if (get_theme_mod('show_breadcrumbs', true) && !is_front_page()) {
        pratikwp_breadcrumbs();
    }
}

// Post meta
add_action('pratikwp_entry_header', 'pratikwp_render_post_meta', 10);
function pratikwp_render_post_meta() {
    if (is_singular('post')) {
        pratikwp_post_meta();
    }
}

// Post thumbnail
add_action('pratikwp_entry_content_before', 'pratikwp_render_post_thumbnail', 10);
function pratikwp_render_post_thumbnail() {
    if (has_post_thumbnail() && !is_singular()) {
        ?>
        <div class="post-thumbnail mb-3">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
            </a>
        </div>
        <?php
    } elseif (has_post_thumbnail() && is_singular()) {
        ?>
        <div class="post-thumbnail mb-4">
            <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
        </div>
        <?php
    }
}

// Read more link for excerpts
add_action('pratikwp_entry_content_after', 'pratikwp_render_read_more', 10);
function pratikwp_render_read_more() {
    if (!is_singular() && !is_admin()) {
        ?>
        <div class="read-more mt-3">
            <a href="<?php the_permalink(); ?>" class="btn btn-primary btn-sm">
                <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
            </a>
        </div>
        <?php
    }
}

// Post tags
add_action('pratikwp_entry_footer', 'pratikwp_render_post_tags', 10);
function pratikwp_render_post_tags() {
    if (is_singular('post') && has_tag()) {
        ?>
        <div class="post-tags mt-3">
            <strong><?php esc_html_e('Etiketler:', 'pratikwp'); ?></strong>
            <?php the_tags('<span class="tag-links">', ', ', '</span>'); ?>
        </div>
        <?php
    }
}

// Author bio
add_action('pratikwp_entry_footer', 'pratikwp_render_author_bio', 20);
function pratikwp_render_author_bio() {
    if (is_singular('post') && get_theme_mod('show_author_bio', true)) {
        get_template_part('template-parts/post/author-bio');
    }
}

// Post navigation
add_action('pratikwp_content_after', 'pratikwp_render_post_navigation', 10);
function pratikwp_render_post_navigation() {
    if (is_singular('post')) {
        pratikwp_post_navigation();
    }
}

/**
 * Footer hooks
 */

// Footer widgets
add_action('pratikwp_footer', 'pratikwp_render_footer_widgets', 10);
function pratikwp_render_footer_widgets() {
    get_template_part('template-parts/footer/widgets');
}

// Copyright
add_action('pratikwp_footer', 'pratikwp_render_copyright', 20);
function pratikwp_render_copyright() {
    get_template_part('template-parts/footer/copyright');
}

// Social links
add_action('pratikwp_footer', 'pratikwp_render_social_links', 30);
function pratikwp_render_social_links() {
    if (get_theme_mod('show_footer_social', true)) {
        get_template_part('template-parts/footer/social-links');
    }
}

/**
 * Comment hooks
 */

// Comment form customization
add_filter('comment_form_defaults', 'pratikwp_comment_form_defaults');
function pratikwp_comment_form_defaults($defaults) {
    $defaults['class_form'] = 'comment-form row g-3';
    $defaults['class_submit'] = 'btn btn-primary';
    $defaults['submit_button'] = '<div class="col-12"><input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" /></div>';
    
    return $defaults;
}

// Comment form fields
add_filter('comment_form_default_fields', 'pratikwp_comment_form_fields');
function pratikwp_comment_form_fields($fields) {
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? ' aria-required="true"' : '');
    
    $fields['author'] = sprintf(
        '<div class="col-md-4"><input id="author" name="author" type="text" class="form-control" placeholder="%s" value="%s"%s /></div>',
        esc_attr__('Adınız *', 'pratikwp'),
        esc_attr($commenter['comment_author']),
        $aria_req
    );
    
    $fields['email'] = sprintf(
        '<div class="col-md-4"><input id="email" name="email" type="email" class="form-control" placeholder="%s" value="%s"%s /></div>',
        esc_attr__('E-posta *', 'pratikwp'),
        esc_attr($commenter['comment_author_email']),
        $aria_req
    );
    
    $fields['url'] = sprintf(
        '<div class="col-md-4"><input id="url" name="url" type="url" class="form-control" placeholder="%s" value="%s" /></div>',
        esc_attr__('Web Sitesi', 'pratikwp'),
        esc_attr($commenter['comment_author_url'])
    );
    
    return $fields;
}

// Comment form comment field
add_filter('comment_form_field_comment', 'pratikwp_comment_form_comment_field');
function pratikwp_comment_form_comment_field($comment_field) {
    return sprintf(
        '<div class="col-12"><textarea id="comment" name="comment" class="form-control" rows="5" placeholder="%s" required></textarea></div>',
        esc_attr__('Yorumunuz...', 'pratikwp')
    );
}

/**
 * Archive hooks
 */

// Archive description
add_action('pratikwp_archive_header', 'pratikwp_render_archive_description', 10);
function pratikwp_render_archive_description() {
    if (is_category() || is_tag() || is_tax()) {
        $description = term_description();
        if ($description) {
            echo '<div class="archive-description mt-3">' . wp_kses_post($description) . '</div>';
        }
    }
}

/**
 * Search hooks
 */

// Search form customization
add_filter('get_search_form', 'pratikwp_custom_search_form');
function pratikwp_custom_search_form($form) {
    $form = sprintf(
        '<form role="search" method="get" class="search-form" action="%s">
            <div class="input-group">
                <input type="search" class="form-control search-field" placeholder="%s" value="%s" name="s" aria-label="%s" />
                <button class="btn btn-primary search-submit" type="submit" aria-label="%s">%s</button>
            </div>
        </form>',
        esc_url(home_url('/')),
        esc_attr__('Arama...', 'pratikwp'),
        get_search_query(),
        esc_attr__('Arama', 'pratikwp'),
        esc_attr__('Ara', 'pratikwp'),
        esc_html__('Ara', 'pratikwp')
    );
    
    return $form;
}

/**
 * Widget hooks
 */

// Widget title wrapper
add_filter('dynamic_sidebar_params', 'pratikwp_widget_title_wrapper');
function pratikwp_widget_title_wrapper($params) {
    $params[0]['before_title'] = '<h5 class="widget-title">';
    $params[0]['after_title'] = '</h5>';
    
    return $params;
}

/**
 * Performance hooks
 */

// Remove unnecessary WordPress features
add_action('init', 'pratikwp_cleanup_wp');
function pratikwp_cleanup_wp() {
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
}

// Disable emoji scripts
add_action('init', 'pratikwp_disable_emojis');
function pratikwp_disable_emojis() {
    if (get_theme_mod('disable_emojis', true)) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    }
}

/**
 * SEO hooks
 */

// Add meta description
add_action('wp_head', 'pratikwp_meta_description');
function pratikwp_meta_description() {
    if (is_singular()) {
        $description = get_post_meta(get_the_ID(), '_pratikwp_meta_description', true);
        if (!$description && has_excerpt()) {
            $description = get_the_excerpt();
        }
        if ($description) {
            echo '<meta name="description" content="' . esc_attr(wp_trim_words($description, 25)) . '">' . "\n";
        }
    }
}

// Add Open Graph tags
add_action('wp_head', 'pratikwp_og_tags');
function pratikwp_og_tags() {
    if (get_theme_mod('enable_og_tags', true)) {
        if (is_singular()) {
            echo '<meta property="og:type" content="article">' . "\n";
            echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
            echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
            
            if (has_post_thumbnail()) {
                echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')) . '">' . "\n";
            }
            
            if (has_excerpt()) {
                echo '<meta property="og:description" content="' . esc_attr(get_the_excerpt()) . '">' . "\n";
            }
        }
    }
}