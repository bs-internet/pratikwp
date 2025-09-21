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
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <!-- Post Thumbnail -->
    <?php if (has_post_thumbnail()) : ?>
    <div class="post-thumbnail">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium_large', ['class' => 'img-fluid']); ?>
        </a>
    </div>
    <?php endif; ?>
    
    <!-- Post Header -->
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        ?>
        
        <?php if ('post' === get_post_type()) : ?>
        <div class="entry-meta">
            <span class="posted-on">
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </span>
            
            <span class="byline">
                <?php esc_html_e('Yazar:', 'pratikwp'); ?>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                    <?php the_author(); ?>
                </a>
            </span>
            
            <?php
            $categories = get_the_category();
            if ($categories) :
            ?>
            <span class="cat-links">
                <?php esc_html_e('Kategori:', 'pratikwp'); ?>
                <?php the_category(', '); ?>
            </span>
            <?php endif; ?>
            
            <?php if (comments_open() || get_comments_number()) : ?>
            <span class="comments-link">
                <a href="<?php comments_link(); ?>">
                    <?php
                    $comments_number = get_comments_number();
                    if ($comments_number == 0) {
                        esc_html_e('Yorum Yok', 'pratikwp');
                    } elseif ($comments_number == 1) {
                        esc_html_e('1 Yorum', 'pratikwp');
                    } else {
                        printf(__('%s Yorum', 'pratikwp'), $comments_number);
                    }
                    ?>
                </a>
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </header>

    <!-- Post Content -->
    <div class="entry-content">
        <?php
        if (is_singular()) {
            the_content();
            
            wp_link_pages([
                'before' => '<div class="page-links">' . esc_html__('Sayfalar:', 'pratikwp'),
                'after'  => '</div>',
            ]);
        } else {
            the_excerpt();
        }
        ?>
    </div>

    <!-- Post Footer -->
    <footer class="entry-footer">
        <?php if (!is_singular()) : ?>
        <div class="read-more">
            <a href="<?php the_permalink(); ?>" class="btn btn-outline">
                <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
            </a>
        </div>
        <?php endif; ?>
        
        <?php if (is_singular() && 'post' === get_post_type()) : ?>
            <?php
            $tags = get_the_tags();
            if ($tags) :
            ?>
            <div class="tag-links">
                <strong><?php esc_html_e('Etiketler:', 'pratikwp'); ?></strong>
                <?php the_tags('', ', '); ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (current_user_can('edit_post', get_the_ID())) : ?>
        <div class="edit-link">
            <?php edit_post_link(__('Düzenle', 'pratikwp')); ?>
        </div>
        <?php endif; ?>
    </footer>

</article><!-- #post-<?php the_ID(); ?> -->