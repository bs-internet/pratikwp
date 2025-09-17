<?php
/**
 * Single Post Minimal Template
 * Clean, minimal layout for single post pages focused on readability
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get minimal template specific settings
$show_featured_image = get_theme_mod('single_minimal_show_featured_image', true);
$show_post_meta = get_theme_mod('single_minimal_show_post_meta', true);
$show_author_bio = get_theme_mod('single_minimal_show_author_bio', false);
$show_related_posts = get_theme_mod('single_minimal_show_related_posts', false);
$show_social_share = get_theme_mod('single_minimal_show_social_share', true);
$typography_style = get_theme_mod('single_minimal_typography', 'serif'); // serif, sans-serif
$content_width = get_theme_mod('single_minimal_content_width', 'narrow'); // narrow, medium

// Content width classes
$width_classes = [
    'narrow' => 'col-lg-6 col-md-8 col-12',
    'medium' => 'col-lg-8 col-md-10 col-12'
];
$width_class = $width_classes[$content_width] ?? 'col-lg-6 col-md-8 col-12';

while (have_posts()): the_post();
?>

<div class="single-post-layout single-minimal-template typography-<?php echo esc_attr($typography_style); ?>">

    <!-- Minimal Header -->
    <header class="minimal-post-header py-5">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr($width_class); ?> mx-auto text-center">

                    <!-- Post Categories (Minimal) -->
                    <?php if ($show_post_meta && has_category()): ?>
                    <div class="post-categories mb-3">
                        <?php
                        $primary_category = get_the_category()[0];
                        if ($primary_category) {
                            echo '<a href="' . esc_url(get_category_link($primary_category->term_id)) . '" class="category-link text-decoration-none text-uppercase small fw-bold tracking-wide text-muted">' . esc_html($primary_category->name) . '</a>';
                        }
                        ?>
                    </div>
                    <?php endif; ?>

                    <!-- Post Title -->
                    <h1 class="minimal-post-title mb-4" itemprop="headline">
                        <?php the_title(); ?>
                    </h1>

                    <!-- Minimal Meta -->
                    <?php if ($show_post_meta): ?>
                    <div class="minimal-post-meta mb-4">
                        <div class="meta-line text-muted small">

                            <!-- Author -->
                            <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                <span class="by-author">
                                    <?php esc_html_e('tarafından', 'pratikwp'); ?>
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                        class="author-link text-decoration-none" itemprop="name">
                                        <?php the_author(); ?>
                                    </a>
                                </span>
                            </span>

                            <span class="meta-separator mx-2">•</span>

                            <!-- Date -->
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                <?php echo esc_html(get_the_date('F j, Y')); ?>
                            </time>

                            <?php
                            // Calculate reading time
                            $content = get_the_content();
                            $word_count = str_word_count(wp_strip_all_tags($content));
                            $reading_time = ceil($word_count / 200);
                            ?>

                            <span class="meta-separator mx-2">•</span>

                            <!-- Reading Time -->
                            <span class="reading-time">
                                <?php
                                printf(
                                    esc_html(_n('%d dakika okuma', '%d dakika okuma', $reading_time, 'pratikwp')),
                                    $reading_time
                                );
                                ?>
                            </span>

                        </div>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </header>

    <!-- Featured Image (Minimal Style) -->
    <?php if ($show_featured_image && has_post_thumbnail()): ?>
    <div class="minimal-featured-image mb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">
                    <figure class="featured-image-wrapper">
                        <?php
                        the_post_thumbnail('full', [
                            'class' => 'img-fluid w-100',
                            'alt' => get_the_title(),
                            'itemprop' => 'image',
                            'style' => 'border-radius: 8px;'
                        ]);
                        ?>

                        <?php if (get_the_post_thumbnail_caption()): ?>
                        <figcaption class="image-caption text-center mt-3 text-muted small fst-italic">
                            <?php echo wp_kses_post(get_the_post_thumbnail_caption()); ?>
                        </figcaption>
                        <?php endif; ?>
                    </figure>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="minimal-post-content">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr($width_class); ?> mx-auto">

                    <article id="post-<?php the_ID(); ?>" <?php post_class('minimal-post-article'); ?> itemscope
                        itemtype="https://schema.org/Article">

                        <!-- Post Content -->
                        <div class="post-content minimal-content" itemprop="articleBody">
                            <?php
                            the_content();
                            
                            wp_link_pages([
                                'before' => '<nav class="page-links mt-5 text-center"><span class="page-links-title small text-muted text-uppercase tracking-wide mb-3 d-block">' . esc_html__('Sayfalar', 'pratikwp') . '</span><div class="page-numbers">',
                                'after' => '</div></nav>',
                                'link_before' => '<span class="page-number">',
                                'link_after' => '</span>',
                            ]);
                            ?>
                        </div>

                        <!-- Minimal Footer -->
                        <footer class="minimal-post-footer mt-5 pt-4">

                            <!-- Tags (Minimal Style) -->
                            <?php if (has_tag()): ?>
                            <div class="minimal-tags mb-4">
                                <div class="tags-wrapper">
                                    <?php
                                    $tags = get_the_tags();
                                    if ($tags) {
                                        echo '<div class="tags-list text-center">';
                                        foreach (array_slice($tags, 0, 5) as $tag) { // Show max 5 tags
                                            echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link text-decoration-none text-muted small me-3">#' . esc_html($tag->name) . '</a>';
                                        }
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Social Share (Minimal) -->
                            <?php if ($show_social_share): ?>
                            <div class="minimal-social-share text-center mb-4">
                                <div class="share-label mb-3">
                                    <span class="small text-muted text-uppercase tracking-wide">
                                        <?php esc_html_e('Paylaş', 'pratikwp'); ?>
                                    </span>
                                </div>

                                <div class="share-buttons d-flex justify-content-center align-items-center gap-3">

                                    <?php
                                    $post_url = urlencode(get_permalink());
                                    $post_title = urlencode(get_the_title());
                                    ?>

                                    <!-- Twitter -->
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>"
                                        target="_blank" rel="noopener noreferrer" class="share-btn share-twitter"
                                        aria-label="<?php esc_attr_e('Twitter\'da paylaş', 'pratikwp'); ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    </a>

                                    <!-- Facebook -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>"
                                        target="_blank" rel="noopener noreferrer" class="share-btn share-facebook"
                                        aria-label="<?php esc_attr_e('Facebook\'ta paylaş', 'pratikwp'); ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    </a>

                                    <!-- LinkedIn -->
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $post_url; ?>"
                                        target="_blank" rel="noopener noreferrer" class="share-btn share-linkedin"
                                        aria-label="<?php esc_attr_e('LinkedIn\'de paylaş', 'pratikwp'); ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                        </svg>
                                    </a>

                                    <!-- Copy Link -->
                                    <button class="share-btn share-copy copy-link-btn"
                                        data-url="<?php the_permalink(); ?>"
                                        aria-label="<?php esc_attr_e('Linki kopyala', 'pratikwp'); ?>">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" />
                                        </svg>
                                    </button>

                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- Updated Date (Minimal) -->
                            <?php if (get_the_modified_date() !== get_the_date()): ?>
                            <div class="minimal-updated-date text-center mb-4">
                                <small class="text-muted">
                                    <?php esc_html_e('Son güncelleme:', 'pratikwp'); ?>
                                    <time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"
                                        itemprop="dateModified">
                                        <?php echo esc_html(get_the_modified_date('F j, Y')); ?>
                                    </time>
                                </small>
                            </div>
                            <?php endif; ?>

                            <!-- Divider -->
                            <div class="minimal-divider text-center mb-4">
                                <span class="divider-symbol text-muted">***</span>
                            </div>

                        </footer>

                        <!-- Structured Data -->
                        <meta itemprop="url" content="<?php the_permalink(); ?>">
                        <meta itemprop="wordCount"
                            content="<?php echo esc_attr(str_word_count(wp_strip_all_tags(get_the_content()))); ?>">

                        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization"
                            style="display: none;">
                            <meta itemprop="name" content="<?php bloginfo('name'); ?>">
                            <meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>">
                        </div>

                    </article>

                </div>
            </div>
        </div>
    </main>

    <!-- Minimal Author Bio -->
    <?php if ($show_author_bio): ?>
    <section class="minimal-author-bio py-5">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr($width_class); ?> mx-auto text-center">

                    <div class="author-bio-minimal">

                        <!-- Author Avatar -->
                        <div class="author-avatar mb-3">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80, '', get_the_author(), ['class' => 'rounded-circle']); ?>
                            </a>
                        </div>

                        <!-- Author Name -->
                        <h4 class="author-name mb-2">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                class="text-decoration-none">
                                <?php the_author(); ?>
                            </a>
                        </h4>

                        <!-- Author Description -->
                        <?php if (get_the_author_meta('description')): ?>
                        <p class="author-description text-muted mb-3">
                            <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                        </p>
                        <?php endif; ?>

                        <!-- Author Link -->
                        <div class="author-link">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"
                                class="text-decoration-none small text-muted">
                                <?php esc_html_e('Diğer yazıları görüntüle', 'pratikwp'); ?> →
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Minimal Related Posts -->
    <?php if ($show_related_posts): ?>
    <section class="minimal-related-posts py-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12 mx-auto">

                    <?php
                    // Get related posts by categories (minimal amount)
                    $related_posts = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => 2,
                        'post__not_in' => [get_the_ID()],
                        'category__in' => wp_get_post_categories(get_the_ID()),
                        'orderby' => 'rand',
                        'meta_key' => '_thumbnail_id'
                    ]);
                    ?>

                    <?php if ($related_posts->have_posts()): ?>
                    <div class="related-posts-minimal">

                        <h3 class="related-title text-center mb-4 small text-muted text-uppercase tracking-wide">
                            <?php esc_html_e('Daha fazlasını oku', 'pratikwp'); ?>
                        </h3>

                        <div class="row">

                            <?php while ($related_posts->have_posts()): $related_posts->the_post(); ?>
                            <div class="col-md-6 col-12 mb-4">

                                <article class="related-post-minimal text-center">

                                    <!-- Category -->
                                    <?php if (has_category()): ?>
                                    <div class="post-category mb-2">
                                        <?php
                                        $primary_category = get_the_category()[0];
                                        if ($primary_category) {
                                            echo '<a href="' . esc_url(get_category_link($primary_category->term_id)) . '" class="category-link text-decoration-none text-uppercase small text-muted tracking-wide">' . esc_html($primary_category->name) . '</a>';
                                        }
                                        ?>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Title -->
                                    <h4 class="related-post-title mb-2">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h4>

                                    <!-- Date -->
                                    <div class="related-post-date">
                                        <small class="text-muted">
                                            <?php echo esc_html(get_the_date('F j, Y')); ?>
                                        </small>
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

    <!-- Minimal Comments -->
    <?php if (comments_open() || get_comments_number()): ?>
    <section class="minimal-comments py-5">
        <div class="container">
            <div class="row">
                <div class="<?php echo esc_attr($width_class); ?> mx-auto">

                    <div class="comments-wrapper">
                        <!-- Comments Title -->
                        <h3 class="comments-title text-center mb-4 small text-muted text-uppercase tracking-wide">
                            <?php
                            $comments_number = get_comments_number();
                            if ($comments_number == 0) {
                                esc_html_e('Yorumlar', 'pratikwp');
                            } else {
                                printf(
                                    esc_html(_n('%d Yorum', '%d Yorum', $comments_number, 'pratikwp')),
                                    number_format_i18n($comments_number)
                                );
                            }
                            ?>
                        </h3>

                        <?php comments_template(); ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<?php endwhile; ?>

<!-- Minimal Template JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Copy Link Functionality
        const copyBtns = document.querySelectorAll('.copy-link-btn');
        copyBtns.forEach(btn => {
            btn.addEventListener('click', function () {
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
            const originalContent = btn.innerHTML;
            btn.innerHTML =
                '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>';
            btn.style.color = '#28a745';

            setTimeout(() => {
                btn.innerHTML = originalContent;
                btn.style.color = '';
            }, 2000);
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add reading progress indicator (subtle)
        const article = document.querySelector('.minimal-content');
        if (article) {
            let ticking = false;

            function updateReadingProgress() {
                const articleTop = article.getBoundingClientRect().top;
                const articleHeight = article.offsetHeight;
                const windowHeight = window.innerHeight;
                const scrollProgress = Math.max(0, Math.min(1, (windowHeight - articleTop) / articleHeight));

                // You can use this progress value for any subtle indicators
                // For minimal design, we keep it very subtle
                document.documentElement.style.setProperty('--reading-progress', scrollProgress);

                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateReadingProgress);
                    ticking = true;
                }
            }

            window.addEventListener('scroll', requestTick);
            updateReadingProgress();
        }
    });
</script>

<!-- Minimal Template Styles -->
<style>
    /* Minimal Template Specific Styles */
    .single-minimal-template {
        font-family: var(--bs-font-family-base);
        line-height: 1.7;
    }

    /* Typography Styles */
    .typography-serif .minimal-post-title,
    .typography-serif .minimal-content {
        font-family: Georgia, 'Times New Roman', Times, serif;
    }

    .typography-sans-serif .minimal-post-title,
    .typography-sans-serif .minimal-content {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }

    /* Minimal Post Title */
    .minimal-post-title {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: #1a1a1a;
        margin-bottom: 2rem;
    }

    /* Minimal Content */
    .minimal-content {
        font-size: 1.125rem;
        line-height: 1.8;
        color: #333;
    }

    .minimal-content p {
        margin-bottom: 1.5rem;
    }

    .minimal-content h2,
    .minimal-content h3,
    .minimal-content h4,
    .minimal-content h5,
    .minimal-content h6 {
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        font-weight: 600;
        line-height: 1.3;
    }

    .minimal-content h2 {
        font-size: 1.75rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 0.5rem;
    }

    .minimal-content h3 {
        font-size: 1.5rem;
    }

    .minimal-content h4 {
        font-size: 1.25rem;
    }

    .minimal-content blockquote {
        border-left: 4px solid #007cba;
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: #666;
    }

    .minimal-content ul,
    .minimal-content ol {
        padding-left: 2rem;
        margin-bottom: 1.5rem;
    }

    .minimal-content li {
        margin-bottom: 0.5rem;
    }

    /* Utility Classes */
    .tracking-wide {
        letter-spacing: 0.05em;
    }

    /* Share Buttons */
    .share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 1px solid #ddd;
        background: white;
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .share-btn:hover {
        background: #f8f9fa;
        color: #333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Page Links */
    .page-links {
        text-align: center;
        margin: 3rem 0;
    }

    .page-links .page-number {
        display: inline-block;
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #666;
        transition: all 0.3s ease;
    }

    .page-links .page-number:hover {
        background: #f8f9fa;
        color: #333;
    }

    /* Tags */
    .minimal-tags .tag-link {
        transition: color 0.3s ease;
    }

    .minimal-tags .tag-link:hover {
        color: #007cba !important;
    }

    /* Author Bio */
    .author-bio-minimal .author-avatar img {
        border: 3px solid #f8f9fa;
        transition: transform 0.3s ease;
    }

    .author-bio-minimal .author-avatar:hover img {
        transform: scale(1.05);
    }

    /* Related Posts */
    .related-post-minimal .related-post-title {
        font-size: 1.25rem;
        line-height: 1.3;
    }

    .related-post-minimal .related-post-title a {
        color: #333;
        transition: color 0.3s ease;
    }

    .related-post-minimal .related-post-title a:hover {
        color: #007cba;
    }

    /* Divider */
    .divider-symbol {
        font-size: 1.5rem;
        letter-spacing: 0.5rem;
    }

    /* Comments */
    .minimal-comments .comment-list {
        list-style: none;
        padding: 0;
    }

    .minimal-comments .comment {
        border-bottom: 1px solid #eee;
        padding: 1.5rem 0;
    }

    .minimal-comments .comment:last-child {
        border-bottom: none;
    }

    /* Featured Image */
    .minimal-featured-image img {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Reading Progress (subtle) */
    .single-minimal-template::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        height: 2px;
        background: linear-gradient(90deg, #007cba, #17a2b8);
        width: calc(var(--reading-progress, 0) * 100%);
        z-index: 9999;
        transition: width 0.3s ease;
    }

    /* Responsive Design */
    <blade media|%20(max-width%3A%20768px)%20%7B%0D>.minimal-post-title {
        font-size: 2rem;
    }

    .minimal-content {
        font-size: 1rem;
        line-height: 1.7;
    }

    .minimal-content h2 {
        font-size: 1.5rem;
    }

    .minimal-content h3 {
        font-size: 1.25rem;
    }

    .share-buttons {
        gap: 1rem !important;
    }

    .minimal-post-meta .meta-line {
        flex-direction: column;
        text-align: center;
        line-height: 1.6;
    }

    .meta-separator {
        display: none;
    }
    }

    /* Print Styles */
    <blade media|%20print%20%7B%0D>.minimal-social-share,
    .single-minimal-template::before {
        display: none !important;
    }

    .minimal-content {
        color: #000 !important;
        font-size: 12pt;
        line-height: 1.5;
    }

    .minimal-post-title {
        color: #000 !important;
        font-size: 18pt;
        margin-bottom: 1rem;
    }

    .minimal-post-meta {
        font-size: 10pt;
        margin-bottom: 1rem;
    }
    }

    /* Dark Mode Support */
    <blade media|%20(prefers-color-scheme%3A%20dark)%20%7B%0D>.single-minimal-template {
        background: #1a1a1a;
        color: #e0e0e0;
    }

    .minimal-post-title {
        color: #ffffff;
    }

    .minimal-content {
        color: #e0e0e0;
    }

    .minimal-content h2 {
        border-bottom-color: #333;
    }

    .minimal-content blockquote {
        color: #ccc;
    }

    .share-btn {
        background: #2a2a2a;
        border-color: #444;
        color: #ccc;
    }

    .share-btn:hover {
        background: #333;
        color: #fff;
    }
    }

    /* High Contrast Mode */
    <blade media|%20(prefers-contrast%3A%20high)%20%7B%0D>.minimal-post-title,
    .minimal-content {
        color: #000 !important;
    }

    .share-btn {
        border: 2px solid #000;
    }

    .minimal-content blockquote {
        border-left-color: #000;
    }
    }

    /* Reduced Motion */
    <blade media|%20(prefers-reduced-motion%3A%20reduce)%20%7B%0D>.share-btn,
    .author-bio-minimal .author-avatar img,
    .single-minimal-template::before {
        transition: none !important;
    }
    }

    /* Focus Management for Accessibility */
    .share-btn:focus,
    .tag-link:focus,
    .author-link a:focus,
    .related-post-title a:focus {
        outline: 2px solid #007cba;
        outline-offset: 2px;
    }

    /* Content Link Styling */
    .minimal-content a {
        color: #007cba;
        text-decoration: underline;
        text-decoration-thickness: 1px;
        text-underline-offset: 3px;
        transition: all 0.3s ease;
    }

    .minimal-content a:hover {
        color: #005a87;
        text-decoration-thickness: 2px;
    }

    /* Image Captions */
    .minimal-content .wp-caption {
        max-width: 100%;
        margin: 2rem auto;
        text-align: center;
    }

    .minimal-content .wp-caption-text {
        font-size: 0.9rem;
        color: #666;
        font-style: italic;
        margin-top: 0.5rem;
    }

    /* Code Styling */
    .minimal-content code {
        background: #f8f9fa;
        padding: 0.2em 0.4em;
        border-radius: 3px;
        font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
        font-size: 0.9em;
        color: #e83e8c;
    }

    .minimal-content pre {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 6px;
        overflow-x: auto;
        margin: 2rem 0;
        border-left: 4px solid #007cba;
    }

    .minimal-content pre code {
        background: none;
        padding: 0;
        color: #333;
    }
</style>