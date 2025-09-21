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

        </div></div><?php
    // Elementor'un footer lokasyonu aktifse temanın footer'ını gösterme.
    if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {
        /**
         * pratikwp_footer hook.
         *
         * @hooked pratikwp_footer_content - 10
         */
        do_action('pratikwp_footer');
    }
    ?>

</div><?php wp_footer(); ?>

</body>
</html>