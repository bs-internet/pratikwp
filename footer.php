<?php
/**
 * Footer Template
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

            </div><!-- .container -->
        </div><!-- #content -->

<?php
    // Elementor Footer Location
    if (function_exists('elementor_theme_do_location') && elementor_theme_do_location('footer')) {
        // Elementor manages footer
    } else {
        // Fallback footer
        pratikwp_default_footer();
    }
    ?>

    </div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>