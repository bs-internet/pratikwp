<?php
/**
 * Template part for displaying post meta information
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Only show on posts
if (get_post_type() !== 'post') {
    return;
}

$meta_style = get_theme_mod('post_meta_style', 'default');
$meta_position = get_theme_mod('post_meta_position', 'above');
$show_author = get_theme_mod('show_post_author', true);
$show_date = get_theme_mod('show_post_date', true);
$show_categories = get_theme_mod('show_post_categories', true);
$show_tags = get_theme_mod('show_post_tags', false);
$show_comments = get_theme_mod('show_post_comments', true);
$show_reading_time = get_theme_mod('show_reading_time', false);
$show_view_count = get_theme_mod('show_view_count', false);
$show_edit_link = get_theme_mod('show_edit_link', true);

// Check if any meta should be displayed
if (!$show_author && !$show_date && !$show_categories && !$show_tags && !$show_comments && !$show_reading_time && !$show_view_count) {
    return;
}

$meta_classes = [
    'entry-meta',
    'post-meta-' . $meta_style,
    'post-meta-' . $meta_position
];
?>

<div class="<?php echo esc_attr(implode(' ', $meta_classes)); ?>">
    
    <?php if ($meta_style === 'card') : ?>
    <!-- Card Style -->
    <div class="post-meta-card border rounded p-3 bg-light">
        <div class="row align-items-center">
            
            <?php if ($show_author) : ?>
            <div class="col-auto">
                <div class="author-meta d-flex align-items-center">
                    <?php echo get_avatar(get_the_author_meta('ID'), 40, '', '', ['class' => 'rounded-circle me-2']); ?>
                    <div class="author-info">
                        <div class="author-name">
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-decoration-none fw-bold">
                                <?php echo esc_html(get_the_author()); ?>
                            </a>
                        </div>
                        <?php if (get_theme_mod('show_author_role', false)) : ?>
                        <div class="author-role small text-muted">
                            <?php
                            $author_id = get_the_author_meta('ID');
                            $user = get_userdata($author_id);
                            if ($user) {
                                $roles = $user->roles;
                                if (!empty($roles)) {
                                    $role = translate_user_role(wp_roles()->roles[$roles[0]]['name']);
                                    echo esc_html($role);
                                }
                            }
                            ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="col">
                <div class="post-meta-details d-flex flex-wrap align-items-center">
                    
                    <?php if ($show_date) : ?>
                    <div class="meta-item date-meta me-4 mb-1">
                        <i class="fas fa-calendar-alt me-1 text-primary"></i>
                        <span class="meta-label small text-muted me-1"><?php esc_html_e('Tarih:', 'pratikwp'); ?></span>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="meta-value">
                            <?php echo esc_html(get_the_date()); ?>
                        </time>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_categories) : ?>
                    <div class="meta-item category-meta me-4 mb-1">
                        <i class="fas fa-folder me-1 text-primary"></i>
                        <span class="meta-label small text-muted me-1"><?php esc_html_e('Kategori:', 'pratikwp'); ?></span>
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            $category_links = [];
                            foreach ($categories as $category) {
                                $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="text-decoration-none">' . esc_html($category->name) . '</a>';
                            }
                            echo '<span class="meta-value">' . implode(', ', $category_links) . '</span>';
                        }
                        ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_reading_time) : ?>
                    <div class="meta-item reading-time-meta me-4 mb-1">
                        <i class="fas fa-clock me-1 text-primary"></i>
                        <span class="meta-label small text-muted me-1"><?php esc_html_e('Okuma:', 'pratikwp'); ?></span>
                        <span class="meta-value">
                            <?php
                            $content = get_post_field('post_content', get_the_ID());
                            $word_count = str_word_count(strip_tags($content));
                            $reading_time = ceil($word_count / 200);
                            echo sprintf(_n('%s dk', '%s dk', $reading_time, 'pratikwp'), $reading_time);
                            ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php if ($show_comments && (comments_open() || get_comments_number())) : ?>
                    <div class="meta-item comments-meta mb-1">
                        <i class="fas fa-comments me-1 text-primary"></i>
                        <span class="meta-label small text-muted me-1"><?php esc_html_e('Yorum:', 'pratikwp'); ?></span>
                        <span class="meta-value">
                            <?php
                            comments_popup_link(
                                esc_html__('0', 'pratikwp'),
                                esc_html__('1', 'pratikwp'),
                                esc_html__('%', 'pratikwp'),
                                'text-decoration-none'
                            );
                            ?>
                        </span>
                    </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

    <?php elseif ($meta_style === 'inline') : ?>
    <!-- Inline Style -->
    <div class="post-meta-inline d-flex flex-wrap align-items-center small">
        
        <?php if ($show_author) : ?>
        <div class="meta-item author-meta me-3 mb-1">
            <i class="fas fa-user me-1"></i>
            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-decoration-none">
                <?php echo esc_html(get_the_author()); ?>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($show_date) : ?>
        <div class="meta-item date-meta me-3 mb-1">
            <i class="fas fa-calendar me-1"></i>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
        </div>
        <?php endif; ?>

        <?php if ($show_categories) : ?>
        <div class="meta-item category-meta me-3 mb-1">
            <i class="fas fa-folder me-1"></i>
            <?php
            $categories = get_the_category();
            if ($categories) {
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" class="text-decoration-none">' . esc_html($categories[0]->name) . '</a>';
            }
            ?>
        </div>
        <?php endif; ?>

        <?php if ($show_reading_time) : ?>
        <div class="meta-item reading-time-meta me-3 mb-1">
            <i class="fas fa-clock me-1"></i>
            <?php
            $content = get_post_field('post_content', get_the_ID());
            $word_count = str_word_count(strip_tags($content));
            $reading_time = ceil($word_count / 200);
            echo sprintf(_n('%s dakika', '%s dakika', $reading_time, 'pratikwp'), $reading_time);
            ?>
        </div>
        <?php endif; ?>

        <?php if ($show_comments && (comments_open() || get_comments_number())) : ?>
        <div class="meta-item comments-meta me-3 mb-1">
            <i class="fas fa-comments me-1"></i>
            <?php
            comments_popup_link(
                esc_html__('Yorum Yok', 'pratikwp'),
                esc_html__('1 Yorum', 'pratikwp'),
                esc_html__('% Yorum', 'pratikwp'),
                'text-decoration-none'
            );
            ?>
        </div>
        <?php endif; ?>

        <?php if ($show_view_count && function_exists('pvc_get_post_views')) : ?>
        <div class="meta-item view-count-meta me-3 mb-1">
            <i class="fas fa-eye me-1"></i>
            <?php echo sprintf(esc_html__('%s görüntüleme', 'pratikwp'), pvc_get_post_views()); ?>
        </div>
        <?php endif; ?>

    </div>

    <?php else : ?>
    <!-- Default Style -->
    <div class="post-meta-default">
        <div class="meta-items d-flex flex-wrap align-items-center">
            
            <?php if ($show_author) : ?>
            <div class="meta-item author-meta me-4 mb-2">
                <div class="d-flex align-items-center">
                    <?php echo get_avatar(get_the_author_meta('ID'), 32, '', '', ['class' => 'rounded-circle me-2']); ?>
                    <div>
                        <small class="text-muted d-block"><?php esc_html_e('Yazar', 'pratikwp'); ?></small>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="text-decoration-none fw-medium">
                            <?php echo esc_html(get_the_author()); ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_date) : ?>
            <div class="meta-item date-meta me-4 mb-2">
                <div>
                    <small class="text-muted d-block"><?php esc_html_e('Yayın Tarihi', 'pratikwp'); ?></small>
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="fw-medium">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_categories) : ?>
            <div class="meta-item category-meta me-4 mb-2">
                <div>
                    <small class="text-muted d-block"><?php esc_html_e('Kategori', 'pratikwp'); ?></small>
                    <div class="category-badges">
                        <?php
                        $categories = get_the_category();
                        if ($categories) {
                            foreach ($categories as $category) {
                                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="badge bg-primary text-decoration-none me-1">' . esc_html($category->name) . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_comments && (comments_open() || get_comments_number())) : ?>
            <div class="meta-item comments-meta me-4 mb-2">
                <div>
                    <small class="text-muted d-block"><?php esc_html_e('Yorumlar', 'pratikwp'); ?></small>
                    <div class="fw-medium">
                        <?php
                        comments_popup_link(
                            esc_html__('Henüz yorum yok', 'pratikwp'),
                            esc_html__('1 yorum', 'pratikwp'),
                            esc_html__('% yorum', 'pratikwp'),
                            'text-decoration-none'
                        );
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_reading_time) : ?>
            <div class="meta-item reading-time-meta mb-2">
                <div>
                    <small class="text-muted d-block"><?php esc_html_e('Okuma Süresi', 'pratikwp'); ?></small>
                    <div class="fw-medium">
                        <?php
                        $content = get_post_field('post_content', get_the_ID());
                        $word_count = str_word_count(strip_tags($content));
                        $reading_time = ceil($word_count / 200);
                        echo sprintf(_n('~%s dakika', '~%s dakika', $reading_time, 'pratikwp'), $reading_time);
                        ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <?php if ($show_tags && has_tag()) : ?>
        <div class="meta-tags mt-3 pt-3 border-top">
            <small class="text-muted d-block mb-2"><?php esc_html_e('Etiketler', 'pratikwp'); ?></small>
            <div class="tag-list">
                <?php
                $tags = get_the_tags();
                if ($tags) {
                    foreach ($tags as $tag) {
                        echo '<a href="' . esc_url(get_tag_link($tag->term_id)) . '" class="badge bg-light text-dark border me-1 mb-1 text-decoration-none">#' . esc_html($tag->name) . '</a>';
                    }
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php if ($show_edit_link && get_edit_post_link()) : ?>
    <div class="meta-edit-link mt-2">
        <?php
        edit_post_link(
            '<i class="fas fa-edit me-1"></i>' . esc_html__('Düzenle', 'pratikwp'),
            '<small class="edit-link">',
            '</small>',
            null,
            'btn btn-sm btn-outline-secondary text-decoration-none'
        );
        ?>
    </div>
    <?php endif; ?>

</div>