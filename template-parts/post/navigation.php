<?php
/**
 * Template part for displaying post navigation
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only show on single posts
if (!is_single()) {
    return;
}

// Check if post navigation should be displayed
$show_post_navigation = get_theme_mod('show_post_navigation', true);
if (!$show_post_navigation) {
    return;
}

$navigation_style = get_theme_mod('post_navigation_style', 'default');
$same_category = get_theme_mod('post_navigation_same_category', true);
$show_thumbnails = get_theme_mod('post_navigation_show_thumbnails', true);
$show_excerpts = get_theme_mod('post_navigation_show_excerpts', false);

// Get adjacent posts
$prev_post = get_previous_post($same_category);
$next_post = get_next_post($same_category);

// Don't show if no adjacent posts
if (!$prev_post && !$next_post) {
    return;
}

$nav_classes = [
    'post-navigation',
    'post-nav-' . $navigation_style,
    'py-4',
    'my-4',
    'border-top',
    'border-bottom'
];
?>

<nav class="<?php echo esc_attr(implode(' ', $nav_classes)); ?>" aria-label="<?php esc_attr_e('Yazı Navigasyonu', 'pratikwp'); ?>">
    
    <?php if ($navigation_style === 'card') : ?>
    <!-- Card Style -->
    <div class="container-fluid px-0">
        <div class="row g-3">
            
            <?php if ($prev_post) : ?>
            <div class="col-md-6">
                <div class="nav-card card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="nav-direction mb-2">
                            <small class="text-muted text-uppercase fw-bold">
                                <i class="fas fa-chevron-left me-1"></i>
                                <?php esc_html_e('Önceki Yazı', 'pratikwp'); ?>
                            </small>
                        </div>
                        
                        <?php if ($show_thumbnails && has_post_thumbnail($prev_post->ID)) : ?>
                        <div class="nav-thumbnail mb-3">
                            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="d-block">
                                <?php echo get_the_post_thumbnail($prev_post->ID, 'medium', ['class' => 'img-fluid rounded']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <h5 class="nav-title mb-2">
                            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="text-decoration-none text-dark">
                                <?php echo esc_html(get_the_title($prev_post->ID)); ?>
                            </a>
                        </h5>
                        
                        <?php if ($show_excerpts) : ?>
                        <p class="nav-excerpt text-muted small mb-3">
                            <?php echo esc_html(wp_trim_words(get_the_excerpt($prev_post->ID), 20, '...')); ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="nav-meta small text-muted">
                            <time datetime="<?php echo esc_attr(get_the_date('c', $prev_post->ID)); ?>">
                                <?php echo esc_html(get_the_date('', $prev_post->ID)); ?>
                            </time>
                        </div>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div class="col-md-6">
                <div class="nav-card-empty card h-100 border-0 bg-light">
                    <div class="card-body d-flex align-items-center justify-content-center text-muted">
                        <div class="text-center">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <p class="mb-0"><?php esc_html_e('İlk yazıdasınız', 'pratikwp'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($next_post) : ?>
            <div class="col-md-6">
                <div class="nav-card card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="nav-direction mb-2 text-end">
                            <small class="text-muted text-uppercase fw-bold">
                                <?php esc_html_e('Sonraki Yazı', 'pratikwp'); ?>
                                <i class="fas fa-chevron-right ms-1"></i>
                            </small>
                        </div>
                        
                        <?php if ($show_thumbnails && has_post_thumbnail($next_post->ID)) : ?>
                        <div class="nav-thumbnail mb-3">
                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="d-block">
                                <?php echo get_the_post_thumbnail($next_post->ID, 'medium', ['class' => 'img-fluid rounded']); ?>
                            </a>
                        </div>
                        <?php endif; ?>
                        
                        <h5 class="nav-title mb-2">
                            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="text-decoration-none text-dark">
                                <?php echo esc_html(get_the_title($next_post->ID)); ?>
                            </a>
                        </h5>
                        
                        <?php if ($show_excerpts) : ?>
                        <p class="nav-excerpt text-muted small mb-3">
                            <?php echo esc_html(wp_trim_words(get_the_excerpt($next_post->ID), 20, '...')); ?>
                        </p>
                        <?php endif; ?>
                        
                        <div class="nav-meta small text-muted text-end">
                            <time datetime="<?php echo esc_attr(get_the_date('c', $next_post->ID)); ?>">
                                <?php echo esc_html(get_the_date('', $next_post->ID)); ?>
                            </time>
                        </div>
                    </div>
                </div>
            </div>
            <?php else : ?>
            <div class="col-md-6">
                <div class="nav-card-empty card h-100 border-0 bg-light">
                    <div class="card-body d-flex align-items-center justify-content-center text-muted">
                        <div class="text-center">
                            <i class="fas fa-file-alt fa-2x mb-2"></i>
                            <p class="mb-0"><?php esc_html_e('Son yazıdasınız', 'pratikwp'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>

    <?php elseif ($navigation_style === 'minimal') : ?>
    <!-- Minimal Style -->
    <div class="d-flex justify-content-between align-items-center">
        
        <?php if ($prev_post) : ?>
        <div class="nav-prev">
            <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="btn btn-outline-primary">
                <i class="fas fa-chevron-left me-2"></i>
                <?php esc_html_e('Önceki', 'pratikwp'); ?>
            </a>
        </div>
        <?php else : ?>
        <div></div>
        <?php endif; ?>

        <div class="nav-center">
            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-link text-muted">
                <i class="fas fa-th-large me-2"></i>
                <?php esc_html_e('Tüm Yazılar', 'pratikwp'); ?>
            </a>
        </div>

        <?php if ($next_post) : ?>
        <div class="nav-next">
            <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="btn btn-outline-primary">
                <?php esc_html_e('Sonraki', 'pratikwp'); ?>
                <i class="fas fa-chevron-right ms-2"></i>
            </a>
        </div>
        <?php else : ?>
        <div></div>
        <?php endif; ?>

    </div>

    <?php else : ?>
    <!-- Default Style -->
    <div class="row align-items-center">
        
        <?php if ($prev_post) : ?>
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="nav-previous d-flex align-items-center">
                
                <?php if ($show_thumbnails && has_post_thumbnail($prev_post->ID)) : ?>
                <div class="nav-thumb me-3 flex-shrink-0">
                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                        <?php echo get_the_post_thumbnail($prev_post->ID, 'thumbnail', ['class' => 'img-fluid rounded', 'style' => 'width: 60px; height: 60px; object-fit: cover;']); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <div class="nav-content">
                    <div class="nav-direction small text-muted mb-1">
                        <i class="fas fa-chevron-left me-1"></i>
                        <?php esc_html_e('Önceki Yazı', 'pratikwp'); ?>
                    </div>
                    <h6 class="nav-title mb-0">
                        <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="text-decoration-none">
                            <?php echo esc_html(wp_trim_words(get_the_title($prev_post->ID), 8, '...')); ?>
                        </a>
                    </h6>
                    <div class="nav-date small text-muted mt-1">
                        <time datetime="<?php echo esc_attr(get_the_date('c', $prev_post->ID)); ?>">
                            <?php echo esc_html(get_the_date('', $prev_post->ID)); ?>
                        </time>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($next_post) : ?>
        <div class="col-md-6">
            <div class="nav-next d-flex align-items-center <?php echo $prev_post ? 'justify-content-end' : ''; ?>">
                
                <div class="nav-content text-end me-3">
                    <div class="nav-direction small text-muted mb-1">
                        <?php esc_html_e('Sonraki Yazı', 'pratikwp'); ?>
                        <i class="fas fa-chevron-right ms-1"></i>
                    </div>
                    <h6 class="nav-title mb-0">
                        <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="text-decoration-none">
                            <?php echo esc_html(wp_trim_words(get_the_title($next_post->ID), 8, '...')); ?>
                        </a>
                    </h6>
                    <div class="nav-date small text-muted mt-1">
                        <time datetime="<?php echo esc_attr(get_the_date('c', $next_post->ID)); ?>">
                            <?php echo esc_html(get_the_date('', $next_post->ID)); ?>
                        </time>
                    </div>
                </div>
                
                <?php if ($show_thumbnails && has_post_thumbnail($next_post->ID)) : ?>
                <div class="nav-thumb flex-shrink-0">
                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                        <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail', ['class' => 'img-fluid rounded', 'style' => 'width: 60px; height: 60px; object-fit: cover;']); ?>
                    </a>
                </div>
                <?php endif; ?>
                
            </div>
        </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php if (get_theme_mod('show_post_nav_category_info', false) && $same_category) : ?>
    <!-- Category Info -->
    <div class="nav-category-info mt-4 pt-3 border-top text-center">
        <?php
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            $category_count = $category->count;
            echo '<small class="text-muted">';
            printf(
                esc_html__('Bu yazı %1$s kategorisinde bulunuyor. Bu kategoride toplam %2$s yazı var.', 'pratikwp'),
                '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-decoration-none">' . esc_html($category->name) . '</a>',
                '<strong>' . esc_html($category_count) . '</strong>'
            );
            echo '</small>';
        }
        ?>
    </div>
    <?php endif; ?>

</nav>