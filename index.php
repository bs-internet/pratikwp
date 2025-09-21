<?php
/**
 * Main template file
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
        if (have_posts()) :
            
            // Archive header
            if (is_home() && !is_front_page()) :
                ?>
                <header class="page-header mb-4">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
                <?php
            endif;

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