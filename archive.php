<?php
/**
 * Archive Template - Tek Basit Sürüm
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

        <!-- Archive Header -->
        <header class="page-header">
            <h1 class="page-title">
                <?php
                if (is_category()) {
                    printf(__('Kategori: %s', 'pratikwp'), '<span>' . single_cat_title('', false) . '</span>');
                } elseif (is_tag()) {
                    printf(__('Etiket: %s', 'pratikwp'), '<span>' . single_tag_title('', false) . '</span>');
                } elseif (is_author()) {
                    printf(__('Yazar: %s', 'pratikwp'), '<span>' . get_the_author() . '</span>');
                } elseif (is_date()) {
                    if (is_year()) {
                        printf(__('Yıl: %s', 'pratikwp'), '<span>' . get_the_date('Y') . '</span>');
                    } elseif (is_month()) {
                        printf(__('Ay: %s', 'pratikwp'), '<span>' . get_the_date('F Y') . '</span>');
                    } elseif (is_day()) {
                        printf(__('Gün: %s', 'pratikwp'), '<span>' . get_the_date() . '</span>');
                    }
                } else {
                    esc_html_e('Arşiv', 'pratikwp');
                }
                ?>
            </h1>
            
            <?php
            $description = get_the_archive_description();
            if ($description) :
            ?>
                <div class="archive-description"><?php echo wp_kses_post($description); ?></div>
            <?php endif; ?>
        </header>

        <!-- Archive Posts -->
        <div class="archive-posts">
            <?php
            while (have_posts()) :
                the_post();
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('archive-post'); ?>>
                
                <!-- Post Thumbnail -->
                <?php if (has_post_thumbnail()) : ?>
                <div class="post-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail('medium', ['class' => 'img-fluid']); ?>
                    </a>
                </div>
                <?php endif; ?>
                
                <!-- Post Content -->
                <div class="post-content">
                    
                    <!-- Post Title -->
                    <h2 class="entry-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h2>
                    
                    <!-- Post Meta -->
                    <div class="entry-meta">
                        <span class="posted-on">
                            <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </span>
                        
                        <span class="byline">
                            <?php esc_html_e('Yazar:', 'pratikwp'); ?>
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                <?php the_author(); ?>
                            </a>
                        </span>
                        
                        <?php if (comments_open() || get_comments_number()) : ?>
                        <span class="comments-link">
                            <a href="<?php comments_link(); ?>">
                                <?php
                                $comments_number = get_comments_number();
                                if ($comments_number == 0) {
                                    esc_html_e('Yorum Yok', 'pratikwp');
                                } elseif ($comments_number == 1) {
                                    esc_html_e('1 Yorum', 'pratikwp');
                                } else {
                                    printf(__('%s Yorum', 'pratikwp'), $comments_number);
                                }
                                ?>
                            </a>
                        </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Post Excerpt -->
                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <!-- Read More -->
                    <div class="read-more">
                        <a href="<?php the_permalink(); ?>" class="btn btn-outline">
                            <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
                        </a>
                    </div>
                    
                </div>
                
            </article>
            
            <?php
            endwhile;
            ?>
        </div>

        <!-- Pagination -->
        <?php pratikwp_posts_pagination(); ?>

        <?php else : ?>

            <!-- No Posts Found -->
            <div class="no-posts-found">
                <h2><?php esc_html_e('İçerik Bulunamadı', 'pratikwp'); ?></h2>
                <p><?php esc_html_e('Bu kategoride henüz yayınlanmış içerik bulunmuyor.', 'pratikwp'); ?></p>
                
                <!-- Search Form -->
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>
                
                <!-- Back to Home -->
                <div class="back-to-home">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn">
                        <?php esc_html_e('Ana Sayfaya Dön', 'pratikwp'); ?>
                    </a>
                </div>
            </div>

        <?php endif; ?>

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