<?php
/**
 * Archive template
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

        <?php if (have_posts()) : ?>

        <header class="page-header mb-4">
            <?php
                the_archive_title('<h1 class="page-title">', '</h1>');
                the_archive_description('<div class="archive-description">', '</div>');
                ?>
        </header><!-- .page-header -->

        <?php
            // Posts loop
            while (have_posts()) :
                the_post();
                
                get_template_part('template-parts/content', get_post_type());
                
            endwhile;

            // Pagination
            pratikwp_posts_pagination();

        else :

            get_template_part('template-parts/content', 'none');

        endif;
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