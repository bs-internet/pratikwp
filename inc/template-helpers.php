<?php
/**
 * Template Helper Functions
 * These functions are not meant to be replaced, but their output can be filtered.
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get main content column class (Filterable)
 */
function pratikwp_main_class() {
    $class = 'col-12';
    if (pratikwp_has_sidebar()) {
        $class = 'col-md-8';
    }
    return apply_filters('pratikwp_main_class', $class);
}

/**
 * Get sidebar column class (Filterable)
 */
function pratikwp_sidebar_class() {
    $class = '';
    if (pratikwp_has_sidebar()) {
        $class = 'col-md-4';
    }
    return apply_filters('pratikwp_sidebar_class', $class);
}

/**
 * Check if page has sidebar
 */
function pratikwp_has_sidebar() {
    $post_id = get_the_ID();

    if (is_singular() && $post_id) {
        if (did_action('elementor/loaded') && \Elementor\Plugin::$instance->db->is_built_with_elementor($post_id)) {
            $sidebar_position = get_post_meta($post_id, '_pratikwp_sidebar_position', true);
            
            if ($sidebar_position === 'left' || $sidebar_position === 'right') {
                return is_active_sidebar('main-sidebar');
            }
            return false;
        }

        $sidebar_position = get_post_meta($post_id, '_pratikwp_sidebar_position', true);
        if ($sidebar_position === 'none') {
            return false;
        }
        if ($sidebar_position === 'left' || $sidebar_position === 'right') {
            return is_active_sidebar('main-sidebar');
        }
    }
    
    $show_sidebar_globally = get_theme_mod('show_sidebar', true);
    if (!$show_sidebar_globally) {
        return false;
    }

    return is_active_sidebar('main-sidebar');
}

/**
 * Posts pagination
 */
function pratikwp_posts_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $args = [
        'mid_size'  => 2,
        'prev_text' => __('&larr; Önceki', 'pratikwp'),
        'next_text' => __('Sonraki &rarr;', 'pratikwp'),
        'class'     => 'pagination justify-content-center mt-4',
    ];
    
    echo '<nav class="posts-pagination" aria-label="' . esc_attr__('Sayfa navigasyonu', 'pratikwp') . '">';
    echo '<ul class="pagination justify-content-center">';
    
    $pagination = paginate_links(array_merge($args, [
        'type' => 'array'
    ]));
    
    if ($pagination) {
        foreach ($pagination as $page) {
            $class = 'page-item';
            if (strpos($page, 'current') !== false) {
                $class .= ' active';
            }
            
            $page = str_replace('page-numbers', 'page-link', $page);
            echo '<li class="' . $class . '">' . $page . '</li>';
        }
    }
    
    echo '</ul></nav>';
}

/**
 * Single post navigation
 */
function pratikwp_post_navigation() {
    if (!is_singular('post')) {
        return;
    }
    
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (!$prev_post && !$next_post) {
        return;
    }
    ?>
    <nav class="post-navigation mt-5" aria-label="<?php esc_attr_e('Yazı navigasyonu', 'pratikwp'); ?>">
        <div class="row">
            <?php if ($prev_post): ?>
            <div class="col-md-6">
                <div class="nav-previous">
                    <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="btn btn-outline-primary">
                        &larr; <?php echo esc_html(get_the_title($prev_post)); ?>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if ($next_post): ?>
            <div class="col-md-6 text-end">
                <div class="nav-next">
                    <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="btn btn-outline-primary">
                        <?php echo esc_html(get_the_title($next_post)); ?> &rarr;
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
}

/**
 * Custom comment callback
 */
function pratikwp_comment_callback($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    
    switch ($comment->comment_type) {
        case 'pingback':
        case 'trackback':
            ?>
            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('media'); ?>>
                <div class="media-body">
                    <?php _e('Pingback:', 'pratikwp'); ?> <?php comment_author_link(); ?>
                    <?php edit_comment_link(__('Düzenle', 'pratikwp'), '<span class="edit-link">', '</span>'); ?>
                </div>
            <?php
            break;
            
        default:
            ?>
            <li id="comment-<?php comment_ID(); ?>" <?php comment_class('media mb-4'); ?>>
                <div class="media-object me-3">
                    <?php echo get_avatar($comment, 60, '', '', ['class' => 'rounded']); ?>
                </div>
                <div class="media-body">
                    <h6 class="media-heading">
                        <?php comment_author(); ?>
                        <small class="text-muted ms-2">
                            <time datetime="<?php comment_time('c'); ?>">
                                <?php printf(__('%1$s %2$s', 'pratikwp'), get_comment_date(), get_comment_time()); ?>
                            </time>
                        </small>
                    </h6>
                    
                    <?php if ($comment->comment_approved == '0'): ?>
                    <p class="alert alert-warning">
                        <?php _e('Yorumunuz onay bekliyor.', 'pratikwp'); ?>
                    </p>
                    <?php endif; ?>
                    
                    <div class="comment-content">
                        <?php comment_text(); ?>
                    </div>
                    
                    <div class="comment-actions">
                        <?php 
                        comment_reply_link(array_merge($args, [
                            'depth' => $depth,
                            'max_depth' => $args['max_depth'],
                            'reply_text' => __('Yanıtla', 'pratikwp'),
                            'class' => 'btn btn-sm btn-outline-primary'
                        ]));
                        ?>
                        <?php edit_comment_link(__('Düzenle', 'pratikwp'), '<span class="edit-link ms-2">', '</span>'); ?>
                    </div>
                </div>
            <?php
            break;
    }
}

/**
 * Get post meta
 */
function pratikwp_post_meta() {
    if (!is_singular('post')) {
        return;
    }
    
    $show_meta = get_theme_mod('show_post_meta', true);
    if (!$show_meta) {
        return;
    }
    ?>
    <div class="post-meta text-muted mb-3">
        <span class="posted-on">
            📅
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
        </span>
        
        <span class="byline ms-3">
            👤
            <span class="author vcard">
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                    <?php echo esc_html(get_the_author()); ?>
                </a>
            </span>
        </span>
        
        <?php if (has_category()): ?>
        <span class="cat-links ms-3">
            📁
            <?php the_category(', '); ?>
        </span>
        <?php endif; ?>
        
        <?php if (comments_open() || get_comments_number()): ?>
        <span class="comments-link ms-3">
            💬
            <a href="<?php echo esc_url(get_comments_link()); ?>">
                <?php 
                printf(
                    _n('%s Yorum', '%s Yorum', get_comments_number(), 'pratikwp'),
                    number_format_i18n(get_comments_number())
                ); 
                ?>
            </a>
        </span>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Add custom body class from page settings to the body tag.
 */
function pratikwp_add_custom_body_class($classes) {
    if (is_singular()) {
        $post_id = get_the_ID();
        if ($post_id) {
            $custom_class = get_post_meta($post_id, '_pratikwp_custom_body_class', true);
            
            if (!empty($custom_class)) {
                $custom_classes = explode(' ', $custom_class);
                $classes = array_merge($classes, $custom_classes);
            }
        }
    }
    return $classes;
}
add_filter('body_class', 'pratikwp_add_custom_body_class');

/**
 * Excerpt length
 */
function pratikwp_excerpt_length($length) {
    return get_theme_mod('excerpt_length', 30);
}
add_filter('excerpt_length', 'pratikwp_excerpt_length');

/**
 * Excerpt more
 */
function pratikwp_excerpt_more($more) {
    return get_theme_mod('excerpt_more', '...');
}
add_filter('excerpt_more', 'pratikwp_excerpt_more');

/**
 * Belirtilen anahtara göre firma bilgisini getirir.
 */
function pratikwp_get_company_info(string $key = ''): string
{
    $company_info = get_option('pratikwp_company_info', []);
    if (empty($key)) { return ''; }
    if ($key === 'full_address') {
        $address_parts = [
            $company_info['address'] ?? '',
            $company_info['district'] ?? '',
            $company_info['city'] ?? ''
        ];
        return implode(' ', array_filter($address_parts));
    }
    return $company_info[$key] ?? '';
}

/**
 * `pratikwp_get_company_info` fonksiyonu için takma ad.
 */
function firma_bilgi(string $key = ''): string
{
    return pratikwp_get_company_info($key);
}

/**
 * Firma bilgilerini göstermek için shortcode.
 */
function pratikwp_company_info_shortcode($atts): string
{
    $atts = shortcode_atts([
        'goster' => 'name,full_address,phone1,gsm1,email', // Varsayılanlar güncellendi
        'tek'    => '',
    ], $atts, 'pratikwp_firma_bilgisi');

    if (!empty($atts['tek'])) {
        return esc_html(pratikwp_get_company_info($atts['tek']));
    }

    $fields_to_show = array_map('trim', explode(',', $atts['goster']));
    
    $labels = [
        'name'         => __('Firma Adı', 'pratikwp'),
        'full_address' => __('Adres', 'pratikwp'),
        'address'      => __('Açık Adres', 'pratikwp'),
        'district'     => __('İlçe', 'pratikwp'),
        'city'         => __('İl', 'pratikwp'),        
        'phone1'       => __('Sabit Telefon 1', 'pratikwp'),
        'phone2'       => __('Sabit Telefon 2', 'pratikwp'),
        'gsm1'         => __('GSM 1', 'pratikwp'),
        'gsm2'         => __('GSM 2', 'pratikwp'),
        'email'        => __('E-posta', 'pratikwp'),
    ];

    ob_start();
    echo '<div class="pratikwp-company-info-shortcode"><ul>';

    foreach ($fields_to_show as $field) {
        $value = pratikwp_get_company_info($field);
        if (!empty($value)) {
            echo '<li>';
            echo '<strong>' . esc_html($labels[$field] ?? ucfirst($field)) . ':</strong> ';
            
            if ($field === 'email') {
                echo '<a href="mailto:' . esc_attr($value) . '">' . esc_html($value) . '</a>';
            } elseif (str_starts_with($field, 'phone') || str_starts_with($field, 'gsm')) {
                echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $value)) . '">' . esc_html($value) . '</a>';
            } else {
                echo nl2br(esc_html($value));
            }
            
            echo '</li>';
        }
    }

    echo '</ul></div>';
    
    return ob_get_clean();
}
add_shortcode('pratikwp_firma_bilgisi', 'pratikwp_company_info_shortcode');

function pratikwp_get_social_link(string $platform = ''): string
{
    $social_links = get_option('pratikwp_social_links', []);
    return $social_links[$platform] ?? '';
}

function sosyal_medya(string $platform = ''): string
{
    return pratikwp_get_social_link($platform);
}

function pratikwp_get_social_icon_class(string $platform): string
{
    $icons = [
        'facebook'  => 'fab fa-facebook-f',
        'twitter'   => 'fab fa-twitter',
        'instagram' => 'fab fa-instagram',
        'linkedin'  => 'fab fa-linkedin-in',
        'youtube'   => 'fab fa-youtube',
        'tiktok'    => 'fab fa-tiktok',
    ];
    return $icons[$platform] ?? 'fas fa-link';
}

function pratikwp_social_media_shortcode($atts): string
{
    $atts = shortcode_atts([
        'goster'       => 'facebook,twitter,instagram',
        'stil'         => 'icons',
        'yeni_pencere' => 'yes',
    ], $atts, 'pratikwp_sosyal_medya');

    $platforms_to_show = array_map('trim', explode(',', $atts['goster']));
    $target = ($atts['yeni_pencere'] === 'yes') ? '_blank' : '_self';

    ob_start();
    echo '<div class="pratikwp-social-media">';

    foreach ($platforms_to_show as $platform) {
        $link = sosyal_medya($platform);
        if (!empty($link)) {
            ?>
            <a href="<?php echo esc_url($link); ?>" 
               target="<?php echo esc_attr($target); ?>" 
               class="social-link social-<?php echo esc_attr($platform); ?>"
               aria-label="<?php echo esc_attr(ucfirst($platform)); ?>"
               rel="noopener noreferrer">
                
                <?php if (in_array($atts['stil'], ['icons', 'both'])) : ?>
                    <i class="social-icon <?php echo esc_attr(pratikwp_get_social_icon_class($platform)); ?>"></i>
                <?php endif; ?>
                
                <?php if (in_array($atts['stil'], ['text', 'both'])) : ?>
                    <span class="social-text"><?php echo esc_html(ucfirst($platform)); ?></span>
                <?php endif; ?>
            </a>
            <?php
        }
    }

    echo '</div>';
    return ob_get_clean();
}
add_shortcode('pratikwp_sosyal_medya', 'pratikwp_social_media_shortcode');