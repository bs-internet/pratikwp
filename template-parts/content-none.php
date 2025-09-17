<?php
/**
 * Template part for displaying a message that posts cannot be found
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="no-results not-found text-center py-5">
    
    <div class="page-content">
        
        <?php if (is_home() && current_user_can('publish_posts')) : ?>

            <header class="page-header mb-4">
                <h1 class="page-title"><?php esc_html_e('Henüz Yayınlanmış İçerik Yok', 'pratikwp'); ?></h1>
            </header>

            <div class="no-content-message">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-file-alt fa-4x text-muted"></i>
                </div>
                
                <p class="lead mb-4">
                    <?php esc_html_e('Henüz yayınlanmış bir yazı bulunmuyor. İlk yazınızı yazmaya hazır mısınız?', 'pratikwp'); ?>
                </p>
                
                <div class="action-buttons">
                    <a href="<?php echo esc_url(admin_url('post-new.php')); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus me-2"></i>
                        <?php esc_html_e('İlk Yazınızı Yazın', 'pratikwp'); ?>
                    </a>
                </div>
            </div>

        <?php elseif (is_search()) : ?>

            <header class="page-header mb-4">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('"%s" için arama sonucu bulunamadı', 'pratikwp'),
                        '<span class="search-term">' . get_search_query() . '</span>'
                    );
                    ?>
                </h1>
            </header>

            <div class="no-search-results">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-search fa-4x text-muted"></i>
                </div>
                
                <p class="lead mb-4">
                    <?php esc_html_e('Aradığınız terimle eşleşen sonuç bulunamadı. Farklı kelimeler deneyebilirsiniz.', 'pratikwp'); ?>
                </p>

                <div class="search-suggestions">
                    <h4 class="mb-3"><?php esc_html_e('Arama Önerileri:', 'pratikwp'); ?></h4>
                    <ul class="list-unstyled text-start d-inline-block">
                        <li class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i><?php esc_html_e('Daha genel terimler kullanın', 'pratikwp'); ?></li>
                        <li class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i><?php esc_html_e('Yazım hatalarını kontrol edin', 'pratikwp'); ?></li>
                        <li class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i><?php esc_html_e('Eş anlamlı kelimeler deneyin', 'pratikwp'); ?></li>
                        <li class="mb-2"><i class="fas fa-lightbulb me-2 text-warning"></i><?php esc_html_e('Daha az kelime kullanın', 'pratikwp'); ?></li>
                    </ul>
                </div>

                <div class="search-form-wrapper mt-4">
                    <h5 class="mb-3"><?php esc_html_e('Tekrar Arayın:', 'pratikwp'); ?></h5>
                    <?php get_search_form(); ?>
                </div>

                <?php
                // Show popular/recent posts as alternatives
                $recent_posts = get_posts([
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ]);
                
                if ($recent_posts) : ?>
                <div class="alternative-content mt-5">
                    <h5 class="mb-3"><?php esc_html_e('Son Yazılar:', 'pratikwp'); ?></h5>
                    <div class="row">
                        <?php foreach ($recent_posts as $post) : setup_postdata($post); ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100">
                                <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                </a>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h6>
                                    <p class="card-text small text-muted">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                </div>
                <?php endif; ?>

            </div>

        <?php elseif (is_category()) : ?>

            <header class="page-header mb-4">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('"%s" kategorisinde henüz içerik yok', 'pratikwp'),
                        single_cat_title('', false)
                    );
                    ?>
                </h1>
            </header>

            <div class="no-category-content">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-folder-open fa-4x text-muted"></i>
                </div>
                
                <p class="lead mb-4">
                    <?php esc_html_e('Bu kategoride henüz yayınlanmış içerik bulunmuyor.', 'pratikwp'); ?>
                </p>

                <div class="action-buttons">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                    </a>
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-list me-2"></i>
                        <?php esc_html_e('Tüm Yazılar', 'pratikwp'); ?>
                    </a>
                </div>
            </div>

        <?php elseif (is_tag()) : ?>

            <header class="page-header mb-4">
                <h1 class="page-title">
                    <?php
                    printf(
                        esc_html__('"%s" etiketinde henüz içerik yok', 'pratikwp'),
                        single_tag_title('', false)
                    );
                    ?>
                </h1>
            </header>

            <div class="no-tag-content">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-tags fa-4x text-muted"></i>
                </div>
                
                <p class="lead mb-4">
                    <?php esc_html_e('Bu etikete sahip henüz yayınlanmış içerik bulunmuyor.', 'pratikwp'); ?>
                </p>

                <div class="action-buttons">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                    </a>
                </div>
            </div>

        <?php else : ?>

            <header class="page-header mb-4">
                <h1 class="page-title"><?php esc_html_e('İçerik Bulunamadı', 'pratikwp'); ?></h1>
            </header>

            <div class="no-general-content">
                <div class="empty-state-icon mb-4">
                    <i class="fas fa-exclamation-triangle fa-4x text-muted"></i>
                </div>
                
                <p class="lead mb-4">
                    <?php esc_html_e('Aradığınız sayfa veya içerik bulunamadı. Ana sayfaya dönebilir veya arama yapabilirsiniz.', 'pratikwp'); ?>
                </p>

                <div class="search-form-wrapper mb-4">
                    <?php get_search_form(); ?>
                </div>

                <div class="action-buttons">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <i class="fas fa-home me-2"></i>
                        <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
    
</section>