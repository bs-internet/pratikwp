<?php
/**
 * Template part for displaying posts
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$post_layout = get_theme_mod('blog_layout', 'grid');
$show_excerpt = get_theme_mod('show_post_excerpt', true);
$excerpt_length = get_theme_mod('excerpt_length', 30);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-item mb-4'); ?>>
    
    <?php if (has_post_thumbnail()) : ?>
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
            <?php
            if ($post_layout === 'list') {
                the_post_thumbnail('medium', [
                    'class' => 'img-fluid',
                    'loading' => 'lazy'
                ]);
            } else {
                the_post_thumbnail('large', [
                    'class' => 'img-fluid',
                    'loading' => 'lazy'
                ]);
            }
            ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="post-content">
        
        <?php if (get_theme_mod('show_post_categories', true)) : ?>
        <div class="post-categories mb-2">
            <?php
            $categories = get_the_category();
            if ($categories) {
                echo '<span class="categories">';
                foreach ($categories as $category) {
                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link badge bg-primary me-1">' . esc_html($category->name) . '</a>';
                }
                echo '</span>';
            }
            ?>
        </div>
        <?php endif; ?>

        <header class="entry-header">
            <?php
            if (is_singular()) :
                the_title('<h1 class="entry-title">', '</h1>');
            else :
                the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            endif;
            ?>
        </header>

        <?php if (get_theme_mod('show_post_meta', true)) : ?>
        <div class="entry-meta mb-3">
            <?php
            // Author
            if (get_theme_mod('show_post_author', true)) {
                echo '<span class="author-meta me-3">';
                echo '<i class="fas fa-user me-1"></i>';
                echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
                echo '</span>';
            }

            // Date
            if (get_theme_mod('show_post_date', true)) {
                echo '<span class="date-meta me-3">';
                echo '<i class="fas fa-calendar me-1"></i>';
                echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
                echo '</span>';
            }

            // Comments
            if (get_theme_mod('show_post_comments', true) && (comments_open() || get_comments_number())) {
                echo '<span class="comments-meta me-3">';
                echo '<i class="fas fa-comments me-1"></i>';
                comments_popup_link(
                    esc_html__('Yorum Yok', 'pratikwp'),
                    esc_html__('1 Yorum', 'pratikwp'),
                    esc_html__('% Yorum', 'pratikwp')
                );
                echo '</span>';
            }

            // Reading time
            if (get_theme_mod('show_reading_time', false)) {
                $content = get_post_field('post_content', get_the_ID());
                $word_count = str_word_count(strip_tags($content));
                $reading_time = ceil($word_count / 200);
                
                echo '<span class="reading-time-meta">';
                echo '<i class="fas fa-clock me-1"></i>';
                echo sprintf(_n('%s dakika', '%s dakika', $reading_time, 'pratikwp'), $reading_time);
                echo '</span>';
            }
            ?>
        </div>
        <?php endif; ?>

        <div class="entry-content">
            <?php
            if (is_singular() || !$show_excerpt) {
                the_content(sprintf(
                    wp_kses(
                        __('Devamını oku<span class="screen-reader-text"> "%s"</span>', 'pratikwp'),
                        [
                            'span' => [
                                'class' => [],
                            ],
                        ]
                    ),
                    wp_kses_post(get_the_title())
                ));

                wp_link_pages([
                    'before' => '<div class="page-links">' . esc_html__('Sayfalar:', 'pratikwp'),
                    'after'  => '</div>',
                ]);
            } else {
                // Custom excerpt
                if (has_excerpt()) {
                    the_excerpt();
                } else {
                    $content = get_the_content();
                    $content = wp_strip_all_tags($content);
                    $words = explode(' ', $content);
                    
                    if (count($words) > $excerpt_length) {
                        $excerpt = implode(' ', array_slice($words, 0, $excerpt_length)) . '...';
                    } else {
                        $excerpt = $content;
                    }
                    
                    echo '<p>' . esc_html($excerpt) . '</p>';
                }

                // Read more link
                if (get_theme_mod('show_read_more', true)) {
                    echo '<p class="read-more-wrap mt-3">';
                    echo '<a href="' . esc_url(get_permalink()) . '" class="btn btn-outline-primary btn-sm read-more-link">';
                    echo esc_html(get_theme_mod('read_more_text', __('Devamını Oku', 'pratikwp')));
                    echo ' <i class="fas fa-arrow-right ms-1"></i>';
                    echo '</a>';
                    echo '</p>';
                }
            }
            ?>
        </div>

        <?php if (get_theme_mod('show_post_tags', true) && has_tag()) : ?>
        <div class="entry-tags mt-3">
            <?php
            $tags = get_the_tags();
            if ($tags) {
                echo '<div class="tags-list">';
                echo '<i class="fas fa-tags me-2"></i>';
                foreach ($tags as $tag) {
                    echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="tag-link badge bg-secondary me-1">#' . esc_html($tag->name) . '</a>';
                }
                echo '</div>';
            }
            ?>
        </div>
        <?php endif; ?>

    </div>

</article>