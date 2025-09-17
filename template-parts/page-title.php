<?php
/**
 * Template part for displaying page title area
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Don't show on front page
if (is_front_page()) {
    return;
}

// Check if page title should be displayed
$show_page_title = get_theme_mod('show_page_title', true);
if (!$show_page_title) {
    return;
}

// Check for individual page/post setting
if (is_singular()) {
    $hide_title = get_post_meta(get_the_ID(), '_pratikwp_hide_page_title', true);
    if ($hide_title === '1') {
        return;
    }
}

$page_title_style = get_theme_mod('page_title_style', 'default');
$page_title_bg = get_theme_mod('page_title_background', 'light');
$page_title_alignment = get_theme_mod('page_title_alignment', 'left');
$show_breadcrumbs = get_theme_mod('show_breadcrumbs_in_title', true);
$show_page_description = get_theme_mod('show_page_description', true);

// Get title and description
$title = '';
$description = '';
$subtitle = '';

if (is_home() && !is_front_page()) {
    $title = get_the_title(get_option('page_for_posts'));
    $description = get_post_field('post_content', get_option('page_for_posts'));
} elseif (is_archive()) {
    $title = get_the_archive_title();
    $description = get_the_archive_description();
    
    // Clean up archive title (remove "Category:", "Tag:" etc.)
    $title = preg_replace('/^[^:]+: /', '', $title);
} elseif (is_search()) {
    $title = sprintf(__('Arama Sonuçları: "%s"', 'pratikwp'), get_search_query());
    $search_count = $GLOBALS['wp_query']->found_posts;
    $description = sprintf(_n('%s sonuç bulundu', '%s sonuç bulundu', $search_count, 'pratikwp'), number_format_i18n($search_count));
} elseif (is_404()) {
    $title = __('Sayfa Bulunamadı', 'pratikwp');
    $description = __('Aradığınız sayfa mevcut değil veya taşınmış olabilir.', 'pratikwp');
} elseif (is_singular()) {
    $title = get_the_title();
    
    if (is_single()) {
        // For posts, show category as subtitle
        $categories = get_the_category();
        if ($categories) {
            $subtitle = $categories[0]->name;
        }
        
        // Show excerpt or meta description
        if (has_excerpt()) {
            $description = get_the_excerpt();
        }
    } else {
        // For pages, show excerpt if available
        if (has_excerpt()) {
            $description = get_the_excerpt();
        }
    }
}

if (!$title) {
    return;
}

// Background image
$bg_image = '';
if (is_singular()) {
    $bg_image = get_post_meta(get_the_ID(), '_pratikwp_page_title_bg', true);
}
if (!$bg_image) {
    $bg_image = get_theme_mod('page_title_default_bg', '');
}

$container_class = get_theme_mod('page_title_container', 'container');
$text_alignment_class = 'text-' . $page_title_alignment;

// Style classes
$style_classes = [
    'page-title-area',
    'page-title-' . $page_title_style,
    'bg-' . $page_title_bg,
    'text-' . ($page_title_bg === 'light' ? 'dark' : 'light'),
    $text_alignment_class
];

if ($bg_image) {
    $style_classes[] = 'has-background-image';
}

$padding_class = get_theme_mod('page_title_padding', 'py-5');
?>

<div class="<?php echo esc_attr(implode(' ', $style_classes)); ?> <?php echo esc_attr($padding_class); ?>" <?php if ($bg_image) echo 'style="background-image: url(' . esc_url($bg_image) . '); background-size: cover; background-position: center; background-repeat: no-repeat;"'; ?>>
    
    <?php if ($bg_image) : ?>
    <div class="page-title-overlay" style="background: rgba(0,0,0,0.5); position: absolute; top: 0; left: 0; right: 0; bottom: 0;"></div>
    <?php endif; ?>
    
    <div class="<?php echo esc_attr($container_class); ?> position-relative">
        
        <?php if ($show_breadcrumbs && !is_search() && !is_404()) : ?>
        <div class="page-title-breadcrumbs mb-3">
            <?php get_template_part('template-parts/breadcrumbs'); ?>
        </div>
        <?php endif; ?>

        <div class="page-title-content">
            
            <?php if ($subtitle) : ?>
            <div class="page-subtitle mb-2">
                <span class="badge bg-primary"><?php echo esc_html($subtitle); ?></span>
            </div>
            <?php endif; ?>

            <h1 class="page-title mb-0 <?php echo esc_attr(get_theme_mod('page_title_size', 'display-4')); ?>">
                <?php echo wp_kses_post($title); ?>
            </h1>

            <?php if ($description && $show_page_description) : ?>
            <div class="page-description mt-3 <?php echo esc_attr(get_theme_mod('page_description_size', 'lead')); ?>">
                <?php echo wp_kses_post(wp_trim_words($description, 30, '...')); ?>
            </div>
            <?php endif; ?>

            <?php if (is_search()) : ?>
            <div class="search-form-container mt-4">
                <div class="row justify-content-<?php echo esc_attr($page_title_alignment); ?>">
                    <div class="col-lg-6">
                        <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                            <div class="input-group input-group-lg">
                                <input type="search" 
                                       class="form-control" 
                                       placeholder="<?php echo esc_attr_x('Yeni arama yapın...', 'placeholder', 'pratikwp'); ?>" 
                                       value="<?php echo get_search_query(); ?>" 
                                       name="s" 
                                       autocomplete="off">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (is_404()) : ?>
            <div class="error-actions mt-4">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-home me-2"></i>
                    <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                </a>
                <button type="button" class="btn btn-outline-primary btn-lg" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <i class="fas fa-search me-2"></i>
                    <?php esc_html_e('Arama Yap', 'pratikwp'); ?>
                </button>
            </div>
            <?php endif; ?>

            <?php if (is_single() && get_theme_mod('show_post_meta_in_title', false)) : ?>
            <div class="post-meta-title mt-4">
                <div class="d-flex flex-wrap align-items-center text-muted">
                    <span class="meta-item me-4">
                        <i class="fas fa-user me-1"></i>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-decoration-none">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </span>
                    <span class="meta-item me-4">
                        <i class="fas fa-calendar me-1"></i>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date()); ?></time>
                    </span>
                    <?php if (comments_open() || get_comments_number()) : ?>
                    <span class="meta-item me-4">
                        <i class="fas fa-comments me-1"></i>
                        <?php
                        comments_popup_link(
                            esc_html__('Yorum Yok', 'pratikwp'),
                            esc_html__('1 Yorum', 'pratikwp'),
                            esc_html__('% Yorum', 'pratikwp')
                        );
                        ?>
                    </span>
                    <?php endif; ?>
                    
                    <?php if (get_theme_mod('show_reading_time', false)) : ?>
                    <span class="meta-item">
                        <i class="fas fa-clock me-1"></i>
                        <?php
                        $content = get_post_field('post_content', get_the_ID());
                        $word_count = str_word_count(strip_tags($content));
                        $reading_time = ceil($word_count / 200);
                        echo sprintf(_n('%s dakika', '%s dakika', $reading_time, 'pratikwp'), $reading_time);
                        ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

    </div>
</div>