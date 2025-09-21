<?php
/**
 * Page Template
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
                 * pratikwp_page_header hook.
                 * @hooked pratikwp_page_header_content - 10
                 */
                do_action('pratikwp_page_header');

                /**
                 * pratikwp_page_content hook.
                 * @hooked pratikwp_page_content_body - 10
                 */
                do_action('pratikwp_page_content');
                ?>
            </article>

            <?php
            // Show comments if enabled for pages
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