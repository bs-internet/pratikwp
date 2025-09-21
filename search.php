<?php
/**
 * Search results template
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
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('Arama Sonuçları: %s', 'pratikwp'),
                        '<span>' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header><!-- .page-header -->

            <?php
            // Search results loop
            while (have_posts()) :
                the_post();
                
                get_template_part('template-parts/content', 'search');
                
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