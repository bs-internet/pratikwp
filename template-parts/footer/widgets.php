<?php
/**
 * Template part for displaying footer widgets
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$footer_layout = get_theme_mod('footer_layout', '4-columns');
$footer_style = get_theme_mod('footer_style', 'dark');
$show_footer_widgets = get_theme_mod('show_footer_widgets', true);

if (!$show_footer_widgets) {
    return;
}

// Check if any footer widget area has widgets
$has_widgets = false;
for ($i = 1; $i <= 4; $i++) {
    if (is_active_sidebar('footer-' . $i)) {
        $has_widgets = true;
        break;
    }
}

if (!$has_widgets) {
    return;
}

// Define column classes based on layout
$column_classes = [
    '1-column' => ['col-12'],
    '2-columns' => ['col-md-6', 'col-md-6'],
    '3-columns' => ['col-md-4', 'col-md-4', 'col-md-4'],
    '4-columns' => ['col-lg-3 col-md-6', 'col-lg-3 col-md-6', 'col-lg-3 col-md-6', 'col-lg-3 col-md-6'],
    '2-1-1' => ['col-lg-6 col-md-12', 'col-lg-3 col-md-6', 'col-lg-3 col-md-6'],
    '1-2-1' => ['col-lg-3 col-md-6', 'col-lg-6 col-md-12', 'col-lg-3 col-md-6'],
    '1-1-2' => ['col-lg-3 col-md-6', 'col-lg-3 col-md-6', 'col-lg-6 col-md-12']
];

$classes = $column_classes[$footer_layout] ?? $column_classes['4-columns'];
$widget_count = count($classes);
?>

<div class="footer-widgets bg-<?php echo esc_attr($footer_style); ?> text-<?php echo $footer_style === 'light' ? 'dark' : 'light'; ?> py-5">
    <div class="container">
        <div class="row">
            
            <?php for ($i = 1; $i <= $widget_count; $i++) : ?>
                <?php if (is_active_sidebar('footer-' . $i)) : ?>
                <div class="footer-widget-area <?php echo esc_attr($classes[$i - 1]); ?> mb-4 mb-lg-0">
                    <div class="widget-area">
                        <?php dynamic_sidebar('footer-' . $i); ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endfor; ?>

        </div>
    </div>
</div>

<?php if (get_theme_mod('show_back_to_top', true)) : ?>
<!-- Back to Top Button -->
<button type="button" class="btn btn-primary back-to-top" id="backToTop" aria-label="<?php esc_attr_e('Yukarı Çık', 'pratikwp'); ?>">
    <i class="fas fa-chevron-up"></i>
</button>
<?php endif; ?>