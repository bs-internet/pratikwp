<?php
/**
 * Single Post Full Width Template
 * Full width layout for single post pages (no sidebar)
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
$show_table_of_contents = get_theme_mod('single_show_table_of_contents', false);
$related_posts_count = get_theme_mod('single_related_posts_count', 4);
$featured_image_style = get_theme_mod('single_featured_image_style', 'hero'); // hero, standard, background

while (have_posts()): the_post();
?>

<div class="single-post-layout single-full-width-template">
    
    <?php if ($show_featured_image && has_post_thumbnail() && $featured_image_style === 'hero'): ?>
    <!-- Hero Featured Image -->
    <div class="single-post-hero position-relative">
        
        <!-- Hero Background -->
        <div class="hero-background">
            <?php
            $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            ?>
            <div class="hero-image" 
                 style="background-image: url('<?php echo esc_url($featured_image); ?>');">
            </div>
            <div class="hero-overlay"></div>
        </div>
        
        <!-- Hero Content -->
        <div class="hero-content d-flex align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-10 col-12 mx-auto text-center text-white">
                        
                        <!-- Breadcrumbs -->
                        <?php if (function_exists('pratikwp_breadcrumbs')): ?>
                        <nav aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>" class="mb-4">
                            <div class="breadcrumb-hero">
                                <?php pratikwp_breadcrumbs(); ?>
                            </div>
                        </nav>
                        <?php endif; ?>
                        
                        <!-- Post Categories -->
                        <?php if ($show_post_meta && has_category()): ?>
                        <div class="post-categories mb-3">
                            <?php
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<div class="categories-list">';
                                foreach (array_slice($categories, 0, 2) as $category) { // Show max 2 categories
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-primary bg-opacity-75 text-white text-decoration-none me-2 fs-6">' . esc_html($category->name) . '</a>';
                                }
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Post Title -->
                        <h1 class="hero-title display-4 fw-bold mb-4 text-shadow" itemprop="headline">
                            <?php the_title(); ?>
                        </h1>
                        
                        <!-- Post Excerpt/Summary -->
                        <?php if (has_excerpt()): ?>
                        <div class="hero-excerpt lead mb-4 text-white-75">
                            <?php echo wp_kses_post(get_the_excerpt()); ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Hero Meta -->
                        <?php if ($show_post_meta): ?>
                        <div class="hero-meta d-flex justify-content-center align-items-center flex-wrap gap-4">
                            
                            <!-- Author Info -->
                            <div class="hero-author d-flex align-items-center">
                                <div class="author-avatar me-2">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 32, '', get_the_author(), ['class' => 'rounded-circle border border-2 border-white']); ?>
                                </div>
                                <div class="author-details">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                       class="text-white text-decoration-none fw-semibold"
                                       itemprop="author" itemscope itemtype="https://schema.org/Person">
                                        <span itemprop="name"><?php the_author(); ?></span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Date -->
                            <div class="hero-date">
                                <i class="fas fa-calendar me-2" aria-hidden="true"></i>
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" 
                                      itemprop="datePublished"
                                      class="text-white">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                            </div>
                            
                            <!-- Reading Time -->
                            <div class="hero-reading-time">
                                <i class="fas fa-clock me-2" aria-hidden="true"></i>
                                <span class="text-white">
                                    <?php
                                    $content = get_the_content();
                                    $word_count = str_word_count(wp_strip_all_tags($content));
                                    $reading_time = ceil($word_count / 200);
                                    printf(
                                        esc_html(_n('%d dk', '%d dk', $reading_time, 'pratikwp')),
                                        $reading_time
                                    );
                                    ?>
                                </span>
                            </div>
                            
                            <!-- Comments -->
                            <?php if (comments_open() || get_comments_number()): ?>
                            <div class="hero-comments">
                                <a href="#comments" class="text-white text-decoration-none">
                                    <i class="fas fa-comment me-2" aria-hidden="true"></i>
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
                        <?php endif; ?>
                        
                        <!-- Scroll Down Indicator -->
                        <div class="scroll-indicator mt-5">
                            <a href="#post-content" class="text-white text-decoration-none animate-bounce">
                                <i class="fas fa-chevron-down fa-2x" aria-hidden="true"></i>
                                <div class="small mt-2"><?php esc_html_e('Okumaya Başla', 'pratikwp'); ?></div>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <?php elseif ($show_featured_image && has_post_thumbnail() && $featured_image_style === 'standard'): ?>
    
    <!-- Standard Header -->
    <header class="single-post-header py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto text-center">
                    
                    <!-- Breadcrumbs -->
                    <?php if (function_exists('pratikwp_breadcrumbs')): ?>
                    <nav aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>" class="mb-4">
                        <?php pratikwp_breadcrumbs(); ?>
                    </nav>
                    <?php endif; ?>
                    
                    <!-- Categories -->
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
                    
                    <!-- Title -->
                    <h1 class="single-post-title display-5 mb-4" itemprop="headline">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Meta -->
                    <?php if ($show_post_meta): ?>
                    <div class="post-meta d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4">
                        
                        <div class="author-info d-flex align-items-center">
                            <?php echo get_avatar(get_the_author_meta('ID'), 32, '', get_the_author(), ['class' => 'rounded-circle me-2']); ?>
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                               class="text-decoration-none">
                                <?php the_author(); ?>
                            </a>
                        </div>
                        
                        <span class="text-muted">•</span>
                        
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" 
                              itemprop="datePublished">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                        
                        <span class="text-muted">•</span>
                        
                        <span class="reading-time">
                            <?php
                            $content = get_the_content();
                            $word_count = str_word_count(wp_strip_all_tags($content));
                            $reading_time = ceil($word_count / 200);
                            printf(
                                esc_html(_n('%d dakika okuma', '%d dakika okuma', $reading_time, 'pratikwp')),
                                $reading_time
                            );
                            ?>
                        </span>
                        
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </header>
    
    <!-- Featured Image -->
    <div class="single-post-featured-image mb-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <figure class="featured-image-wrapper text-center">
                        <?php
                        the_post_thumbnail('full', [
                            'class' => 'img-fluid featured-image rounded shadow',
                            'alt' => get_the_title(),
                            'itemprop' => 'image'
                        ]);
                        ?>
                        
                        <?php if (get_the_post_thumbnail_caption()): ?>
                        <figcaption class="image-caption mt-3 text-muted">
                            <?php echo wp_kses_post(get_the_post_thumbnail_caption()); ?>
                        </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    
    <!-- Simple Header (No Featured Image) -->
    <header class="single-post-header py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto text-center">
                    
                    <!-- Breadcrumbs -->
                    <?php if (function_exists('pratikwp_breadcrumbs')): ?>
                    <nav aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>" class="mb-4">
                        <?php pratikwp_breadcrumbs(); ?>
                    </nav>
                    <?php endif; ?>
                    
                    <!-- Categories -->
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
                    
                    <!-- Title -->
                    <h1 class="single-post-title display-4 mb-4" itemprop="headline">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Meta -->
                    <?php if ($show_post_meta): ?>
                    <div class="post-meta d-flex justify-content-center align-items-center flex-wrap gap-4">
                        
                        <div class="author-info d-flex align-items-center">
                            <?php echo get_avatar(get_the_author_meta('ID'), 40, '', get_the_author(), ['class' => 'rounded-circle me-2']); ?>
                            <div>
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                   class="text-decoration-none fw-bold">
                                    <?php the_author(); ?>
                                </a>
                            </div>
                        </div>
                        
                        <div class="date-info text-muted">
                            <i class="fas fa-calendar me-2" aria-hidden="true"></i>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" 
                                  itemprop="datePublished">
                                <?php echo esc_html(get_the_date()); ?>
                            </time>
                        </div>
                        
                        <div class="reading-time text-muted">
                            <i class="fas fa-clock me-2" aria-hidden="true"></i>
                            <?php
                            $content = get_the_content();
                            $word_count = str_word_count(wp_strip_all_tags($content));
                            $reading_time = ceil($word_count / 200);
                            printf(
                                esc_html(_n('%d dk', '%d dk', $reading_time, 'pratikwp')),
                                $reading_time
                            );
                            ?>
                        </div>
                        
                    </div>
                    <?php endif; ?>
                    
                </div>
            </div>
        </div>
    </header>
    
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="single-post-content" id="post-content">
        <div class="container">
            <div class="row">
                
                <!-- Content Column -->
                <div class="<?php echo $show_table_of_contents ? 'col-lg-9 col-12' : 'col-lg-10 col-12 mx-auto'; ?>">
                    
                    <article id="post-<?php the_ID(); ?>" 
                             <?php post_class('single-post-article'); ?> 
                             itemscope itemtype="https://schema.org/Article">
                        
                        <!-- Social Share (Top) -->
                        <?php if ($show_social_share): ?>
                        <div class="social-share-sticky d-none d-lg-block">
                            <div class="share-buttons-vertical">
                                
                                <?php
                                $post_url = urlencode(get_permalink());
                                $post_title = urlencode(get_the_title());
                                ?>
                                
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn share-facebook"
                                   title="<?php esc_attr_e('Facebook\'ta paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                </a>
                                
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn share-twitter"
                                   title="<?php esc_attr_e('Twitter\'da paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-twitter" aria-hidden="true"></i>
                                </a>
                                
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn share-linkedin"
                                   title="<?php esc_attr_e('LinkedIn\'de paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                </a>
                                
                                <a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="share-btn share-whatsapp"
                                   title="<?php esc_attr_e('WhatsApp\'ta paylaş', 'pratikwp'); ?>">
                                    <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                </a>
                                
                                <button class="share-btn share-copy copy-link-btn" 
                                        data-url="<?php the_permalink(); ?>"
                                        title="<?php esc_attr_e('Linki kopyala', 'pratikwp'); ?>">
                                    <i class="fas fa-copy" aria-hidden="true"></i>
                                </button>
                                
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Post Content -->
                        <div class="post-content-wrapper">
                            
                            <!-- Progress Bar -->
                            <div class="reading-progress-bar"></div>
                            
                            <!-- Post Content -->
                            <div class="post-content" itemprop="articleBody">
                                <?php
                                the_content();
                                
                                wp_link_pages([
                                    'before' => '<nav class="page-links mt-5 p-4 bg-light rounded"><span class="page-links-title h6">' . esc_html__('Sayfalar:', 'pratikwp') . '</span>',
                                    'after' => '</nav>',
                                    'link_before' => '<span class="page-number btn btn-outline-primary btn-sm me-2 mb-2">',
                                    'link_after' => '</span>',
                                ]);
                                ?>
                            </div>
                            
                        </div>
                        
                        <!-- Post Footer -->
                        <footer class="post-footer mt-5 pt-4 border-top">
                            
                            <!-- Post Tags -->
                            <?php if (has_tag()): ?>
                            <div class="post-tags mb-4">
                                <h6 class="tags-title mb-3">
                                    <i class="fas fa-hashtag me-2 text-primary" aria-hidden="true"></i>
                                    <?php esc_html_e('Etiketler', 'pratikwp'); ?>
                                </h6>
                                <div class="tags-list">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link badge bg-light text-dark text-decoration-none me-2 mb-2 fs-6">#' . esc_html($tag->name) . '</a>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Social Share (Mobile) -->
                            <?php if ($show_social_share): ?>
                            <div class="social-share-mobile d-lg-none mb-4">
                                <h6 class="share-title mb-3">
                                    <i class="fas fa-share-alt me-2 text-primary" aria-hidden="true"></i>
                                    <?php esc_html_e('Bu yazıyı paylaş', 'pratikwp'); ?>
                                </h6>
                                <div class="share-buttons d-flex align-items-center flex-wrap gap-2">
                                    
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn btn-primary btn-sm">
                                        <i class="fab fa-facebook-f me-1" aria-hidden="true"></i>
                                        Facebook
                                    </a>
                                    
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn btn-info btn-sm">
                                        <i class="fab fa-twitter me-1" aria-hidden="true"></i>
                                        Twitter
                                    </a>
                                    
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn btn-secondary btn-sm">
                                        <i class="fab fa-linkedin-in me-1" aria-hidden="true"></i>
                                        LinkedIn
                                    </a>
                                    
                                    <a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="btn btn-success btn-sm">
                                        <i class="fab fa-whatsapp me-1" aria-hidden="true"></i>
                                        WhatsApp
                                    </a>
                                    
                                    <button class="btn btn-outline-secondary btn-sm copy-link-btn" 
                                            data-url="<?php the_permalink(); ?>">
                                        <i class="fas fa-copy me-1" aria-hidden="true"></i>
                                        <?php esc_html_e('Kopyala', 'pratikwp'); ?>
                                    </button>
                                    
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Post Meta Footer -->
                            <div class="post-meta-footer d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 bg-light rounded">
                                
                                <div class="meta-left">
                                    <small class="text-muted">
                                        <?php esc_html_e('Son güncelleme:', 'pratikwp'); ?>
                                        <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" 
                                              itemprop="dateModified">
                                            <?php echo esc_html(get_the_modified_date()); ?>
                                        </time>
                                    </small>
                                </div>
                                
                                <div class="meta-right">
                                    <small class="text-muted">
                                        <?php
                                        $word_count = str_word_count(wp_strip_all_tags(get_the_content()));
                                        printf(
                                            esc_html(_n('%d kelime', '%d kelime', $word_count, 'pratikwp')),
                                            number_format_i18n($word_count)
                                        );
                                        ?>
                                    </small>
                                </div>
                                
                            </div>
                            
                        </footer>
                        
                        <!-- Structured Data -->
                        <meta itemprop="url" content="<?php the_permalink(); ?>">
                        <meta itemprop="wordCount" content="<?php echo esc_attr(str_word_count(wp_strip_all_tags(get_the_content()))); ?>">
                        
                        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization" style="display: none;">
                            <meta itemprop="name" content="<?php bloginfo('name'); ?>">
                            <meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>">
                        </div>
                        
                    </article>
                    
                </div>
                
                <!-- Table of Contents Sidebar -->
                <?php if ($show_table_of_contents): ?>
                <aside class="col-lg-3 col-12">
                    <div class="table-of-contents-wrapper sticky-top">
                        <div class="table-of-contents p-3 bg-light rounded">
                            <h6 class="toc-title mb-3">
                                <i class="fas fa-list me-2" aria-hidden="true"></i>
                                <?php esc_html_e('İçindekiler', 'pratikwp'); ?>
                            </h6>
                            <div id="table-of-contents">
                                <!-- TOC will be generated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </aside>
                <?php endif; ?>
                
            </div>
        </div>
    </main>
    
    <!-- Author Bio Section -->
    <?php if ($show_author_bio): ?>
    <section class="author-bio-section py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">
                    
                    <div class="author-bio-card p-4 bg-white rounded shadow-sm">
                        <div class="row align-items-center">
                            
                            <div class="col-md-2 col-12 text-center mb-3 mb-md-0">
                                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 100, '', get_the_author(), ['class' => 'rounded-circle border border-3 border-primary']); ?>
                                </a>
                            </div>
                            
                            <div class="col-md-10 col-12">
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
                                    
                                    <div class="author-stats-actions d-flex justify-content-between align-items-center flex-wrap gap-3">
                                        
                                        <div class="author-stats d-flex align-items-center gap-3">
                                            <span class="badge bg-primary">
                                                <?php
                                                printf(
                                                    esc_html(_n('%d yazı', '%d yazı', count_user_posts(get_the_author_meta('ID')), 'pratikwp')),
                                                    number_format_i18n(count_user_posts(get_the_author_meta('ID')))
                                                );
                                                ?>
                                            </span>
                                        </div>
                                        
                                        <div class="author-actions">
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-user me-1" aria-hidden="true"></i>
                                                <?php esc_html_e('Tüm Yazıları Gör', 'pratikwp'); ?>
                                            </a>
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
        <div class="container">
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
                            <div class="col-lg-6 col-12 mb-3 mb-lg-0">
                                <?php if ($prev_post): ?>
                                <div class="nav-previous">
                                    <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" 
                                       class="nav-link d-flex align-items-center text-decoration-none p-4 bg-light rounded h-100">
                                        
                                        <?php if (has_post_thumbnail($prev_post)): ?>
                                        <div class="nav-thumbnail me-3">
                                            <?php echo get_the_post_thumbnail($prev_post, 'thumbnail', ['class' => 'rounded', 'style' => 'width: 60px; height: 60px; object-fit: cover;']); ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="nav-content flex-grow-1">
                                            <div class="nav-label small text-muted mb-1">
                                                <i class="fas fa-chevron-left me-1" aria-hidden="true"></i>
                                                <?php esc_html_e('Önceki Yazı', 'pratikwp'); ?>
                                            </div>
                                            <div class="nav-title fw-bold">
                                                <?php echo esc_html(get_the_title($prev_post)); ?>
                                            </div>
                                            <div class="nav-date small text-muted mt-1">
                                                <?php echo esc_html(get_the_date('', $prev_post)); ?>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php else: ?>
                                <div class="nav-placeholder"></div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Next Post -->
                            <div class="col-lg-6 col-12">
                                <?php if ($next_post): ?>
                                <div class="nav-next">
                                    <a href="<?php echo esc_url(get_permalink($next_post)); ?>" 
                                       class="nav-link d-flex align-items-center text-decoration-none p-4 bg-light rounded h-100">
                                        
                                        <div class="nav-content flex-grow-1 text-end">
                                            <div class="nav-label small text-muted mb-1">
                                                <?php esc_html_e('Sonraki Yazı', 'pratikwp'); ?>
                                                <i class="fas fa-chevron-right ms-1" aria-hidden="true"></i>
                                            </div>
                                            <div class="nav-title fw-bold">
                                                <?php echo esc_html(get_the_title($next_post)); ?>
                                            </div>
                                            <div class="nav-date small text-muted mt-1">
                                                <?php echo esc_html(get_the_date('', $next_post)); ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (has_post_thumbnail($next_post)): ?>
                                        <div class="nav-thumbnail ms-3">
                                            <?php echo get_the_post_thumbnail($next_post, 'thumbnail', ['class' => 'rounded', 'style' => 'width: 60px; height: 60px; object-fit: cover;']); ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                    </a>
                                </div>
                                <?php else: ?>
                                <div class="nav-placeholder"></div>
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
        <div class="container">
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
                        
                        <h3 class="related-posts-title text-center mb-5">
                            <i class="fas fa-newspaper me-2 text-primary" aria-hidden="true"></i>
                            <?php esc_html_e('İlgili Yazılar', 'pratikwp'); ?>
                        </h3>
                        
                        <div class="row">
                            
                            <?php while ($related_posts->have_posts()): $related_posts->the_post(); ?>
                            <div class="col-lg-3 col-md-6 col-12 mb-4">
                                
                                <article class="related-post-card h-100">
                                    <div class="card border-0 shadow-sm h-100 overflow-hidden">
                                        
                                        <?php if (has_post_thumbnail()): ?>
                                        <div class="card-img-top-wrapper position-relative overflow-hidden">
                                            <a href="<?php the_permalink(); ?>" class="card-img-link d-block">
                                                <?php the_post_thumbnail('medium', [
                                                    'class' => 'card-img-top related-post-image', 
                                                    'alt' => get_the_title(),
                                                    'style' => 'height: 200px; object-fit: cover; transition: transform 0.3s ease;'
                                                ]); ?>
                                            </a>
                                            
                                            <!-- Category Badge -->
                                            <?php if (has_category()): ?>
                                            <div class="category-badge position-absolute top-0 start-0 m-2">
                                                <?php
                                                $primary_category = get_the_category()[0];
                                                if ($primary_category) {
                                                    echo '<span class="badge bg-primary">' . esc_html($primary_category->name) . '</span>';
                                                }
                                                ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="card-body d-flex flex-column">
                                            
                                            <h5 class="card-title mb-3">
                                                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark stretched-link">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h5>
                                            
                                            <p class="card-text text-muted small flex-grow-1 mb-3">
                                                <?php echo wp_kses_post(wp_trim_words(get_the_excerpt(), 12, '...')); ?>
                                            </p>
                                            
                                            <div class="card-meta d-flex justify-content-between align-items-center mt-auto">
                                                
                                                <div class="author-info d-flex align-items-center">
                                                    <?php echo get_avatar(get_the_author_meta('ID'), 24, '', get_the_author(), ['class' => 'rounded-circle me-2']); ?>
                                                    <small class="text-muted"><?php the_author(); ?></small>
                                                </div>
                                                
                                                <small class="text-muted">
                                                    <?php echo esc_html(get_the_date('M j')); ?>
                                                </small>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </article>
                                
                            </div>
                            <?php endwhile; ?>
                            
                        </div>
                        
                        <!-- View More Related Posts -->
                        <div class="text-center mt-4">
                            <a href="<?php echo esc_url(get_category_link(get_the_category()[0]->term_id)); ?>" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-arrow-right me-2" aria-hidden="true"></i>
                                <?php esc_html_e('Daha Fazla Benzer Yazı', 'pratikwp'); ?>
                            </a>
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
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">
                    <div class="comments-wrapper bg-white rounded shadow-sm p-4">
                        <?php comments_template(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
</div>

<?php endwhile; ?>

<!-- JavaScript for Enhanced Features -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Reading Progress Bar
    const progressBar = document.querySelector('.reading-progress-bar');
    if (progressBar) {
        function updateProgressBar() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            progressBar.style.width = scrolled + '%';
        }
        
        window.addEventListener('scroll', updateProgressBar);
        updateProgressBar();
    }
    
    // Smooth Scroll for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Table of Contents Generation
    const tocContainer = document.getElementById('table-of-contents');
    if (tocContainer) {
        const headings = document.querySelectorAll('.post-content h2, .post-content h3, .post-content h4');
        
        if (headings.length > 0) {
            const tocList = document.createElement('ul');
            tocList.className = 'toc-list list-unstyled';
            
            headings.forEach((heading, index) => {
                // Add ID to heading if it doesn't have one
                if (!heading.id) {
                    heading.id = 'heading-' + index;
                }
                
                const tocItem = document.createElement('li');
                tocItem.className = 'toc-item';
                
                const tocLink = document.createElement('a');
                tocLink.href = '#' + heading.id;
                tocLink.textContent = heading.textContent;
                tocLink.className = 'toc-link text-decoration-none d-block py-1 px-2 rounded text-muted';
                
                // Add level class based on heading level
                const level = parseInt(heading.tagName.charAt(1));
                tocLink.classList.add('toc-level-' + level);
                
                if (level > 2) {
                    tocLink.style.paddingLeft = (level - 2) * 15 + 10 + 'px';
                }
                
                tocItem.appendChild(tocLink);
                tocList.appendChild(tocItem);
            });
            
            tocContainer.appendChild(tocList);
            
            // Highlight active heading in TOC
            const tocLinks = document.querySelectorAll('.toc-link');
            
            function updateActiveTocLink() {
                let current = '';
                headings.forEach(heading => {
                    const rect = heading.getBoundingClientRect();
                    if (rect.top <= 100) {
                        current = heading.id;
                    }
                });
                
                tocLinks.forEach(link => {
                    link.classList.remove('active', 'text-primary', 'bg-light');
                    link.classList.add('text-muted');
                    if (link.getAttribute('href') === '#' + current) {
                        link.classList.add('active', 'text-primary', 'bg-light');
                        link.classList.remove('text-muted');
                    }
                });
            }
            
            window.addEventListener('scroll', updateProgressBar);
            updateActiveTocLink();
        } else {
            tocContainer.innerHTML = '<p class="text-muted small mb-0">' + '<?php esc_html_e("Bu yazıda başlık bulunamadı.", "pratikwp"); ?>' + '</p>';
        }
    }
    
    // Copy Link Functionality
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
        const originalHTML = btn.innerHTML;
        const isVertical = btn.closest('.share-buttons-vertical');
        
        if (isVertical) {
            btn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i>';
            btn.style.backgroundColor = '#28a745';
        } else {
            btn.innerHTML = '<i class="fas fa-check me-1" aria-hidden="true"></i><?php esc_html_e("Kopyalandı!", "pratikwp"); ?>';
            btn.classList.add('btn-success');
            btn.classList.remove('btn-outline-secondary');
        }
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
            if (isVertical) {
                btn.style.backgroundColor = '';
            } else {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-secondary');
            }
        }, 2000);
    }
    
    // Related Post Hover Effects
    document.querySelectorAll('.related-post-image').forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
});
</script>

<!-- Styles for Full Width Template -->
<style>
/* Hero Styles */
.single-post-hero {
    min-height: 100vh;
    position: relative;
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-image {
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(45deg, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
}

.hero-content {
    position: relative;
    z-index: 2;
    min-height: 100vh;
}

.text-shadow {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.animate-bounce {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Reading Progress Bar */
.reading-progress-bar {
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 4px;
    background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
    z-index: 9999;
    transition: width 0.25s ease;
}

/* Social Share Sticky */
.social-share-sticky {
    position: fixed;
    left: 30px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 100;
}

.share-buttons-vertical {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.share-buttons-vertical .share-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--pratikwp-transition);
    font-size: 18px;
    border: none;
    cursor: pointer;
}

.share-facebook { background-color: #1877f2; }
.share-twitter { background-color: #1da1f2; }
.share-linkedin { background-color: #0077b5; }
.share-whatsapp { background-color: #25d366; }
.share-copy { background-color: #6c757d; }

.share-buttons-vertical .share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

/* Table of Contents */
.table-of-contents-wrapper {
    top: 100px;
}

.toc-link.active {
    font-weight: 600;
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .social-share-sticky {
        display: none;
    }
    
    .hero-content {
        padding: 2rem 0;
        min-height: 80vh;
    }
    
    .hero-title {
        font-size: 2.5rem !important;
    }
}

@media (max-width: 768px) {
    .hero-content {
        min-height: 70vh;
    }
    
    .hero-title {
        font-size: 2rem !important;
    }
    
    .hero-meta {
        flex-direction: column;
        gap: 1rem !important;
    }
}

/* Print Styles */
@media print {
    .social-share-sticky,
    .social-share-mobile,
    .scroll-indicator,
    .reading-progress-bar,
    .table-of-contents-wrapper {
        display: none !important;
    }
    
    .hero-overlay {
        display: none;
    }
    
    .hero-content {
        color: #000 !important;
    }
}
</style>