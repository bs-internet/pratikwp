<?php
/**
 * Archive Grid Template
 * Grid layout for archive pages (category, tag, author, date)
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get archive settings from customizer
$archive_columns = get_theme_mod('archive_columns', 3);
$show_featured_image = get_theme_mod('archive_show_featured_image', true);
$show_excerpt = get_theme_mod('archive_show_excerpt', true);
$show_meta = get_theme_mod('archive_show_meta', true);
$show_read_more = get_theme_mod('archive_show_read_more', true);
$excerpt_length = get_theme_mod('archive_excerpt_length', 150);
$image_size = get_theme_mod('archive_image_size', 'medium');

// Grid column classes
$column_classes = [
    1 => 'col-12',
    2 => 'col-lg-6 col-md-6 col-12',
    3 => 'col-lg-4 col-md-6 col-12',
    4 => 'col-lg-3 col-md-6 col-12',
];
$column_class = $column_classes[$archive_columns] ?? 'col-lg-4 col-md-6 col-12';
?>

<div class="archive-grid-layout">
    
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
                                esc_html_e('Arşiv', 'pratikwp');
                            }
                            ?>
                        </h1>
                        
                        <!-- Archive Description -->
                        <?php if (is_category() || is_tag() || is_author()): ?>
                        <div class="archive-description">
                            <?php
                            if (is_category() || is_tag()) {
                                $description = term_description();
                                if ($description) {
                                    echo '<div class="lead text-muted">' . wp_kses_post($description) . '</div>';
                                }
                            } elseif (is_author()) {
                                $author_description = get_the_author_meta('description');
                                if ($author_description) {
                                    echo '<div class="lead text-muted">' . wp_kses_post($author_description) . '</div>';
                                }
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Post Count -->
                        <div class="archive-count mt-3">
                            <span class="badge bg-primary">
                                <?php
                                global $wp_query;
                                $total_posts = $wp_query->found_posts;
                                printf(
                                    esc_html(_n('%d yazı bulundu', '%d yazı bulundu', $total_posts, 'pratikwp')),
                                    number_format_i18n($total_posts)
                                );
                                ?>
                            </span>
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
                
                <?php while (have_posts()): the_post(); ?>
                
                <div class="<?php echo esc_attr($column_class); ?> mb-4">
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('archive-post-card h-100'); ?> itemscope itemtype="https://schema.org/Article">
                        
                        <div class="post-card-inner h-100 d-flex flex-column">
                            
                            <!-- Featured Image -->
                            <?php if ($show_featured_image && has_post_thumbnail()): ?>
                            <div class="post-thumbnail mb-3">
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
                                    
                                    <!-- Post Format Icon -->
                                    <?php if (get_post_format()): ?>
                                    <div class="post-format-icon">
                                        <?php
                                        $format_icons = [
                                            'video' => 'fas fa-play',
                                            'audio' => 'fas fa-music',
                                            'gallery' => 'fas fa-images',
                                            'image' => 'fas fa-camera',
                                            'quote' => 'fas fa-quote-right',
                                            'link' => 'fas fa-link',
                                            'status' => 'fas fa-comment',
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
                            <?php endif; ?>
                            
                            <!-- Post Content -->
                            <div class="post-content flex-grow-1 d-flex flex-column">
                                
                                <!-- Post Meta (Top) -->
                                <?php if ($show_meta): ?>
                                <div class="post-meta post-meta-top mb-2">
                                    
                                    <!-- Categories -->
                                    <?php if (has_category()): ?>
                                    <div class="post-categories">
                                        <?php
                                        $categories = get_the_category();
                                        if ($categories) {
                                            echo '<span class="categories-list">';
                                            foreach ($categories as $category) {
                                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-secondary text-decoration-none me-1">' . esc_html($category->name) . '</a>';
                                            }
                                            echo '</span>';
                                        }
                                        ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                </div>
                                <?php endif; ?>
                                
                                <!-- Post Title -->
                                <h2 class="post-title h5 mb-3">
                                    <a href="<?php the_permalink(); ?>" 
                                       class="post-title-link text-decoration-none"
                                       itemprop="headline">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <!-- Post Excerpt -->
                                <?php if ($show_excerpt): ?>
                                <div class="post-excerpt flex-grow-1 mb-3" itemprop="description">
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
                                <div class="post-meta post-meta-bottom mt-auto">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        
                                        <!-- Author & Date -->
                                        <div class="meta-left d-flex align-items-center gap-3">
                                            
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
                                            
                                        </div>
                                        
                                        <!-- Read More & Comments -->
                                        <div class="meta-right d-flex align-items-center gap-2">
                                            
                                            <!-- Comments Count -->
                                            <?php if (comments_open() || get_comments_number()): ?>
                                            <div class="post-comments">
                                                <a href="<?php comments_link(); ?>" 
                                                   class="comments-link text-decoration-none small text-muted">
                                                    <i class="fas fa-comment me-1" aria-hidden="true"></i>
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
                                            
                                            <!-- Read More -->
                                            <?php if ($show_read_more): ?>
                                            <div class="post-read-more">
                                                <a href="<?php the_permalink(); ?>" 
                                                   class="read-more-link btn btn-sm btn-outline-primary">
                                                    <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
                                                    <i class="fas fa-arrow-right ms-1" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            
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
            
            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <div class="archive-pagination mt-5">
                        <?php
                        the_posts_pagination([
                            'mid_size' => 2,
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
    </div>
    
    <?php else: ?>
    
    <!-- No Posts Found -->
    <div class="archive-no-posts">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    
                    <div class="no-posts-wrapper text-center py-5">
                        <div class="no-posts-icon mb-4">
                            <i class="fas fa-search fa-3x text-muted" aria-hidden="true"></i>
                        </div>
                        
                        <h2 class="no-posts-title mb-3">
                            <?php esc_html_e('Hiç yazı bulunamadı', 'pratikwp'); ?>
                        </h2>
                        
                        <p class="no-posts-description lead text-muted mb-4">
                            <?php esc_html_e('Bu kategoride henüz hiç yazı yayınlanmamış.', 'pratikwp'); ?>
                        </p>
                        
                        <div class="no-posts-actions">
                            <a href="<?php echo esc_url(home_url('/')); ?>" 
                               class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2" aria-hidden="true"></i>
                                <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    <?php endif; ?>
    
</div>