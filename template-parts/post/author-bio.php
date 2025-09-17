<?php
/**
 * Template part for displaying author bio
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only show on single posts
if (!is_single()) {
    return;
}

// Check if author bio should be displayed
$show_author_bio = get_theme_mod('show_author_bio', true);
if (!$show_author_bio) {
    return;
}

$author_id = get_the_author_meta('ID');
$author_description = get_the_author_meta('description', $author_id);

// Don't show if no description
if (empty($author_description)) {
    return;
}

$bio_style = get_theme_mod('author_bio_style', 'card');
$show_author_posts_count = get_theme_mod('show_author_posts_count', true);
$show_author_social = get_theme_mod('show_author_social', true);
$show_author_website = get_theme_mod('show_author_website', true);
$show_all_posts_link = get_theme_mod('show_author_all_posts_link', true);

// Author data
$author_name = get_the_author_meta('display_name', $author_id);
$author_email = get_the_author_meta('user_email', $author_id);
$author_website = get_the_author_meta('url', $author_id);
$author_posts_url = get_author_posts_url($author_id);
$author_posts_count = count_user_posts($author_id);
$author_avatar = get_avatar($author_id, 80, '', '', ['class' => 'rounded-circle']);

// Social media links (custom fields)
$author_facebook = get_the_author_meta('facebook', $author_id);
$author_twitter = get_the_author_meta('twitter', $author_id);
$author_linkedin = get_the_author_meta('linkedin', $author_id);
$author_instagram = get_the_author_meta('instagram', $author_id);
$author_youtube = get_the_author_meta('youtube', $author_id);

$bio_classes = [
    'author-bio',
    'author-bio-' . $bio_style,
    'my-5'
];
?>

<section class="<?php echo esc_attr(implode(' ', $bio_classes)); ?>">
    
    <?php if ($bio_style === 'card') : ?>
    <!-- Card Style -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="row align-items-center">
                
                <div class="col-auto">
                    <div class="author-avatar">
                        <?php echo $author_avatar; ?>
                    </div>
                </div>
                
                <div class="col">
                    <div class="author-info">
                        
                        <div class="author-header mb-3">
                            <h4 class="author-name mb-1">
                                <a href="<?php echo esc_url($author_posts_url); ?>" class="text-decoration-none">
                                    <?php echo esc_html($author_name); ?>
                                </a>
                            </h4>
                            
                            <?php if ($show_author_posts_count) : ?>
                            <div class="author-stats small text-muted">
                                <i class="fas fa-edit me-1"></i>
                                <?php echo sprintf(_n('%s yazı', '%s yazı', $author_posts_count, 'pratikwp'), number_format_i18n($author_posts_count)); ?>
                                
                                <?php
                                // Registration date
                                $user_registered = get_the_author_meta('user_registered', $author_id);
                                if ($user_registered) {
                                    $registration_date = date_i18n(get_option('date_format'), strtotime($user_registered));
                                    echo ' • ' . sprintf(esc_html__('%s tarihinden beri', 'pratikwp'), $registration_date);
                                }
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="author-description mb-3">
                            <p class="mb-0"><?php echo wp_kses_post(wpautop($author_description)); ?></p>
                        </div>
                        
                        <div class="author-links d-flex flex-wrap align-items-center">
                            
                            <?php if ($show_all_posts_link) : ?>
                            <a href="<?php echo esc_url($author_posts_url); ?>" class="btn btn-primary btn-sm me-3 mb-2">
                                <i class="fas fa-user me-1"></i>
                                <?php esc_html_e('Tüm Yazıları', 'pratikwp'); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($show_author_website && $author_website) : ?>
                            <a href="<?php echo esc_url($author_website); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm me-3 mb-2">
                                <i class="fas fa-globe me-1"></i>
                                <?php esc_html_e('Website', 'pratikwp'); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($show_author_social) : ?>
                            <div class="author-social me-3 mb-2">
                                <?php if ($author_facebook) : ?>
                                <a href="<?php echo esc_url($author_facebook); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none me-2" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($author_twitter) : ?>
                                <a href="<?php echo esc_url($author_twitter); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none me-2" title="Twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($author_linkedin) : ?>
                                <a href="<?php echo esc_url($author_linkedin); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none me-2" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($author_instagram) : ?>
                                <a href="<?php echo esc_url($author_instagram); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none me-2" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <?php endif; ?>
                                
                                <?php if ($author_youtube) : ?>
                                <a href="<?php echo esc_url($author_youtube); ?>" target="_blank" rel="noopener noreferrer" class="text-decoration-none me-2" title="YouTube">
                                    <i class="fab fa-youtube"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <?php elseif ($bio_style === 'minimal') : ?>
    <!-- Minimal Style -->
    <div class="author-bio-minimal border-top border-bottom py-4">
        <div class="row align-items-center">
            
            <div class="col-auto">
                <div class="author-avatar">
                    <?php echo get_avatar($author_id, 60, '', '', ['class' => 'rounded-circle']); ?>
                </div>
            </div>
            
            <div class="col">
                <div class="author-content">
                    <h5 class="author-name mb-1">
                        <a href="<?php echo esc_url($author_posts_url); ?>" class="text-decoration-none">
                            <?php echo esc_html($author_name); ?>
                        </a>
                    </h5>
                    <p class="author-description mb-2 text-muted">
                        <?php echo esc_html(wp_trim_words($author_description, 20, '...')); ?>
                    </p>
                    <div class="author-meta small">
                        <a href="<?php echo esc_url($author_posts_url); ?>" class="text-decoration-none">
                            <?php echo sprintf(_n('%s yazı', '%s yazı', $author_posts_count, 'pratikwp'), $author_posts_count); ?>
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <?php else : ?>
    <!-- Default Style -->
    <div class="author-bio-default bg-light rounded p-4">
        
        <div class="author-bio-header text-center mb-4">
            <h4 class="author-bio-title">
                <i class="fas fa-user-edit me-2"></i>
                <?php esc_html_e('Yazar Hakkında', 'pratikwp'); ?>
            </h4>
        </div>
        
        <div class="author-bio-content">
            <div class="row align-items-start">
                
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <div class="author-profile">
                        <div class="author-avatar mb-3">
                            <?php echo get_avatar($author_id, 120, '', '', ['class' => 'rounded-circle border']); ?>
                        </div>
                        
                        <h5 class="author-name mb-2">
                            <?php echo esc_html($author_name); ?>
                        </h5>
                        
                        <?php if ($show_author_posts_count) : ?>
                        <div class="author-post-count badge bg-primary mb-3">
                            <?php echo sprintf(_n('%s Yazı', '%s Yazı', $author_posts_count, 'pratikwp'), $author_posts_count); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($show_author_social && ($author_facebook || $author_twitter || $author_linkedin || $author_instagram || $author_youtube)) : ?>
                        <div class="author-social-links mb-3">
                            <?php if ($author_facebook) : ?>
                            <a href="<?php echo esc_url($author_facebook); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($author_twitter) : ?>
                            <a href="<?php echo esc_url($author_twitter); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-info rounded-circle me-1" title="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($author_linkedin) : ?>
                            <a href="<?php echo esc_url($author_linkedin); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($author_instagram) : ?>
                            <a href="<?php echo esc_url($author_instagram); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-circle me-1" title="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($author_youtube) : ?>
                            <a href="<?php echo esc_url($author_youtube); ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger rounded-circle me-1" title="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($show_all_posts_link) : ?>
                        <a href="<?php echo esc_url($author_posts_url); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-list me-1"></i>
                            <?php esc_html_e('Tüm Yazıları Gör', 'pratikwp'); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-9">
                    <div class="author-description">
                        <?php echo wp_kses_post(wpautop($author_description)); ?>
                    </div>
                    
                    <?php if ($show_author_website && $author_website) : ?>
                    <div class="author-website mt-3">
                        <strong><?php esc_html_e('Website:', 'pratikwp'); ?></strong>
                        <a href="<?php echo esc_url($author_website); ?>" target="_blank" rel="noopener noreferrer" class="ms-2">
                            <?php echo esc_html($author_website); ?>
                            <i class="fas fa-external-link-alt ms-1 small"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                    
                    <?php
                    // Latest posts by author
                    if (get_theme_mod('show_author_latest_posts', false)) {
                        $latest_posts = get_posts([
                            'author' => $author_id,
                            'numberposts' => 3,
                            'post__not_in' => [get_the_ID()],
                            'post_status' => 'publish'
                        ]);
                        
                        if ($latest_posts) :
                    ?>
                    <div class="author-latest-posts mt-4">
                        <h6><?php esc_html_e('Son Yazıları:', 'pratikwp'); ?></h6>
                        <ul class="list-unstyled">
                            <?php foreach ($latest_posts as $post) : ?>
                            <li class="mb-2">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="text-decoration-none">
                                    <i class="fas fa-arrow-right me-2 small"></i>
                                    <?php echo esc_html(get_the_title($post->ID)); ?>
                                </a>
                                <small class="text-muted ms-2"><?php echo esc_html(get_the_date('', $post->ID)); ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php 
                        endif;
                        wp_reset_postdata();
                    }
                    ?>
                    
                </div>
                
            </div>
        </div>
    </div>
    <?php endif; ?>

</section>