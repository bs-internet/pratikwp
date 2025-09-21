<?php
/**
 * Single Post Template
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="row">
    <main id="primary" class="site-main <?php echo esc_attr(pratikwp_main_class()); ?>">

        <?php
        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php
                /**
                 * pratikwp_post_header hook.
                 * @hooked pratikwp_post_header_content - 10
                 */
                do_action('pratikwp_post_header');

                /**
                 * pratikwp_post_content hook.
                 * @hooked pratikwp_post_content_body - 10
                 */
                do_action('pratikwp_post_content');

                /**
                 * pratikwp_post_footer hook.
                 * @hooked pratikwp_post_footer_content - 10
                 */
                do_action('pratikwp_post_footer');
                ?>
            </article>

            <?php
            // Author bio, post navigation, and comments
            if (get_theme_mod('show_author_bio', true)) {
                get_template_part('template-parts/author-bio');
            }
            pratikwp_post_navigation();
            
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php
        endwhile; // End of the loop.
        ?>

    </main><?php
    if (pratikwp_has_sidebar()) {
        get_sidebar();
    }
    ?>
</div>

<?php
get_footer();