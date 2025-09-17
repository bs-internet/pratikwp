<?php
/**
 * Archive List Template
 * List layout for archive pages (category, tag, author, date)
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get archive settings from customizer
$show_featured_image = get_theme_mod('archive_show_featured_image', true);
$show_excerpt = get_theme_mod('archive_show_excerpt', true);
$show_meta = get_theme_mod('archive_show_meta', true);
$show_read_more = get_theme_mod('archive_show_read_more', true);
$excerpt_length = get_theme_mod('archive_excerpt_length', 200);
$image_size = get_theme_mod('archive_image_size', 'medium');
$list_style = get_theme_mod('archive_list_style', 'horizontal'); // horizontal or vertical
?>

<div class="archive-list-layout archive-list-<?php echo esc_attr($list_style); ?>">
    
    <?php if (have_posts()): ?>
    
    <!-- Archive Header -->
    <div class="archive-header mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    <div class="archive-title-wrapper">
                        
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
                                esc_html_e('Arşiv', 'pratikwp');
                            }
                            ?>
                        </h1>
                        
                        <!-- Archive Description -->
                        <?php if (is_category() || is_tag() || is_author()): ?>
                        <div class="archive-description mb-3">
                            <?php
                            if (is_category() || is_tag()) {
                                $description = term_description();
                                if ($description) {
                                    echo '<div class="archive-desc text-muted">' . wp_kses_post($description) . '</div>';
                                }
                            } elseif (is_author()) {
                                $author_description = get_the_author_meta('description');
                                if ($author_description) {
                                    echo '<div class="archive-desc text-muted">' . wp_kses_post($author_description) . '</div>';
                                }
                                
                                // Author info
                                echo '<div class="author-info d-flex align-items-center mt-3">';
                                echo '<div class="author-avatar me-3">';
                                echo get_avatar(get_the_author_meta('ID'), 60, '', get_the_author(), ['class' => 'rounded-circle']);
                                echo '</div>';
                                echo '<div class="author-details">';
                                echo '<h6 class="author-name mb-1">' . esc_html(get_the_author()) . '</h6>';
                                echo '<div class="author-posts-count text-muted small">';
                                printf(
                                    esc_html(_n('%d yazı', '%d yazı', count_user_posts(get_the_author_meta('ID')), 'pratikwp')),
                                    number_format_i18n(count_user_posts(get_the_author_meta('ID')))
                                );
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Archive Stats -->
                        <div class="archive-stats d-flex align-items-center flex-wrap gap-3">
                            
                            <!-- Post Count -->
                            <div class="post-count">
                                <span class="badge bg-primary">
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
                            
                            <!-- Archive Type -->
                            <div class="archive-type">
                                <span class="badge bg-secondary">
                                    <?php
                                    if (is_category()) {
                                        esc_html_e('Kategori', 'pratikwp');
                                    } elseif (is_tag()) {
                                        esc_html_e('Etiket', 'pratikwp');
                                    } elseif (is_author()) {
                                        esc_html_e('Yazar', 'pratikwp');
                                    } elseif (is_date()) {
                                        esc_html_e('Tarih', 'pratikwp');
                                    } else {
                                        esc_html_e('Arşiv', 'pratikwp');
                                    }
                                    ?>
                                </span>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <!-- Archive Content -->
    <div class="archive-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    <div class="archive-posts-list">
                        
                        <?php $post_count = 0; ?>
                        <?php while (have_posts()): the_post(); ?>
                        <?php $post_count++; ?>
                        
                        <article id="post-<?php the_ID(); ?>" 
                                 <?php post_class('archive-post-item mb-4 pb-4 border-bottom'); ?> 
                                 itemscope itemtype="https://schema.org/Article">
                            
                            <?php if ($list_style === 'horizontal'): ?>
                            
                            <!-- Horizontal Layout -->
                            <div class="row align-items-center">
                                
                                <!-- Featured Image -->
                                <?php if ($show_featured_image && has_post_thumbnail()): ?>
                                <div class="col-lg-4 col-md-5 col-12 mb-3 mb-md-0">
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>" 
                                           class="post-thumbnail-link d-block"
                                           aria-label="<?php printf(esc_attr__('"%s" yazısını oku', 'pratikwp'), get_the_title()); ?>">
                                            
                                            <?php
                                            the_post_thumbnail($image_size, [
                                                'class' => 'img-fluid post-thumbnail-img',
                                                'alt' => get_the_title(),
                                                'itemprop' => 'image',
                                                'loading' => 'lazy'
                                            ]);
                                            ?>
                                            
                                            <!-- Post Format Overlay -->
                                            <?php if (get_post_format()): ?>
                                            <div class="post-format-overlay">
                                                <?php
                                                $format_icons = [
                                                    'video' => 'fas fa-play-circle',
                                                    'audio' => 'fas fa-music',
                                                    'gallery' => 'fas fa-images',
                                                    'image' => 'fas fa-camera',
                                                    'quote' => 'fas fa-quote-right',
                                                    'link' => 'fas fa-external-link-alt',
                                                    'status' => 'fas fa-comment-dots',
                                                    'aside' => 'fas fa-file-text',
                                                    'chat' => 'fas fa-comments'
                                                ];
                                                $format = get_post_format();
                                                $icon = $format_icons[$format] ?? 'fas fa-file-alt';
                                                ?>
                                                <i class="<?php echo esc_attr($icon); ?>" aria-hidden="true"></i>
                                            </div>
                                            <?php endif; ?>
                                            
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Post Content -->
                                <div class="<?php echo ($show_featured_image && has_post_thumbnail()) ? 'col-lg-8 col-md-7 col-12' : 'col-12'; ?>">
                                    
                                    <!-- Post Meta (Top) -->
                                    <?php if ($show_meta): ?>
                                    <div class="post-meta post-meta-top mb-2">
                                        
                                        <!-- Categories -->
                                        <?php if (has_category()): ?>
                                        <div class="post-categories mb-2">
                                            <?php
                                            $categories = get_the_category();
                                            if ($categories) {
                                                echo '<div class="categories-list">';
                                                foreach ($categories as $category) {
                                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-light text-dark text-decoration-none me-1">' . esc_html($category->name) . '</a>';
                                                }
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Post Number (for large lists) -->
                                        <div class="post-number text-muted small mb-1">
                                            <?php printf(esc_html__('Yazı #%d', 'pratikwp'), $post_count); ?>
                                        </div>
                                        
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Post Title -->
                                    <h2 class="post-title h4 mb-3">
                                        <a href="<?php the_permalink(); ?>" 
                                           class="post-title-link text-decoration-none"
                                           itemprop="headline">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <!-- Post Excerpt -->
                                    <?php if ($show_excerpt): ?>
                                    <div class="post-excerpt mb-3" itemprop="description">
                                        <?php
                                        if (has_excerpt()) {
                                            echo wp_kses_post(wp_trim_words(get_the_excerpt(), $excerpt_length / 8, '...'));
                                        } else {
                                            echo wp_kses_post(wp_trim_words(get_the_content(), $excerpt_length / 8, '...'));
                                        }
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Post Meta (Bottom) -->
                                    <?php if ($show_meta): ?>
                                    <div class="post-meta post-meta-bottom">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                            
                                            <!-- Author, Date, Comments -->
                                            <div class="meta-left d-flex align-items-center gap-3 flex-wrap">
                                                
                                                <!-- Author -->
                                                <div class="post-author d-flex align-items-center">
                                                    <i class="fas fa-user me-1 text-muted" aria-hidden="true"></i>
                                                    <span class="author-name small text-muted" itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                                           class="text-decoration-none"
                                                           itemprop="name">
                                                            <?php the_author(); ?>
                                                        </a>
                                                    </span>
                                                </div>
                                                
                                                <!-- Date -->
                                                <div class="post-date d-flex align-items-center">
                                                    <i class="fas fa-calendar me-1 text-muted" aria-hidden="true"></i>
                                                    <time class="small text-muted" 
                                                          datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                                                          itemprop="datePublished">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                </div>
                                                
                                                <!-- Comments -->
                                                <?php if (comments_open() || get_comments_number()): ?>
                                                <div class="post-comments d-flex align-items-center">
                                                    <i class="fas fa-comment me-1 text-muted" aria-hidden="true"></i>
                                                    <a href="<?php comments_link(); ?>" 
                                                       class="comments-link text-decoration-none small text-muted">
                                                        <?php
                                                        $comments_number = get_comments_number();
                                                        printf(
                                                            esc_html(_n('%d yorum', '%d yorum', $comments_number, 'pratikwp')),
                                                            number_format_i18n($comments_number)
                                                        );
                                                        ?>
                                                    </a>
                                                </div>
                                                <?php endif; ?>
                                                
                                                <!-- Reading Time -->
                                                <div class="reading-time d-flex align-items-center">
                                                    <i class="fas fa-clock me-1 text-muted" aria-hidden="true"></i>
                                                    <span class="small text-muted">
                                                        <?php
                                                        $content = get_the_content();
                                                        $word_count = str_word_count(wp_strip_all_tags($content));
                                                        $reading_time = ceil($word_count / 200); // 200 words per minute
                                                        printf(
                                                            esc_html(_n('%d dk okuma', '%d dk okuma', $reading_time, 'pratikwp')),
                                                            $reading_time
                                                        );
                                                        ?>
                                                    </span>
                                                </div>
                                                
                                            </div>
                                            
                                            <!-- Read More -->
                                            <?php if ($show_read_more): ?>
                                            <div class="meta-right">
                                                <a href="<?php the_permalink(); ?>" 
                                                   class="read-more-link btn btn-outline-primary btn-sm">
                                                    <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
                                                    <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                </div>
                                
                            </div>
                            
                            <?php else: ?>
                            
                            <!-- Vertical Layout -->
                            <div class="vertical-layout">
                                
                                <!-- Post Meta (Top) -->
                                <?php if ($show_meta): ?>
                                <div class="post-meta post-meta-top mb-2">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        
                                        <!-- Categories -->
                                        <?php if (has_category()): ?>
                                        <div class="post-categories">
                                            <?php
                                            $categories = get_the_category();
                                            if ($categories) {
                                                echo '<div class="categories-list">';
                                                foreach ($categories as $category) {
                                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-primary text-decoration-none me-1">' . esc_html($category->name) . '</a>';
                                                }
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Date -->
                                        <div class="post-date">
                                            <time class="small text-muted" 
                                                  datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                                                  itemprop="datePublished">
                                                <?php echo esc_html(get_the_date()); ?>
                                            </time>
                                        </div>
                                        
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Post Title -->
                                <h2 class="post-title h3 mb-3">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="post-title-link text-decoration-none"
                                       itemprop="headline">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <!-- Featured Image -->
                                <?php if ($show_featured_image && has_post_thumbnail()): ?>
                                <div class="post-thumbnail mb-3">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="post-thumbnail-link d-block">
                                        <?php
                                        the_post_thumbnail($image_size, [
                                            'class' => 'img-fluid post-thumbnail-img',
                                            'alt' => get_the_title(),
                                            'itemprop' => 'image',
                                            'loading' => 'lazy'
                                        ]);
                                        ?>
                                    </a>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Post Excerpt -->
                                <?php if ($show_excerpt): ?>
                                <div class="post-excerpt mb-3" itemprop="description">
                                    <?php
                                    if (has_excerpt()) {
                                        echo wp_kses_post(wp_trim_words(get_the_excerpt(), $excerpt_length / 8, '...'));
                                    } else {
                                        echo wp_kses_post(wp_trim_words(get_the_content(), $excerpt_length / 8, '...'));
                                    }
                                    ?>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Post Meta (Bottom) -->
                                <?php if ($show_meta): ?>
                                <div class="post-meta post-meta-bottom">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        
                                        <!-- Author & Comments -->
                                        <div class="meta-left d-flex align-items-center gap-3">
                                            
                                            <!-- Author -->
                                            <div class="post-author">
                                                <span class="small text-muted">
                                                    <?php esc_html_e('Yazar:', 'pratikwp'); ?>
                                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                                       class="text-decoration-none">
                                                        <?php the_author(); ?>
                                                    </a>
                                                </span>
                                            </div>
                                            
                                            <!-- Comments -->
                                            <?php if (comments_open() || get_comments_number()): ?>
                                            <div class="post-comments">
                                                <a href="<?php comments_link(); ?>" 
                                                   class="comments-link text-decoration-none small text-muted">
                                                    <?php
                                                    $comments_number = get_comments_number();
                                                    printf(
                                                        esc_html(_n('%d yorum', '%d yorum', $comments_number, 'pratikwp')),
                                                        number_format_i18n($comments_number)
                                                    );
                                                    ?>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            
                                        </div>
                                        
                                        <!-- Read More -->
                                        <?php if ($show_read_more): ?>
                                        <div class="meta-right">
                                            <a href="<?php the_permalink(); ?>" 
                                               class="read-more-link btn btn-link p-0 text-decoration-none">
                                                <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
                                                <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            
                            <?php endif; ?>
                            
                            <!-- Structured Data -->
                            <meta itemprop="url" content="<?php the_permalink(); ?>">
                            <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
                                <meta itemprop="name" content="<?php bloginfo('name'); ?>">
                                <meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>">
                            </div>
                            
                        </article>
                        
                        <?php endwhile; ?>
                        
                    </div>
                    
                    <!-- Pagination -->
                    <div class="archive-pagination mt-5">
                        <?php
                        the_posts_pagination([
                            'mid_size' => 2,
                            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Önceki Sayfa', 'pratikwp'),
                            'next_text' => esc_html__('Sonraki Sayfa', 'pratikwp') . ' <i class="fas fa-chevron-right"></i>',
                            'before_page_number' => '<span class="screen-reader-text">' . esc_html__('Sayfa', 'pratikwp') . ' </span>',
                            'class' => 'pagination justify-content-center',
                        ]);
                        ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    
    <!-- No Posts Found -->
    <div class="archive-no-posts">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    <div class="no-posts-wrapper text-center py-5">
                        <div class="no-posts-icon mb-4">
                            <i class="fas fa-inbox fa-4x text-muted" aria-hidden="true"></i>
                        </div>
                        
                        <h2 class="no-posts-title mb-3">
                            <?php esc_html_e('Bu arşivde hiç yazı bulunamadı', 'pratikwp'); ?>
                        </h2>
                        
                        <p class="no-posts-description text-muted mb-4">
                            <?php
                            if (is_category()) {
                                esc_html_e('Bu kategoride henüz hiç yazı yayınlanmamış.', 'pratikwp');
                            } elseif (is_tag()) {
                                esc_html_e('Bu etikete sahip hiç yazı bulunamadı.', 'pratikwp');
                            } elseif (is_author()) {
                                esc_html_e('Bu yazar henüz hiç yazı yayınlamamış.', 'pratikwp');
                            } else {
                                esc_html_e('Seçtiğiniz tarih aralığında hiç yazı bulunamadı.', 'pratikwp');
                            }
                            ?>
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