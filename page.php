<?php
/**
 * Page template
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

            get_template_part('template-parts/content', 'page');

            // Comments on pages
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;

        endwhile;
        ?>

    </main><!-- #primary -->

    <?php
    // Sidebar
    if (pratikwp_has_sidebar()) {
        get_sidebar();
    }
    ?>
</div>

<?php
get_footer();