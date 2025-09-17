<?php
/**
 * Template Functions
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get main content column class
 */
function pratikwp_main_class() {
    if (pratikwp_has_sidebar()) {
        return 'col-md-8';
    }
    return 'col-12';
}

/**
 * Check if page has sidebar
 */
function pratikwp_has_sidebar() {
    // No sidebar on front page if using Elementor
    if (is_front_page() && function_exists('elementor_theme_do_location')) {
        return false;
    }
    
    // Page-specific sidebar settings
    if (is_page()) {
        $sidebar_setting = get_post_meta(get_the_ID(), '_pratikwp_sidebar', true);
        if ($sidebar_setting === 'none') {
            return false;
        }
        if ($sidebar_setting === 'show') {
            return true;
        }
    }
    
    // Default sidebar visibility
    $show_sidebar = get_theme_mod('sidebar_visibility', 'show');
    
    if ($show_sidebar === 'none') {
        return false;
    }
    
    if ($show_sidebar === 'posts_only' && !is_singular('post') && !is_home() && !is_archive()) {
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
 * Default header fallback
 */
function pratikwp_default_header() {
    ?>
    <header id="masthead" class="site-header">
        <?php get_template_part('template-parts/header/top-bar'); ?>
        <?php get_template_part('template-parts/header/main-header'); ?>
    </header>
    <?php
}

/**
 * Default footer fallback
 */
function pratikwp_default_footer() {
    ?>
    <footer id="colophon" class="site-footer">
        <?php get_template_part('template-parts/footer/widgets'); ?>
        <?php get_template_part('template-parts/footer/copyright'); ?>
    </footer>
    <?php
}

/**
 * Page title area
 */
function pratikwp_page_title() {
    if (is_front_page()) {
        return;
    }
    
    $show_page_title = get_theme_mod('show_page_title', true);
    if (!$show_page_title) {
        return;
    }
    
    $title = '';
    $description = '';
    
    if (is_home() && !is_front_page()) {
        $title = get_the_title(get_option('page_for_posts'));
    } elseif (is_archive()) {
        $title = get_the_archive_title();
        $description = get_the_archive_description();
    } elseif (is_search()) {
        $title = sprintf(__('Arama Sonuçları: %s', 'pratikwp'), get_search_query());
    } elseif (is_404()) {
        $title = __('Sayfa Bulunamadı', 'pratikwp');
    } elseif (is_singular()) {
        $title = get_the_title();
    }
    
    if (!$title) {
        return;
    }
    ?>
    <div class="page-title-area bg-light py-4 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title mb-0"><?php echo wp_kses_post($title); ?></h1>
                    <?php if ($description): ?>
                    <div class="page-description mt-2">
                        <?php echo wp_kses_post($description); ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Breadcrumbs
 */
function pratikwp_breadcrumbs() {
    if (is_front_page()) {
        return;
    }
    
    $separator = ' / ';
    $home_title = __('Ana Sayfa', 'pratikwp');
    
    echo '<nav class="breadcrumbs mb-4" aria-label="' . esc_attr__('Breadcrumb', 'pratikwp') . '">';
    echo '<ol class="breadcrumb">';
    
    // Home link
    echo '<li class="breadcrumb-item">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html($home_title) . '</a>';
    echo '</li>';
    
    if (is_category() || is_single()) {
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            echo '</li>';
        }
        
        if (is_single()) {
            echo '<li class="breadcrumb-item active" aria-current="page">';
            echo esc_html(get_the_title());
            echo '</li>';
        }
    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        $ancestors = array_reverse($ancestors);
        
        foreach ($ancestors as $ancestor) {
            echo '<li class="breadcrumb-item">';
            echo '<a href="' . esc_url(get_permalink($ancestor)) . '">' . esc_html(get_the_title($ancestor)) . '</a>';
            echo '</li>';
        }
        
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo esc_html(get_the_title());
        echo '</li>';
    } elseif (is_archive()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo wp_kses_post(get_the_archive_title());
        echo '</li>';
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        printf(__('Arama: %s', 'pratikwp'), esc_html(get_search_query()));
        echo '</li>';
    } elseif (is_404()) {
        echo '<li class="breadcrumb-item active" aria-current="page">';
        echo __('404 - Sayfa Bulunamadı', 'pratikwp');
        echo '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
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