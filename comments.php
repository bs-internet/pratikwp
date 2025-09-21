<?php
/**
 * Comments template
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// If comments are closed and there are no comments, return
if (!comments_open() && get_comments_number() === 0) {
    return;
}
?>

<div id="comments" class="comments-area mt-5">

    <?php if (have_comments()) : ?>

    <h3 class="comments-title mb-4">
        <?php
            $comment_count = get_comments_number();
            printf(
                _n(
                    '%s Yorum',
                    '%s Yorum',
                    $comment_count,
                    'pratikwp'
                ),
                number_format_i18n($comment_count)
            );
            ?>
    </h3>

    <ol class="comment-list list-unstyled">
        <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'pratikwp_comment_callback',
            ]);
            ?>
    </ol>

    <?php
        // Comment pagination
        if (get_comment_pages_count() > 1 && get_option('page_comments')) :
        ?>
    <nav class="comment-navigation" aria-label="<?php esc_attr_e('Yorum sayfaları', 'pratikwp'); ?>">
        <div class="nav-links d-flex justify-content-between">
            <div class="nav-previous">
                <?php previous_comments_link(__('&larr; Önceki Yorumlar', 'pratikwp')); ?>
            </div>
            <div class="nav-next">
                <?php next_comments_link(__('Sonraki Yorumlar &rarr;', 'pratikwp')); ?>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
    <p class="no-comments alert alert-info">
        <?php esc_html_e('Yorumlar kapalı.', 'pratikwp'); ?>
    </p>
    <?php endif; ?>

    <?php
    // Comment form
    if (comments_open()) :
        $fields = [
            'author' => '<div class="row"><div class="col-md-4 mb-3"><input id="author" name="author" type="text" class="form-control" placeholder="' . esc_attr__('Adınız *', 'pratikwp') . '" value="' . esc_attr($commenter['comment_author']) . '" required /></div>',
            'email'  => '<div class="col-md-4 mb-3"><input id="email" name="email" type="email" class="form-control" placeholder="' . esc_attr__('E-posta *', 'pratikwp') . '" value="' . esc_attr($commenter['comment_author_email']) . '" required /></div>',
            'url'    => '<div class="col-md-4 mb-3"><input id="url" name="url" type="url" class="form-control" placeholder="' . esc_attr__('Web Sitesi', 'pratikwp') . '" value="' . esc_attr($commenter['comment_author_url']) . '" /></div></div>',
        ];

        comment_form([
            'title_reply'          => __('Yorum Yapın', 'pratikwp'),
            'title_reply_to'       => __('%s kişisine yanıt yazın', 'pratikwp'),
            'cancel_reply_link'    => __('Yanıtı iptal et', 'pratikwp'),
            'label_submit'         => __('Yorumu Gönder', 'pratikwp'),
            'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary" value="%4$s" />',
            'comment_field'        => '<div class="mb-3"><textarea id="comment" name="comment" class="form-control" rows="5" placeholder="' . esc_attr__('Yorumunuz...', 'pratikwp') . '" required></textarea></div>',
            'fields'               => $fields,
            'class_form'           => 'comment-form mt-4',
            'comment_notes_before' => '',
            'comment_notes_after'  => '',
        ]);
    endif;
    ?>

</div><!-- #comments -->