<?php
/**
 * Single Post Default Template
 * Default layout for single post pages
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get single post settings from customizer
$show_featured_image = get_theme_mod('single_show_featured_image', true);
$show_post_meta = get_theme_mod('single_show_post_meta', true);
$show_author_bio = get_theme_mod('single_show_author_bio', true);
$show_post_navigation = get_theme_mod('single_show_post_navigation', true);
$show_related_posts = get_theme_mod('single_show_related_posts', true);
$show_social_share = get_theme_mod('single_show_social_share', true);
$show_print_button = get_theme_mod('single_show_print_button', false);
$related_posts_count = get_theme_mod('single_related_posts_count', 3);
$content_width = get_theme_mod('single_content_width', 'container');

while (have_posts()): the_post();
?>

<div class="single-post-layout single-default-template">
    
    <!-- Post Header -->
    <header class="single-post-header py-5 bg-light">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-lg-8 col-12 mx-auto">
                    
                    <!-- Breadcrumbs -->
                    <?php if (function_exists('pratikwp_breadcrumbs')): ?>
                    <nav aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>" class="mb-4">
                        <?php pratikwp_breadcrumbs(); ?>
                    </nav>
                    <?php endif; ?>
                    
                    <!-- Post Categories -->
                    <?php if ($show_post_meta && has_category()): ?>
                    <div class="post-categories mb-3">
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            echo '<div class="categories-list">';
                            foreach ($categories as $category) {
                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-primary text-decoration-none me-2">' . esc_html($category->name) . '</a>';
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Post Title -->
                    <h1 class="single-post-title mb-4" itemprop="headline">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Post Meta -->
                    <?php if ($show_post_meta): ?>
                    <div class="single-post-meta mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            
                            <!-- Author & Date -->
                            <div class="meta-left d-flex align-items-center gap-4 flex-wrap">
                                
                                <!-- Author Info -->
                                <div class="author-info d-flex align-items-center">
                                    <div class="author-avatar me-3">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo get_avatar(get_the_author_meta('ID'), 48, '', get_the_author(), ['class' => 'rounded-circle']); ?>
                                        </a>
                                    </div>
                                    <div class="author-details">
                                        <div class="author-name">
                                            <span class="text-muted small"><?php esc_html_e('Yazar:', 'pratikwp'); ?></span>
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                               class="text-decoration-none fw-bold"
                                               itemprop="author" itemscope itemtype="https://schema.org/Person">
                                                <span itemprop="name"><?php the_author(); ?></span>
                                            </a>
                                        </div>
                                        <div class="author-posts-count">
                                            <small class="text-muted">
                                                <?php
                                                printf(
                                                    esc_html(_n('%d yazı', '%d yazı', count_user_posts(get_the_author_meta('ID')), 'pratikwp')),
                                                    number_format_i18n(count_user_posts(get_the_author_meta('ID')))
                                                );
                                                ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Date & Reading Time -->
                                <div class="date-info">
                                    <div class="publish-date">
                                        <i class="fas fa-calendar me-2 text-primary" aria-hidden="true"></i>
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" 
                                              itemprop="datePublished"
                                              class="text-muted">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </div>
                                    
                                    <?php if (get_the_modified_date() !== get_the_date()): ?>
                                    <div class="modified-date">
                                        <small class="text-muted">
                                            <i class="fas fa-edit me-1" aria-hidden="true"></i>
                                            <?php esc_html_e('Güncellendi:', 'pratikwp'); ?>
                                            <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" 
                                                  itemprop="dateModified">
                                                <?php echo esc_html(get_the_modified_date()); ?>
                                            </time>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Reading Time -->
                                    <div class="reading-time">
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                            <?php
                                            $content = get_the_content();
                                            $word_count = str_word_count(wp_strip_all_tags($content));
                                            $reading_time = ceil($word_count / 200);
                                            printf(
                                                esc_html(_n('%d dakika okuma', '%d dakika okuma', $reading_time, 'pratikwp')),
                                                $reading_time
                                            );
                                            ?>
                                        </small>
                                    </div>
                                </div>
                                
                            </div>
                            
                            <!-- Actions -->
                            <div class="meta-right d-flex align-items-center gap-2">
                                
                                <!-- Comments Count -->
                                <?php if (comments_open() || get_comments_number()): ?>
                                <div class="comments-count">
                                    <a href="#comments" 
                                       class="btn btn-outline-secondary btn-sm text-decoration-none">
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
                                
                                <!-- Print Button -->
                                <?php if ($show_print_button): ?>
                                <div class="print-button">
                                    <button class="btn btn-outline-secondary btn-sm" 
                                            onclick="window.print()"
                                            aria-label="<?php esc_attr_e('Yazıyı yazdır', 'pratikwp'); ?>">
                                        <i class="fas fa-print" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </header>
    
    <!-- Featured Image -->
    <?php if ($show_featured_image && has_post_thumbnail()): ?>
    <div class="single-post-featured-image mb-5">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-12">
                    <figure class="featured-image-wrapper text-center">
                        <?php
                        the_post_thumbnail('full', [
                            'class' => 'img-fluid featured-image',
                            'alt' => get_the_title(),
                            'itemprop' => 'image'
                        ]);
                        ?>
                        
                        <?php if (get_the_post_thumbnail_caption()): ?>
                        <figcaption class="image-caption mt-2 text-muted small">
                            <?php echo wp_kses_post(get_the_post_thumbnail_caption()); ?>
                        </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Post Content -->
    <main class="single-post-content">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-lg-8 col-12 mx-auto">
                    
                    <article id="post-<?php the_ID(); ?>" 
                             <?php post_class('single-post-article'); ?> 
                             itemscope itemtype="https://schema.org/Article">
                        
                        <!-- Post Content -->
                        <div class="post-content mb-5" itemprop="articleBody">
                            <?php
                            the_content();
                            
                            wp_link_pages([
                                'before' => '<div class="page-links mt-4"><span class="page-links-title">' . esc_html__('Sayfalar:', 'pratikwp') . '</span>',
                                'after' => '</div>',
                                'link_before' => '<span class="page-number">',
                                'link_after' => '</span>',
                            ]);
                            ?>
                        </div>
                        
                        <!-- Post Tags -->
                        <?php if (has_tag()): ?>
                        <div class="post-tags mb-4">
                            <h6 class="tags-title mb-3">
                                <i class="fas fa-tags me-2" aria-hidden="true"></i>
                                <?php esc_html_e('Etiketler:', 'pratikwp'); ?>
                            </h6>
                            <div class="tags-list">
                                <?php
                                $tags = get_the_tags();
                                if ($tags) {
                                    foreach ($tags as $tag) {
                                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link badge bg-light text-dark text-decoration-none me-2 mb-2">#' . esc_html($tag->name) . '</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Social Share -->
                        <?php if ($show_social_share): ?>
                        <div class="social-share mb-5">
                            <h6 class="share-title mb-3">
                                <i class="fas fa-share-alt me-2" aria-hidden="true"></i>
                                <?php esc_html_e('Paylaş:', 'pratikwp'); ?>
                            </h6>
                            <div class="share-buttons d-flex align-items-center flex-wrap gap-2">
                                
                                <?php
                                $post_url = urlencode(get_permalink());
                                $post_title = urlencode(get_the_title());
                                $post_excerpt = urlencode(wp_trim_words(get_the_excerpt(), 20));
                                ?>
                                
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn btn btn-primary btn-sm"
                                   aria-label="<?php esc_attr_e('Facebook\'ta paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                    Facebook
                                </a>
                                
                                <!-- Twitter -->
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn btn btn-info btn-sm"
                                   aria-label="<?php esc_attr_e('Twitter\'da paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                    Twitter
                                </a>
                                
                                <!-- LinkedIn -->
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn btn btn-secondary btn-sm"
                                   aria-label="<?php esc_attr_e('LinkedIn\'de paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                    LinkedIn
                                </a>
                                
                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn btn btn-success btn-sm"
                                   aria-label="<?php esc_attr_e('WhatsApp\'ta paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                    WhatsApp
                                </a>
                                
                                <!-- Copy Link -->
                                <button class="share-btn btn btn-outline-secondary btn-sm copy-link-btn" 
                                        data-url="<?php the_permalink(); ?>"
                                        aria-label="<?php esc_attr_e('Linki kopyala', 'pratikwp'); ?>">
                                    <i class="fas fa-copy" aria-hidden="true"></i>
                                    <?php esc_html_e('Linki Kopyala', 'pratikwp'); ?>
                                </button>
                                
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Structured Data -->
                        <meta itemprop="url" content="<?php the_permalink(); ?>">
                        <meta itemprop="dateModified" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                        <meta itemprop="wordCount" content="<?php echo esc_attr(str_word_count(wp_strip_all_tags(get_the_content()))); ?>">
                        
                        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
                            <meta itemprop="name" content="<?php bloginfo('name'); ?>">
                            <meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>">
                            <?php if (has_custom_logo()): ?>
                            <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                                <meta itemprop="url" content="<?php echo esc_url(wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full')[0]); ?>">
                            </div>
                            <?php endif; ?>
                        </div>
                        
                    </article>
                    
                </div>
            </div>
        </div>
    </main>
    
    <!-- Author Bio -->
    <?php if ($show_author_bio): ?>
    <section class="author-bio-section py-5 bg-light">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-lg-8 col-12 mx-auto">
                    
                    <div class="author-bio-card">
                        <div class="row align-items-center">
                            
                            <!-- Author Avatar -->
                            <div class="col-md-3 col-12 text-center mb-3 mb-md-0">
                                <div class="author-avatar-large">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 120, '', get_the_author(), ['class' => 'rounded-circle border border-3 border-white shadow']); ?>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Author Info -->
                            <div class="col-md-9 col-12">
                                <div class="author-bio-content">
                                    
                                    <h4 class="author-name mb-2">
                                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                           class="text-decoration-none">
                                            <?php the_author(); ?>
                                        </a>
                                    </h4>
                                    
                                    <?php if (get_the_author_meta('description')): ?>
                                    <p class="author-description text-muted mb-3">
                                        <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                    </p>
                                    <?php endif; ?>
                                    
                                    <div class="author-stats d-flex align-items-center gap-4 flex-wrap">
                                        
                                        <!-- Posts Count -->
                                        <div class="author-posts">
                                            <span class="badge bg-primary">
                                                <?php
                                                printf(
                                                    esc_html(_n('%d yazı', '%d yazı', count_user_posts(get_the_author_meta('ID')), 'pratikwp')),
                                                    number_format_i18n(count_user_posts(get_the_author_meta('ID')))
                                                );
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <!-- Author Links -->
                                        <div class="author-links d-flex align-items-center gap-2">
                                            
                                            <!-- View All Posts -->
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-user me-1" aria-hidden="true"></i>
                                                <?php esc_html_e('Tüm Yazıları', 'pratikwp'); ?>
                                            </a>
                                            
                                            <!-- Author Website -->
                                            <?php if (get_the_author_meta('user_url')): ?>
                                            <a href="<?php echo esc_url(get_the_author_meta('user_url')); ?>" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-globe me-1" aria-hidden="true"></i>
                                                <?php esc_html_e('Website', 'pratikwp'); ?>
                                            </a>
                                            <?php endif; ?>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Post Navigation -->
    <?php if ($show_post_navigation): ?>
    <section class="post-navigation-section py-4 border-top border-bottom">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-12">
                    
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    ?>
                    
                    <?php if ($prev_post || $next_post): ?>
                    <nav class="post-navigation" aria-label="<?php esc_attr_e('Yazı navigasyonu', 'pratikwp'); ?>">
                        <div class="row">
                            
                            <!-- Previous Post -->
                            <div class="col-md-6 col-12 mb-3 mb-md-0">
                                <?php if ($prev_post): ?>
                                <div class="nav-previous">
                                    <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" 
                                       class="nav-link d-flex align-items-center text-decoration-none p-3 bg-light rounded">
                                        <div class="nav-icon me-3">
                                            <i class="fas fa-chevron-left text-primary" aria-hidden="true"></i>
                                        </div>
                                        <div class="nav-content">
                                            <div class="nav-label small text-muted mb-1">
                                                <?php esc_html_e('Önceki Yazı', 'pratikwp'); ?>
                                            </div>
                                            <div class="nav-title fw-bold">
                                                <?php echo esc_html(get_the_title($prev_post)); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Next Post -->
                            <div class="col-md-6 col-12">
                                <?php if ($next_post): ?>
                                <div class="nav-next">
                                    <a href="<?php echo esc_url(get_permalink($next_post)); ?>" 
                                       class="nav-link d-flex align-items-center text-decoration-none p-3 bg-light rounded">
                                        <div class="nav-content text-end">
                                            <div class="nav-label small text-muted mb-1">
                                                <?php esc_html_e('Sonraki Yazı', 'pratikwp'); ?>
                                            </div>
                                            <div class="nav-title fw-bold">
                                                <?php echo esc_html(get_the_title($next_post)); ?>
                                            </div>
                                        </div>
                                        <div class="nav-icon ms-3">
                                            <i class="fas fa-chevron-right text-primary" aria-hidden="true"></i>
                                        </div>
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                    </nav>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Related Posts -->
    <?php if ($show_related_posts): ?>
    <section class="related-posts-section py-5">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-12">
                    
                    <?php
                    // Get related posts by categories
                    $related_posts = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => $related_posts_count,
                        'post__not_in' => [get_the_ID()],
                        'category__in' => wp_get_post_categories(get_the_ID()),
                        'orderby' => 'rand',
                        'meta_key' => '_thumbnail_id'
                    ]);
                    ?>
                    
                    <?php if ($related_posts->have_posts()): ?>
                    <div class="related-posts-wrapper">
                        
                        <h3 class="related-posts-title mb-4 text-center">
                            <i class="fas fa-newspaper me-2" aria-hidden="true"></i>
                            <?php esc_html_e('İlgili Yazılar', 'pratikwp'); ?>
                        </h3>
                        
                        <div class="row">
                            
                            <?php while ($related_posts->have_posts()): $related_posts->the_post(); ?>
                            <div class="col-lg-4 col-md-6 col-12 mb-4">
                                
                                <article class="related-post-card h-100">
                                    <div class="card border-0 shadow-sm h-100">
                                        
                                        <?php if (has_post_thumbnail()): ?>
                                        <div class="card-img-top-wrapper">
                                            <a href="<?php the_permalink(); ?>" class="card-img-link">
                                                <?php the_post_thumbnail('medium', ['class' => 'card-img-top', 'alt' => get_the_title()]); ?>
                                            </a>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="card-body d-flex flex-column">
                                            
                                            <h5 class="card-title">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h5>
                                            
                                            <p class="card-text text-muted small flex-grow-1">
                                                <?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 15, '...')); ?>
                                            </p>
                                            
                                            <div class="card-meta d-flex justify-content-between align-items-center mt-auto">
                                                <small class="text-muted">
                                                    <?php echo esc_html(get_the_date()); ?>
                                                </small>
                                                <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                                    <?php esc_html_e('Oku', 'pratikwp'); ?>
                                                </a>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </article>
                                
                            </div>
                            <?php endwhile; ?>
                            
                        </div>
                        
                    </div>
                    <?php endif; ?>
                    
                    <?php wp_reset_postdata(); ?>
                    
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- Comments Section -->
    <?php if (comments_open() || get_comments_number()): ?>
    <section class="comments-section py-5 bg-light">
        <div class="<?php echo esc_attr($content_width); ?>">
            <div class="row">
                <div class="col-lg-8 col-12 mx-auto">
                    <?php comments_template(); ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
</div>

<?php endwhile; ?>

<!-- Copy Link JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const copyBtns = document.querySelectorAll('.copy-link-btn');
    
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const url = this.dataset.url;
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    showCopySuccess(this);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = url;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                showCopySuccess(this);
            }
        });
    });
    
    function showCopySuccess(btn) {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> <?php esc_html_e("Kopyalandı!", "pratikwp"); ?>';
        btn.classList.add('btn-success');
        btn.classList.remove('btn-outline-secondary');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-secondary');
        }, 2000);
    }
});
</script>