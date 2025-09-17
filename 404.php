<?php
/**
 * 404 template
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
    <main id="primary" class="site-main col-12">

        <section class="error-404 not-found text-center py-5">
            <header class="page-header mb-4">
                <h1 class="page-title display-1 text-muted">404</h1>
                <h2 class="mb-3"><?php esc_html_e('Sayfa Bulunamadı', 'pratikwp'); ?></h2>
                <p class="lead">
                    <?php esc_html_e('Aradığınız sayfa bulunamadı. Kaldırılmış veya adres değişmiş olabilir.', 'pratikwp'); ?>
                </p>
            </header><!-- .page-header -->

            <div class="page-content">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <?php get_search_form(); ?>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                    </a>
                </div>

                <?php
                // Popular posts or recent posts
                $recent_posts = wp_get_recent_posts([
                    'numberposts' => 3,
                    'post_status' => 'publish'
                ]);

                if (!empty($recent_posts)) :
                ?>
                <div class="mt-5">
                    <h3><?php esc_html_e('Son Yazılar', 'pratikwp'); ?></h3>
                    <div class="row">
                        <?php foreach ($recent_posts as $post) : ?>
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?php echo esc_url(get_permalink($post['ID'])); ?>">
                                            <?php echo esc_html($post['post_title']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text small text-muted">
                                        <?php echo esc_html(get_the_date('', $post['ID'])); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div><!-- .page-content -->
        </section><!-- .error-404 -->

    </main><!-- #primary -->
</div>

<?php
get_footer();