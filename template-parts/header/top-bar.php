<?php
/**
 * Template part for displaying top bar
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check if top bar should be displayed
$show_top_bar = get_theme_mod('show_top_bar', true);
if (!$show_top_bar) {
    return;
}

$top_bar_style = get_theme_mod('top_bar_style', 'dark');
$show_contact_info = get_theme_mod('top_bar_show_contact', true);
$show_social_links = get_theme_mod('top_bar_show_social', true);
$show_language_switcher = get_theme_mod('top_bar_show_language', false);
$show_search_icon = get_theme_mod('top_bar_show_search', false);
?>

<div class="top-bar bg-<?php echo esc_attr($top_bar_style); ?> text-<?php echo $top_bar_style === 'light' ? 'dark' : 'light'; ?> py-2">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Left Side - Contact Info -->
            <div class="col-md-8">
                <?php if ($show_contact_info) : ?>
                <div class="top-bar-left d-flex flex-wrap align-items-center">
                    
                    <?php 
                    $phone = get_theme_mod('contact_phone', '');
                    if ($phone) : ?>
                    <div class="contact-phone me-4 small">
                        <i class="fas fa-phone me-1"></i>
                        <a href="tel:<?php echo esc_attr(str_replace([' ', '-', '(', ')'], '', $phone)); ?>" class="text-decoration-none">
                            <?php echo esc_html($phone); ?>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php 
                    $email = get_theme_mod('contact_email', '');
                    if ($email) : ?>
                    <div class="contact-email me-4 small">
                        <i class="fas fa-envelope me-1"></i>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="text-decoration-none">
                            <?php echo esc_html($email); ?>
                        </a>
                    </div>
                    <?php endif; ?>

                    <?php 
                    $address = get_theme_mod('contact_address', '');
                    if ($address) : ?>
                    <div class="contact-address me-4 small d-none d-lg-block">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        <span><?php echo esc_html($address); ?></span>
                    </div>
                    <?php endif; ?>

                    <?php 
                    $working_hours = get_theme_mod('working_hours', '');
                    if ($working_hours) : ?>
                    <div class="working-hours small d-none d-xl-block">
                        <i class="fas fa-clock me-1"></i>
                        <span><?php echo esc_html($working_hours); ?></span>
                    </div>
                    <?php endif; ?>

                </div>
                <?php endif; ?>
            </div>

            <!-- Right Side - Social Links & Utils -->
            <div class="col-md-4">
                <div class="top-bar-right d-flex align-items-center justify-content-md-end">
                    
                    <?php if ($show_search_icon) : ?>
                    <div class="top-search me-3">
                        <button type="button" class="btn btn-link p-0 text-<?php echo $top_bar_style === 'light' ? 'dark' : 'light'; ?>" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="<?php esc_attr_e('Arama', 'pratikwp'); ?>">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_language_switcher && function_exists('pll_the_languages')) : ?>
                    <div class="language-switcher me-3">
                        <div class="dropdown">
                            <button class="btn btn-link p-0 text-<?php echo $top_bar_style === 'light' ? 'dark' : 'light'; ?> dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-globe me-1"></i>
                                <?php echo esc_html(pll_current_language('name')); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php
                                pll_the_languages([
                                    'show_names' => 1,
                                    'show_flags' => 1,
                                    'hide_current' => 0,
                                    'dropdown' => 1
                                ]);
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_social_links) : ?>
                    <div class="top-social-links">
                        <?php
                        $social_links = [
                            'facebook' => get_theme_mod('social_facebook', ''),
                            'twitter' => get_theme_mod('social_twitter', ''),
                            'instagram' => get_theme_mod('social_instagram', ''),
                            'linkedin' => get_theme_mod('social_linkedin', ''),
                            'youtube' => get_theme_mod('social_youtube', ''),
                            'whatsapp' => get_theme_mod('social_whatsapp', '')
                        ];

                        $social_icons = [
                            'facebook' => 'fab fa-facebook-f',
                            'twitter' => 'fab fa-twitter',
                            'instagram' => 'fab fa-instagram',
                            'linkedin' => 'fab fa-linkedin-in',
                            'youtube' => 'fab fa-youtube',
                            'whatsapp' => 'fab fa-whatsapp'
                        ];

                        foreach ($social_links as $platform => $url) :
                            if ($url) :
                                $target = ($platform === 'whatsapp') ? '_self' : '_blank';
                                $href = ($platform === 'whatsapp') ? 'https://wa.me/' . $url : $url;
                        ?>
                        <a href="<?php echo esc_url($href); ?>" 
                           target="<?php echo esc_attr($target); ?>"
                           class="social-link me-2 text-<?php echo $top_bar_style === 'light' ? 'dark' : 'light'; ?>"
                           aria-label="<?php echo esc_attr(ucfirst($platform)); ?>"
                           <?php if ($target === '_blank') echo 'rel="noopener noreferrer"'; ?>>
                            <i class="<?php echo esc_attr($social_icons[$platform]); ?>"></i>
                        </a>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php if ($show_search_icon) : ?>
<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="searchModalLabel"><?php esc_html_e('Arama', 'pratikwp'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Kapat', 'pratikwp'); ?>"></button>
            </div>
            <div class="modal-body">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="input-group">
                        <input type="search" 
                               class="form-control form-control-lg" 
                               placeholder="<?php echo esc_attr_x('Arama...', 'placeholder', 'pratikwp'); ?>" 
                               value="<?php echo get_search_query(); ?>" 
                               name="s" 
                               autocomplete="off">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>