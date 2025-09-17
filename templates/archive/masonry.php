<?php
/**
 * Archive Masonry Template
 * Pinterest-style masonry layout for archive pages
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get archive settings from customizer
$archive_columns = get_theme_mod('archive_masonry_columns', 3);
$show_featured_image = get_theme_mod('archive_show_featured_image', true);
$show_excerpt = get_theme_mod('archive_show_excerpt', true);
$show_meta = get_theme_mod('archive_show_meta', true);
$show_read_more = get_theme_mod('archive_show_read_more', true);
$excerpt_length = get_theme_mod('archive_excerpt_length', 120);
$image_size = get_theme_mod('archive_image_size', 'medium_large');
$enable_infinite_scroll = get_theme_mod('archive_infinite_scroll', false);

// Masonry column classes
$column_classes = [
    2 => 'masonry-col-2',
    3 => 'masonry-col-3', 
    4 => 'masonry-col-4',
    5 => 'masonry-col-5'
];
$column_class = $column_classes[$archive_columns] ?? 'masonry-col-3';
?>

<div class="archive-masonry-layout <?php echo esc_attr($column_class); ?>" 
     data-masonry-columns="<?php echo esc_attr($archive_columns); ?>"
     data-infinite-scroll="<?php echo esc_attr($enable_infinite_scroll ? 'true' : 'false'); ?>">
    
    <?php if (have_posts()): ?>
    
    <!-- Archive Header -->
    <div class="archive-header mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    <div class="archive-title-wrapper text-center">
                        
                        <!-- Archive Title -->
                        <h1 class="archive-title mb-3">
                            <?php
                            if (is_category()) {
                                printf(esc_html__('Kategori: %s', 'pratikwp'), '<span class="archive-term">' . single_cat_title('', false) . '</span>');
                            } elseif (is_tag()) {
                                printf(esc_html__('Etiket: %s', 'pratikwp'), '<span class="archive-term">' . single_tag_title('', false) . '</span>');
                            } elseif (is_author()) {
                                printf(esc_html__('Yazar: %s', 'pratikwp'), '<span class="archive-term">' . get_the_author() . '</span>');
                            } elseif (is_year()) {
                                printf(esc_html__('Yıl: %s', 'pratikwp'), '<span class="archive-term">' . get_the_date('Y') . '</span>');
                            } elseif (is_month()) {
                                printf(esc_html__('Ay: %s', 'pratikwp'), '<span class="archive-term">' . get_the_date('F Y') . '</span>');
                            } elseif (is_day()) {
                                printf(esc_html__('Gün: %s', 'pratikwp'), '<span class="archive-term">' . get_the_date() . '</span>');
                            } else {
                                esc_html_e('Masonry Arşiv', 'pratikwp');
                            }
                            ?>
                        </h1>
                        
                        <!-- Archive Description -->
                        <?php if (is_category() || is_tag() || is_author()): ?>
                        <div class="archive-description mb-4">
                            <?php
                            if (is_category() || is_tag()) {
                                $description = term_description();
                                if ($description) {
                                    echo '<div class="archive-desc lead text-muted">' . wp_kses_post($description) . '</div>';
                                }
                            } elseif (is_author()) {
                                $author_description = get_the_author_meta('description');
                                if ($author_description) {
                                    echo '<div class="archive-desc lead text-muted">' . wp_kses_post($author_description) . '</div>';
                                }
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Archive Filter Info -->
                        <div class="archive-filter-info d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                            
                            <!-- Post Count -->
                            <div class="post-count">
                                <span class="badge bg-primary fs-6">
                                    <?php
                                    global $wp_query;
                                    $total_posts = $wp_query->found_posts;
                                    printf(
                                        esc_html(_n('%d yazı', '%d yazı', $total_posts, 'pratikwp')),
                                        number_format_i18n($total_posts)
                                    );
                                    ?>
                                </span>
                            </div>
                            
                            <!-- Layout Type -->
                            <div class="layout-type">
                                <span class="badge bg-secondary fs-6">
                                    <i class="fas fa-th-large me-1" aria-hidden="true"></i>
                                    <?php esc_html_e('Masonry Görünüm', 'pratikwp'); ?>
                                </span>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <!-- Masonry Grid Container -->
    <div class="masonry-container">
        <div class="container-fluid">
            
            <!-- Grid Sizer (for masonry calculations) -->
            <div class="masonry-grid-sizer"></div>
            
            <!-- Masonry Grid -->
            <div class="masonry-grid" 
                 data-masonry='{"columnWidth": ".masonry-grid-sizer", "itemSelector": ".masonry-item", "gutter": 20, "percentPosition": true, "horizontalOrder": true}'>
                
                <?php while (have_posts()): the_post(); ?>
                
                <div class="masonry-item mb-4">
                    
                    <article id="post-<?php the_ID(); ?>" 
                             <?php post_class('masonry-post-card h-100'); ?> 
                             itemscope itemtype="https://schema.org/Article">
                        
                        <div class="card border-0 shadow-sm h-100">
                            
                            <!-- Featured Image -->
                            <?php if ($show_featured_image && has_post_thumbnail()): ?>
                            <div class="card-img-top-wrapper position-relative">
                                
                                <a href="<?php the_permalink(); ?>" 
                                   class="card-img-link d-block"
                                   aria-label="<?php printf(esc_attr__('"%s" yazısını oku', 'pratikwp'), get_the_title()); ?>">
                                    
                                    <?php
                                    the_post_thumbnail($image_size, [
                                        'class' => 'card-img-top masonry-post-image',
                                        'alt' => get_the_title(),
                                        'itemprop' => 'image',
                                        'loading' => 'lazy'
                                    ]);
                                    ?>
                                    
                                </a>
                                
                                <!-- Post Format Badge -->
                                <?php if (get_post_format()): ?>
                                <div class="post-format-badge position-absolute top-0 start-0 m-2">
                                    <?php
                                    $format_icons = [
                                        'video' => ['icon' => 'fas fa-play', 'color' => 'danger', 'label' => 'Video'],
                                        'audio' => ['icon' => 'fas fa-music', 'color' => 'success', 'label' => 'Ses'],
                                        'gallery' => ['icon' => 'fas fa-images', 'color' => 'info', 'label' => 'Galeri'],
                                        'image' => ['icon' => 'fas fa-camera', 'color' => 'warning', 'label' => 'Resim'],
                                        'quote' => ['icon' => 'fas fa-quote-right', 'color' => 'secondary', 'label' => 'Alıntı'],
                                        'link' => ['icon' => 'fas fa-link', 'color' => 'primary', 'label' => 'Link'],
                                        'status' => ['icon' => 'fas fa-comment', 'color' => 'dark', 'label' => 'Durum'],
                                        'aside' => ['icon' => 'fas fa-file-alt', 'color' => 'light', 'label' => 'Not'],
                                        'chat' => ['icon' => 'fas fa-comments', 'color' => 'success', 'label' => 'Sohbet']
                                    ];
                                    $format = get_post_format();
                                    $format_data = $format_icons[$format] ?? ['icon' => 'fas fa-file-alt', 'color' => 'primary', 'label' => 'Yazı'];
                                    ?>
                                    <span class="badge bg-<?php echo esc_attr($format_data['color']); ?>" 
                                          title="<?php echo esc_attr($format_data['label']); ?>">
                                        <i class="<?php echo esc_attr($format_data['icon']); ?>" aria-hidden="true"></i>
                                    </span>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Category Badge -->
                                <?php if ($show_meta && has_category()): ?>
                                <div class="category-badge position-absolute top-0 end-0 m-2">
                                    <?php
                                    $primary_category = get_the_category()[0];
                                    if ($primary_category) {
                                        echo '<a href="' . esc_url(get_category_link($primary_category->term_id)) . '" class="badge bg-primary text-decoration-none">' . esc_html($primary_category->name) . '</a>';
                                    }
                                    ?>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            <?php endif; ?>
                            
                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                
                                <!-- Post Title -->
                                <h3 class="card-title h6 mb-3">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="text-decoration-none text-dark"
                                       itemprop="headline">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                
                                <!-- Post Excerpt -->
                                <?php if ($show_excerpt): ?>
                                <div class="card-text mb-3 flex-grow-1" itemprop="description">
                                    <p class="text-muted small mb-0">
                                        <?php
                                        if (has_excerpt()) {
                                            echo wp_kses_post(wp_trim_words(get_the_excerpt(), $excerpt_length / 8, '...'));
                                        } else {
                                            echo wp_kses_post(wp_trim_words(get_the_content(), $excerpt_length / 8, '...'));
                                        }
                                        ?>
                                    </p>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Post Meta -->
                                <?php if ($show_meta): ?>
                                <div class="card-meta mt-auto">
                                    
                                    <!-- Author & Date -->
                                    <div class="meta-row d-flex justify-content-between align-items-center mb-2">
                                        
                                        <div class="author-info d-flex align-items-center">
                                            <div class="author-avatar me-2">
                                                <?php echo get_avatar(get_the_author_meta('ID'), 24, '', get_the_author(), ['class' => 'rounded-circle']); ?>
                                            </div>
                                            <div class="author-details">
                                                <small class="text-muted" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                                       class="text-decoration-none text-muted"
                                                       itemprop="name">
                                                        <?php the_author(); ?>
                                                    </a>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="post-date">
                                            <small class="text-muted">
                                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                                                      itemprop="datePublished">
                                                    <?php echo esc_html(get_the_date('j M')); ?>
                                                </time>
                                            </small>
                                        </div>
                                        
                                    </div>
                                    
                                    <!-- Engagement Stats -->
                                    <div class="engagement-stats d-flex justify-content-between align-items-center">
                                        
                                        <div class="stats-left d-flex align-items-center gap-3">
                                            
                                            <!-- Comments -->
                                            <?php if (comments_open() || get_comments_number()): ?>
                                            <div class="comments-count">
                                                <a href="<?php comments_link(); ?>" 
                                                   class="text-decoration-none text-muted small">
                                                    <i class="fas fa-comment me-1" aria-hidden="true"></i>
                                                    <?php echo esc_html(get_comments_number()); ?>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <!-- Reading Time -->
                                            <div class="reading-time">
                                                <span class="text-muted small">
                                                    <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                                    <?php
                                                    $content = get_the_content();
                                                    $word_count = str_word_count(wp_strip_all_tags($content));
                                                    $reading_time = ceil($word_count / 200);
                                                    printf(esc_html__('%d dk', 'pratikwp'), $reading_time);
                                                    ?>
                                                </span>
                                            </div>
                                            
                                        </div>
                                        
                                        <!-- Read More -->
                                        <?php if ($show_read_more): ?>
                                        <div class="stats-right">
                                            <a href="<?php the_permalink(); ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                    
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            
                            <!-- Tags Footer (if has tags) -->
                            <?php if (has_tag() && $show_meta): ?>
                            <div class="card-footer bg-transparent border-top-0 pt-0">
                                <div class="post-tags">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags && count($tags) <= 3) { // Show max 3 tags
                                        echo '<div class="tags-list d-flex flex-wrap gap-1">';
                                        foreach (array_slice($tags, 0, 3) as $tag) {
                                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge bg-light text-dark text-decoration-none small">#' . esc_html($tag->name) . '</a>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                        
                        <!-- Structured Data -->
                        <meta itemprop="url" content="<?php the_permalink(); ?>">
                        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
                            <meta itemprop="name" content="<?php bloginfo('name'); ?>">
                            <meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>">
                        </div>
                        
                    </article>
                    
                </div>
                
                <?php endwhile; ?>
                
            </div>
            
        </div>
    </div>
    
    <!-- Load More / Infinite Scroll -->
    <?php if (!$enable_infinite_scroll): ?>
    <!-- Traditional Pagination -->
    <div class="masonry-pagination mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    the_posts_pagination([
                        'mid_size' => 1,
                        'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Önceki', 'pratikwp'),
                        'next_text' => esc_html__('Sonraki', 'pratikwp') . ' <i class="fas fa-chevron-right"></i>',
                        'before_page_number' => '<span class="screen-reader-text">' . esc_html__('Sayfa', 'pratikwp') . ' </span>',
                        'class' => 'pagination justify-content-center',
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- Load More Button -->
    <div class="masonry-load-more mt-5 text-center">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    global $wp_query;
                    if ($wp_query->max_num_pages > 1):
                    ?>
                    <button class="btn btn-primary btn-lg load-more-btn" 
                            data-page="1" 
                            data-max-pages="<?php echo esc_attr($wp_query->max_num_pages); ?>"
                            data-loading-text="<?php esc_attr_e('Yükleniyor...', 'pratikwp'); ?>">
                        <i class="fas fa-plus me-2" aria-hidden="true"></i>
                        <?php esc_html_e('Daha Fazla Yükle', 'pratikwp'); ?>
                    </button>
                    
                    <!-- Loading Spinner -->
                    <div class="loading-spinner mt-3" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden"><?php esc_html_e('Yükleniyor...', 'pratikwp'); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
    
    <!-- No Posts Found -->
    <div class="masonry-no-posts">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    <div class="no-posts-wrapper text-center py-5">
                        <div class="no-posts-icon mb-4">
                            <i class="fas fa-th-large fa-4x text-muted" aria-hidden="true"></i>
                        </div>
                        
                        <h2 class="no-posts-title mb-3">
                            <?php esc_html_e('Masonry görünümde gösterilecek yazı bulunamadı', 'pratikwp'); ?>
                        </h2>
                        
                        <p class="no-posts-description text-muted mb-4">
                            <?php esc_html_e('Bu arşivde henüz hiç içerik bulunmuyor. Lütfen daha sonra tekrar kontrol edin.', 'pratikwp'); ?>
                        </p>
                        
                        <div class="no-posts-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" 
                               class="btn btn-primary me-3">
                                <i class="fas fa-home me-2" aria-hidden="true"></i>
                                <?php esc_html_e('Ana Sayfa', 'pratikwp'); ?>
                            </a>
                            
                            <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-list me-2" aria-hidden="true"></i>
                                <?php esc_html_e('Tüm Yazılar', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
    
</div>

<!-- Masonry Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Masonry
    if (typeof Masonry !== 'undefined') {
        const grid = document.querySelector('.masonry-grid');
        if (grid) {
            const masonry = new Masonry(grid, {
                columnWidth: '.masonry-grid-sizer',
                itemSelector: '.masonry-item',
                gutter: 20,
                percentPosition: true,
                horizontalOrder: true,
                transitionDuration: '0.3s'
            });
            
            // Reload masonry after images load
            imagesLoaded(grid, function() {
                masonry.layout();
            });
        }
    }
    
    // Load More functionality
    const loadMoreBtn = document.querySelector('.load-more-btn');
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            const currentPage = parseInt(this.dataset.page);
            const maxPages = parseInt(this.dataset.maxPages);
            const nextPage = currentPage + 1;
            
            if (nextPage <= maxPages) {
                loadMorePosts(nextPage, this);
            }
        });
    }
    
    function loadMorePosts(page, button) {
        const loadingSpinner = document.querySelector('.loading-spinner');
        const originalText = button.innerHTML;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + button.dataset.loadingText;
        button.disabled = true;
        if (loadingSpinner) loadingSpinner.style.display = 'block';
        
        // AJAX request to load more posts
        fetch(ajaxurl + '?action=load_more_masonry_posts&page=' + page + '&query=' + encodeURIComponent(JSON.stringify(<?php echo json_encode($wp_query->query_vars); ?>)))
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.html) {
                    // Append new posts to masonry grid
                    const grid = document.querySelector('.masonry-grid');
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.data.html;
                    
                    const newItems = Array.from(tempDiv.children);
                    newItems.forEach(item => grid.appendChild(item));
                    
                    // Update masonry layout
                    if (typeof Masonry !== 'undefined') {
                        const masonry = Masonry.data(grid);
                        if (masonry) {
                            masonry.appended(newItems);
                            imagesLoaded(grid, function() {
                                masonry.layout();
                            });
                        }
                    }
                    
                    // Update button
                    button.dataset.page = page;
                    if (page >= parseInt(button.dataset.maxPages)) {
                        button.style.display = 'none';
                    }
                } else {
                    button.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Load more error:', error);
            })
            .finally(() => {
                button.innerHTML = originalText;
                button.disabled = false;
                if (loadingSpinner) loadingSpinner.style.display = 'none';
            });
    }
});
</script>