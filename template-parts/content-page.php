<?php
/**
 * Template part for displaying page content
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$show_featured_image = get_theme_mod('page_show_featured_image', false);
$show_page_meta = get_theme_mod('page_show_meta', false);
$show_page_title = get_post_meta(get_the_ID(), '_pratikwp_hide_title', true) !== '1';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>

    <?php if ($show_featured_image && has_post_thumbnail()) : ?>
    <div class="page-thumbnail mb-4">
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

    <?php if ($show_page_title) : ?>
    <header class="entry-header mb-4">
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        
        <?php if ($show_page_meta) : ?>
        <div class="entry-meta mt-3 text-muted">
            <?php
            // Author
            echo '<span class="author-meta me-3">';
            echo '<i class="fas fa-user me-1"></i>';
            echo esc_html__('Yazar:', 'pratikwp') . ' ';
            echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
            echo '</span>';

            // Publish date
            echo '<span class="date-meta me-3">';
            echo '<i class="fas fa-calendar me-1"></i>';
            echo esc_html__('Yayınlanma:', 'pratikwp') . ' ';
            echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
            echo '</span>';

            // Modified date
            if (get_the_modified_date() !== get_the_date()) {
                echo '<span class="modified-date-meta">';
                echo '<i class="fas fa-edit me-1"></i>';
                echo esc_html__('Güncelleme:', 'pratikwp') . ' ';
                echo '<time datetime="' . esc_attr(get_the_modified_date('c')) . '">' . esc_html(get_the_modified_date()) . '</time>';
                echo '</span>';
            }
            ?>
        </div>
        <?php endif; ?>
    </header>
    <?php endif; ?>

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

    <?php if (get_edit_post_link()) : ?>
    <footer class="entry-footer mt-4 pt-3 border-top">
        <?php
        edit_post_link(
            sprintf(
                wp_kses(
                    __('Düzenle <span class="screen-reader-text">"%s"</span>', 'pratikwp'),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                wp_kses_post(get_the_title())
            ),
            '<span class="edit-link"><i class="fas fa-edit me-1"></i>',
            '</span>'
        );
        ?>
    </footer>
    <?php endif; ?>

</article>