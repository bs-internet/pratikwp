<?php
/**
 * Template part for displaying single posts
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$show_featured_image = get_theme_mod('single_show_featured_image', true);
$show_post_meta = get_theme_mod('single_show_post_meta', true);
$show_author_bio = get_theme_mod('single_show_author_bio', true);
$show_post_navigation = get_theme_mod('single_show_post_navigation', true);
$show_related_posts = get_theme_mod('single_show_related_posts', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

    <?php if ($show_featured_image && has_post_thumbnail()) : ?>
    <div class="post-thumbnail mb-4">
        <?php
        the_post_thumbnail('full', [
            'class' => 'img-fluid rounded',
            'loading' => 'eager'
        ]);
        
        // Featured image caption
        $caption = get_the_post_thumbnail_caption();
        if ($caption) {
            echo '<figcaption class="wp-caption-text text-muted mt-2 small">' . esc_html($caption) . '</figcaption>';
        }
        ?>
    </div>
    <?php endif; ?>

    <header class="entry-header mb-4">
        
        <?php if (get_theme_mod('single_show_categories', true)) : ?>
        <div class="post-categories mb-3">
            <?php
            $categories = get_the_category();
            if ($categories) {
                echo '<div class="categories-list">';
                foreach ($categories as $category) {
                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-primary me-2">';
                    echo esc_html($category->name);
                    echo '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php endif; ?>

        <?php the_title('<h1 class="entry-title mb-3">', '</h1>'); ?>

        <?php if ($show_post_meta) : ?>
        <div class="entry-meta mb-4 pb-3 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <?php
                    // Author with avatar
                    if (get_theme_mod('single_show_author', true)) {
                        $author_id = get_the_author_meta('ID');
                        echo '<div class="author-meta d-inline-flex align-items-center me-4 mb-2">';
                        echo get_avatar($author_id, 32, '', '', ['class' => 'rounded-circle me-2']);
                        echo '<span>';
                        echo '<strong>' . esc_html__('Yazar:', 'pratikwp') . '</strong> ';
                        echo '<a href="' . esc_url(get_author_posts_url($author_id)) . '">' . esc_html(get_the_author()) . '</a>';
                        echo '</span>';
                        echo '</div>';
                    }

                    // Publish date
                    if (get_theme_mod('single_show_date', true)) {
                        echo '<div class="date-meta d-inline-flex align-items-center me-4 mb-2">';
                        echo '<i class="fas fa-calendar me-2"></i>';
                        echo '<span>';
                        echo '<strong>' . esc_html__('Tarih:', 'pratikwp') . '</strong> ';
                        echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
                        echo '</span>';
                        echo '</div>';
                    }

                    // Modified date
                    if (get_theme_mod('single_show_modified_date', false) && get_the_modified_date() !== get_the_date()) {
                        echo '<div class="modified-date-meta d-inline-flex align-items-center me-4 mb-2">';
                        echo '<i class="fas fa-edit me-2"></i>';
                        echo '<span>';
                        echo '<strong>' . esc_html__('Güncelleme:', 'pratikwp') . '</strong> ';
                        echo '<time datetime="' . esc_attr(get_the_modified_date('c')) . '">' . esc_html(get_the_modified_date()) . '</time>';
                        echo '</span>';
                        echo '</div>';
                    }

                    // Reading time
                    if (get_theme_mod('single_show_reading_time', true)) {
                        $content = get_post_field('post_content', get_the_ID());
                        $word_count = str_word_count(strip_tags($content));
                        $reading_time = ceil($word_count / 200);
                        
                        echo '<div class="reading-time-meta d-inline-flex align-items-center me-4 mb-2">';
                        echo '<i class="fas fa-clock me-2"></i>';
                        echo '<span>';
                        echo '<strong>' . esc_html__('Okuma Süresi:', 'pratikwp') . '</strong> ';
                        echo sprintf(_n('%s dakika', '%s dakika', $reading_time, 'pratikwp'), $reading_time);
                        echo '</span>';
                        echo '</div>';
                    }
                    ?>
                </div>
                
                <div class="col-md-4 text-md-end">
                    <?php
                    // Comments count
                    if (get_theme_mod('single_show_comments_count', true) && (comments_open() || get_comments_number())) {
                        echo '<div class="comments-count-meta mb-2">';
                        echo '<i class="fas fa-comments me-2"></i>';
                        $comments_count = get_comments_number();
                        if ($comments_count == 0) {
                            echo esc_html__('Henüz yorum yok', 'pratikwp');
                        } else {
                            echo sprintf(_n('%s yorum', '%s yorum', $comments_count, 'pratikwp'), $comments_count);
                        }
                        echo '</div>';
                    }

                    // View count (if plugin available)
                    if (get_theme_mod('single_show_view_count', false) && function_exists('pvc_get_post_views')) {
                        echo '<div class="view-count-meta mb-2">';
                        echo '<i class="fas fa-eye me-2"></i>';
                        echo sprintf(esc_html__('%s görüntüleme', 'pratikwp'), pvc_get_post_views());
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </header>

    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages([
            'before' => '<div class="page-links mt-4"><h4 class="page-links-title">' . esc_html__('Sayfalar:', 'pratikwp') . '</h4>',
            'after'  => '</div>',
            'link_before' => '<span class="page-number">',
            'link_after'  => '</span>',
        ]);
        ?>
    </div>

    <?php if (get_theme_mod('single_show_tags', true) && has_tag()) : ?>
    <footer class="entry-footer mt-4 pt-4 border-top">
        <div class="post-tags">
            <h5 class="tags-title mb-3"><?php esc_html_e('Etiketler:', 'pratikwp'); ?></h5>
            <div class="tags-list">
                <?php
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link badge bg-light text-dark border me-2 mb-2 p-2">';
                        echo '<i class="fas fa-tag me-1"></i>' . esc_html($tag->name);
                        echo '</a>';
                    }
                }
                ?>
            </div>
        </div>
    </footer>
    <?php endif; ?>

</article>

<?php
// Social sharing
if (get_theme_mod('single_show_social_share', true)) {
    get_template_part('template-parts/post/social-share');
}

// Author bio
if ($show_author_bio && get_the_author_meta('description')) {
    get_template_part('template-parts/post/author-bio');
}

// Post navigation
if ($show_post_navigation) {
    get_template_part('template-parts/post/navigation');
}

// Related posts
if ($show_related_posts) {
    get_template_part('template-parts/post/related-posts');
}
?>