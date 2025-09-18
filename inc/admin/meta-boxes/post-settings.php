<?php
/**
 * Post Settings Meta Box
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Post Settings Meta Box handler
 */
class PratikWp_Post_Settings {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        $post_types = apply_filters('pratikwp_post_settings_post_types', ['post']);
        
        foreach ($post_types as $post_type) {
            add_meta_box(
                'pratikwp_post_settings',
                __('PratikWp Yazı Ayarları', 'pratikwp'),
                [$this, 'render_meta_box'],
                $post_type,
                'side',
                'high'
            );
        }
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts($hook) {
        if (!in_array($hook, ['post.php', 'post-new.php'])) {
            return;
        }

        global $post;
        if (!$post || !in_array($post->post_type, ['post'])) {
            return;
        }

        wp_enqueue_style(
            'pratikwp-post-settings',
            get_template_directory_uri() . '/assets/css/admin.css',
            [],
            PRATIKWP_VERSION
        );

        wp_enqueue_script(
            'pratikwp-post-settings',
            get_template_directory_uri() . '/assets/js/admin.js',
            ['jquery', 'wp-color-picker'],
            PRATIKWP_VERSION,
            true
        );

        wp_enqueue_style('wp-color-picker');
    }

    /**
     * Render meta box
     */
    public function render_meta_box($post) {
        // Add nonce field
        wp_nonce_field('pratikwp_post_settings_nonce', 'pratikwp_post_settings_nonce');

        // Get current values
        $post_layout = get_post_meta($post->ID, '_pratikwp_post_layout', true);
        $sidebar_position = get_post_meta($post->ID, '_pratikwp_sidebar_position', true);
        $hide_featured_image = get_post_meta($post->ID, '_pratikwp_hide_featured_image', true);
        $hide_post_meta = get_post_meta($post->ID, '_pratikwp_hide_post_meta', true);
        $hide_author_box = get_post_meta($post->ID, '_pratikwp_hide_author_box', true);
        $hide_related_posts = get_post_meta($post->ID, '_pratikwp_hide_related_posts', true);
        $custom_read_more = get_post_meta($post->ID, '_pratikwp_custom_read_more', true);
        $featured_video = get_post_meta($post->ID, '_pratikwp_featured_video', true);
        $gallery_images = get_post_meta($post->ID, '_pratikwp_gallery_images', true);
        $post_format_settings = get_post_meta($post->ID, '_pratikwp_post_format_settings', true);
        $estimated_reading_time = get_post_meta($post->ID, '_pratikwp_estimated_reading_time', true);

        ?>
        <div class="pratikwp-meta-box">
            <!-- Post Layout -->
            <div class="meta-box-section">
                <label for="pratikwp_post_layout" class="meta-label">
                    <strong><?php esc_html_e('Yazı Düzeni', 'pratikwp'); ?></strong>
                </label>
                <select name="pratikwp_post_layout" id="pratikwp_post_layout" class="widefat">
                    <option value=""><?php esc_html_e('Varsayılan (Tema Ayarından)', 'pratikwp'); ?></option>
                    <option value="full-width" <?php selected($post_layout, 'full-width'); ?>><?php esc_html_e('Tam Genişlik', 'pratikwp'); ?></option>
                    <option value="contained" <?php selected($post_layout, 'contained'); ?>><?php esc_html_e('Container İçinde', 'pratikwp'); ?></option>
                    <option value="minimal" <?php selected($post_layout, 'minimal'); ?>><?php esc_html_e('Minimal Görünüm', 'pratikwp'); ?></option>
                    <option value="magazine" <?php selected($post_layout, 'magazine'); ?>><?php esc_html_e('Magazin Stili', 'pratikwp'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Bu yazının görüntülenme düzenini belirler', 'pratikwp'); ?></p>
            </div>

            <!-- Sidebar Position -->
            <div class="meta-box-section">
                <label for="pratikwp_sidebar_position" class="meta-label">
                    <strong><?php esc_html_e('Sidebar Konumu', 'pratikwp'); ?></strong>
                </label>
                <select name="pratikwp_sidebar_position" id="pratikwp_sidebar_position" class="widefat">
                    <option value=""><?php esc_html_e('Varsayılan (Tema Ayarından)', 'pratikwp'); ?></option>
                    <option value="left" <?php selected($sidebar_position, 'left'); ?>><?php esc_html_e('Sol Taraf', 'pratikwp'); ?></option>
                    <option value="right" <?php selected($sidebar_position, 'right'); ?>><?php esc_html_e('Sağ Taraf', 'pratikwp'); ?></option>
                    <option value="none" <?php selected($sidebar_position, 'none'); ?>><?php esc_html_e('Sidebar Yok', 'pratikwp'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Yazı görüntülenirken sidebar pozisyonu', 'pratikwp'); ?></p>
            </div>

            <!-- Display Options -->
            <div class="meta-box-section">
                <label class="meta-label">
                    <strong><?php esc_html_e('Görüntüleme Seçenekleri', 'pratikwp'); ?></strong>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_featured_image" value="1" <?php checked($hide_featured_image, '1'); ?> />
                    <?php esc_html_e('Öne Çıkan Görseli Gizle', 'pratikwp'); ?>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_post_meta" value="1" <?php checked($hide_post_meta, '1'); ?> />
                    <?php esc_html_e('Yazı Meta Bilgilerini Gizle', 'pratikwp'); ?>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_author_box" value="1" <?php checked($hide_author_box, '1'); ?> />
                    <?php esc_html_e('Yazar Kutusu Gizle', 'pratikwp'); ?>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_related_posts" value="1" <?php checked($hide_related_posts, '1'); ?> />
                    <?php esc_html_e('İlgili Yazıları Gizle', 'pratikwp'); ?>
                </label>
                
                <p class="description"><?php esc_html_e('Yazı sayfasında gizlenecek elemanları seçin', 'pratikwp'); ?></p>
            </div>

            <!-- Custom Read More -->
            <div class="meta-box-section">
                <label for="pratikwp_custom_read_more" class="meta-label">
                    <strong><?php esc_html_e('Özel "Devamını Oku" Metni', 'pratikwp'); ?></strong>
                </label>
                <input type="text" name="pratikwp_custom_read_more" id="pratikwp_custom_read_more" 
                       value="<?php echo esc_attr($custom_read_more); ?>" class="widefat" 
                       placeholder="<?php esc_attr_e('Detayları gör...', 'pratikwp'); ?>" />
                <p class="description"><?php esc_html_e('Blog listelerinde görünecek özel "devamını oku" metni', 'pratikwp'); ?></p>
            </div>

            <!-- Post Format Settings -->
            <div class="meta-box-section">
                <label class="meta-label">
                    <strong><?php esc_html_e('Post Format Ayarları', 'pratikwp'); ?></strong>
                </label>

                <!-- Video Post Format -->
                <div class="post-format-option" data-format="video">
                    <h4><?php esc_html_e('Video Ayarları', 'pratikwp'); ?></h4>
                    <label for="pratikwp_featured_video"><?php esc_html_e('Video URL:', 'pratikwp'); ?></label>
                    <input type="url" name="pratikwp_featured_video" id="pratikwp_featured_video" 
                           value="<?php echo esc_url($featured_video); ?>" class="widefat" 
                           placeholder="https://youtube.com/watch?v=..." />
                    <p class="description"><?php esc_html_e('YouTube, Vimeo veya doğrudan video dosya URL\'si', 'pratikwp'); ?></p>
                </div>

                <!-- Gallery Post Format -->
                <div class="post-format-option" data-format="gallery">
                    <h4><?php esc_html_e('Galeri Ayarları', 'pratikwp'); ?></h4>
                    <div class="gallery-images-wrapper">
                        <input type="hidden" name="pratikwp_gallery_images" id="pratikwp_gallery_images" 
                               value="<?php echo esc_attr($gallery_images); ?>" />
                        <button type="button" class="button button-secondary" id="select-gallery-images">
                            <?php esc_html_e('Galeri Görsellerini Seç', 'pratikwp'); ?>
                        </button>
                        <div class="gallery-preview" id="gallery-preview">
                            <?php if ($gallery_images): 
                                $image_ids = explode(',', $gallery_images);
                                foreach ($image_ids as $image_id):
                                    if ($image_id):
                            ?>
                                <div class="gallery-thumb" data-id="<?php echo esc_attr($image_id); ?>">
                                    <img src="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'thumbnail')); ?>" alt="" />
                                    <button type="button" class="remove-gallery-image">&times;</button>
                                </div>
                            <?php 
                                    endif;
                                endforeach;
                            endif; 
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Quote Post Format -->
                <div class="post-format-option" data-format="quote">
                    <h4><?php esc_html_e('Alıntı Ayarları', 'pratikwp'); ?></h4>
                    <label for="pratikwp_quote_text"><?php esc_html_e('Alıntı Metni:', 'pratikwp'); ?></label>
                    <textarea name="pratikwp_quote_text" id="pratikwp_quote_text" rows="3" class="widefat"
                              placeholder="<?php esc_attr_e('Alıntı metnini buraya yazın...', 'pratikwp'); ?>"><?php echo esc_textarea($post_format_settings['quote_text'] ?? ''); ?></textarea>
                    
                    <label for="pratikwp_quote_author"><?php esc_html_e('Alıntı Yazarı:', 'pratikwp'); ?></label>
                    <input type="text" name="pratikwp_quote_author" id="pratikwp_quote_author" 
                           value="<?php echo esc_attr($post_format_settings['quote_author'] ?? ''); ?>" class="widefat" 
                           placeholder="<?php esc_attr_e('Yazar adı', 'pratikwp'); ?>" />
                </div>

                <!-- Link Post Format -->
                <div class="post-format-option" data-format="link">
                    <h4><?php esc_html_e('Link Ayarları', 'pratikwp'); ?></h4>
                    <label for="pratikwp_link_url"><?php esc_html_e('Link URL:', 'pratikwp'); ?></label>
                    <input type="url" name="pratikwp_link_url" id="pratikwp_link_url" 
                           value="<?php echo esc_url($post_format_settings['link_url'] ?? ''); ?>" class="widefat" 
                           placeholder="https://example.com" />
                    
                    <label class="checkbox-label">
                        <input type="checkbox" name="pratikwp_link_new_tab" value="1" 
                               <?php checked($post_format_settings['link_new_tab'] ?? '', '1'); ?> />
                        <?php esc_html_e('Yeni sekmede aç', 'pratikwp'); ?>
                    </label>
                </div>
            </div>

            <!-- Reading Time -->
            <div class="meta-box-section">
                <label for="pratikwp_estimated_reading_time" class="meta-label">
                    <strong><?php esc_html_e('Tahmini Okuma Süresi', 'pratikwp'); ?></strong>
                </label>
                <div class="reading-time-wrapper">
                    <input type="number" name="pratikwp_estimated_reading_time" id="pratikwp_estimated_reading_time" 
                           value="<?php echo esc_attr($estimated_reading_time); ?>" class="small-text" min="1" max="60" />
                    <span><?php esc_html_e('dakika', 'pratikwp'); ?></span>
                    <button type="button" class="button button-secondary" id="calculate-reading-time">
                        <?php esc_html_e('Otomatik Hesapla', 'pratikwp'); ?>
                    </button>
                </div>
                <p class="description"><?php esc_html_e('Boş bırakılırsa otomatik hesaplanır (ortalama 200 kelime/dakika)', 'pratikwp'); ?></p>
            </div>

            <!-- SEO Settings -->
            <div class="meta-box-section">
                <h4 class="section-title"><?php esc_html_e('SEO Ayarları', 'pratikwp'); ?></h4>
                
                <label for="pratikwp_meta_description" class="meta-label">
                    <strong><?php esc_html_e('Meta Açıklama', 'pratikwp'); ?></strong>
                </label>
                <textarea name="pratikwp_meta_description" id="pratikwp_meta_description" 
                          rows="3" class="widefat" maxlength="160"
                          placeholder="<?php esc_attr_e('Bu yazı için özel meta açıklama...', 'pratikwp'); ?>"><?php echo esc_textarea(get_post_meta($post->ID, '_pratikwp_meta_description', true)); ?></textarea>
                <div class="character-counter">
                    <span id="meta-description-count">0</span> / 160 <?php esc_html_e('karakter', 'pratikwp'); ?>
                </div>
                
                <label for="pratikwp_focus_keyword" class="meta-label">
                    <strong><?php esc_html_e('Odak Anahtar Kelime', 'pratikwp'); ?></strong>
                </label>
                <input type="text" name="pratikwp_focus_keyword" id="pratikwp_focus_keyword" 
                       value="<?php echo esc_attr(get_post_meta($post->ID, '_pratikwp_focus_keyword', true)); ?>" 
                       class="widefat" placeholder="<?php esc_attr_e('ana anahtar kelime', 'pratikwp'); ?>" />
                <p class="description"><?php esc_html_e('Bu yazının odaklandığı ana anahtar kelime', 'pratikwp'); ?></p>
            </div>

            <!-- Social Media -->
            <div class="meta-box-section">
                <h4 class="section-title"><?php esc_html_e('Sosyal Medya', 'pratikwp'); ?></h4>
                
                <label for="pratikwp_social_title" class="meta-label">
                    <strong><?php esc_html_e('Sosyal Medya Başlığı', 'pratikwp'); ?></strong>
                </label>
                <input type="text" name="pratikwp_social_title" id="pratikwp_social_title" 
                       value="<?php echo esc_attr(get_post_meta($post->ID, '_pratikwp_social_title', true)); ?>" 
                       class="widefat" placeholder="<?php esc_attr_e('Paylaşımlarda görünecek başlık', 'pratikwp'); ?>" />
                
                <label for="pratikwp_social_description" class="meta-label">
                    <strong><?php esc_html_e('Sosyal Medya Açıklama', 'pratikwp'); ?></strong>
                </label>
                <textarea name="pratikwp_social_description" id="pratikwp_social_description" 
                          rows="2" class="widefat" maxlength="200"
                          placeholder="<?php esc_attr_e('Sosyal medyada paylaşıldığında görünecek açıklama...', 'pratikwp'); ?>"><?php echo esc_textarea(get_post_meta($post->ID, '_pratikwp_social_description', true)); ?></textarea>
                
                <label for="pratikwp_social_image" class="meta-label">
                    <strong><?php esc_html_e('Sosyal Medya Görseli', 'pratikwp'); ?></strong>
                </label>
                <div class="image-upload-wrapper">
                    <input type="hidden" name="pratikwp_social_image" id="pratikwp_social_image" 
                           value="<?php echo esc_attr(get_post_meta($post->ID, '_pratikwp_social_image', true)); ?>" />
                    <button type="button" class="button button-secondary upload-image-button">
                        <?php esc_html_e('Görsel Seç', 'pratikwp'); ?>
                    </button>
                    <button type="button" class="button button-link-delete remove-image-button" 
                            <?php echo empty(get_post_meta($post->ID, '_pratikwp_social_image', true)) ? 'style="display:none;"' : ''; ?>>
                        <?php esc_html_e('Kaldır', 'pratikwp'); ?>
                    </button>
                    <div class="image-preview" 
                         <?php echo empty(get_post_meta($post->ID, '_pratikwp_social_image', true)) ? 'style="display:none;"' : ''; ?>>
                        <?php 
                        $social_image = get_post_meta($post->ID, '_pratikwp_social_image', true);
                        if ($social_image): 
                        ?>
                            <img src="<?php echo esc_url(wp_get_attachment_image_url($social_image, 'medium')); ?>" alt="" />
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Character counter for meta description
            $('#pratikwp_meta_description').on('input', function() {
                var count = $(this).val().length;
                $('#meta-description-count').text(count);
                
                if (count > 160) {
                    $('#meta-description-count').css('color', 'red');
                } else if (count > 140) {
                    $('#meta-description-count').css('color', 'orange');
                } else {
                    $('#meta-description-count').css('color', 'green');
                }
            }).trigger('input');

            // Gallery images selection
            $('#select-gallery-images').on('click', function(e) {
                e.preventDefault();
                
                var mediaUploader = wp.media({
                    title: '<?php esc_html_e('Galeri Görsellerini Seç', 'pratikwp'); ?>',
                    button: {
                        text: '<?php esc_html_e('Seç', 'pratikwp'); ?>'
                    },
                    multiple: true
                });

                mediaUploader.on('select', function() {
                    var selection = mediaUploader.state().get('selection');
                    var imageIds = [];
                    var preview = $('#gallery-preview');
                    
                    preview.html('');
                    
                    selection.each(function(attachment) {
                        var attachmentData = attachment.toJSON();
                        imageIds.push(attachmentData.id);
                        
                        preview.append(
                            '<div class="gallery-thumb" data-id="' + attachmentData.id + '">' +
                            '<img src="' + attachmentData.sizes.thumbnail.url + '" alt="" />' +
                            '<button type="button" class="remove-gallery-image">&times;</button>' +
                            '</div>'
                        );
                    });
                    
                    $('#pratikwp_gallery_images').val(imageIds.join(','));
                });

                mediaUploader.open();
            });

            // Remove gallery image
            $(document).on('click', '.remove-gallery-image', function() {
                var thumb = $(this).closest('.gallery-thumb');
                var imageId = thumb.data('id');
                thumb.remove();
                
                var currentIds = $('#pratikwp_gallery_images').val().split(',');
                currentIds = currentIds.filter(id => id !== imageId.toString());
                $('#pratikwp_gallery_images').val(currentIds.join(','));
            });

            // Calculate reading time
            $('#calculate-reading-time').on('click', function() {
                var content = '';
                
                // Get content from editor
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get('content')) {
                    content = tinyMCE.get('content').getContent();
                } else {
                    content = $('#content').val();
                }
                
                // Remove HTML tags and count words
                var wordCount = content.replace(/<[^>]*>/g, '').split(/\s+/).length;
                var readingTime = Math.ceil(wordCount / 200); // 200 words per minute
                
                $('#pratikwp_estimated_reading_time').val(readingTime);
            });

            // Image upload functionality
            $('.upload-image-button').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var hiddenInput = button.siblings('input[type="hidden"]');
                var preview = button.siblings('.image-preview');
                var removeBtn = button.siblings('.remove-image-button');

                var mediaUploader = wp.media({
                    title: '<?php esc_html_e('Görsel Seç', 'pratikwp'); ?>',
                    button: {
                        text: '<?php esc_html_e('Seç', 'pratikwp'); ?>'
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    hiddenInput.val(attachment.id);
                    preview.html('<img src="' + attachment.sizes.medium.url + '" alt="" />').show();
                    removeBtn.show();
                });

                mediaUploader.open();
            });

            // Remove image
            $('.remove-image-button').on('click', function(e) {
                e.preventDefault();
                var button = $(this);
                button.siblings('input[type="hidden"]').val('');
                button.siblings('.image-preview').hide().html('');
                button.hide();
            });

            // Show/hide post format options based on selected format
            function togglePostFormatOptions() {
                var selectedFormat = $('input[name="post_format"]:checked').val() || 'standard';
                $('.post-format-option').hide();
                $('.post-format-option[data-format="' + selectedFormat + '"]').show();
            }
            
            togglePostFormatOptions();
            $('input[name="post_format"]').on('change', togglePostFormatOptions);
        });
        </script>

        <style>
        .pratikwp-meta-box .post-format-option {
            display: none;
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .pratikwp-meta-box .post-format-option h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        
        .pratikwp-meta-box .image-upload-wrapper {
            margin-bottom: 10px;
        }
        
        .pratikwp-meta-box .image-preview img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        </style>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['pratikwp_post_settings_nonce']) || 
            !wp_verify_nonce($_POST['pratikwp_post_settings_nonce'], 'pratikwp_post_settings_nonce')) {
            return;
        }

        // Check user permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Check if this is an autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Save meta fields
        $meta_fields = [
            '_pratikwp_post_layout' => 'sanitize_text_field',
            '_pratikwp_sidebar_position' => 'sanitize_text_field',
            '_pratikwp_hide_featured_image' => 'sanitize_text_field',
            '_pratikwp_hide_post_meta' => 'sanitize_text_field',
            '_pratikwp_hide_author_box' => 'sanitize_text_field',
            '_pratikwp_hide_related_posts' => 'sanitize_text_field',
            '_pratikwp_custom_read_more' => 'sanitize_text_field',
            '_pratikwp_featured_video' => 'esc_url_raw',
            '_pratikwp_gallery_images' => 'sanitize_text_field',
            '_pratikwp_estimated_reading_time' => 'absint',
            '_pratikwp_meta_description' => 'sanitize_textarea_field',
            '_pratikwp_focus_keyword' => 'sanitize_text_field',
            '_pratikwp_social_title' => 'sanitize_text_field',
            '_pratikwp_social_description' => 'sanitize_textarea_field',
            '_pratikwp_social_image' => 'absint',
            '_pratikwp_disable_lazy_loading' => 'sanitize_text_field',
            '_pratikwp_critical_css' => 'sanitize_text_field',
            '_pratikwp_preload_images' => 'sanitize_text_field',
        ];

        foreach ($meta_fields as $meta_key => $sanitize_callback) {
            $post_key = str_replace('_pratikwp_', 'pratikwp_', $meta_key);
            
            if (isset($_POST[$post_key])) {
                $value = call_user_func($sanitize_callback, $_POST[$post_key]);
                update_post_meta($post_id, $meta_key, $value);
            } else {
                // For checkboxes, delete meta if not checked
                if (in_array($meta_key, [
                    '_pratikwp_hide_featured_image',
                    '_pratikwp_hide_post_meta',
                    '_pratikwp_hide_author_box',
                    '_pratikwp_hide_related_posts',
                    '_pratikwp_disable_lazy_loading',
                    '_pratikwp_critical_css',
                    '_pratikwp_preload_images'
                ])) {
                    delete_post_meta($post_id, $meta_key);
                }
            }
        }

        // Save post format settings
        $post_format_settings = [];
        
        if (isset($_POST['pratikwp_quote_text'])) {
            $post_format_settings['quote_text'] = sanitize_textarea_field($_POST['pratikwp_quote_text']);
        }
        
        if (isset($_POST['pratikwp_quote_author'])) {
            $post_format_settings['quote_author'] = sanitize_text_field($_POST['pratikwp_quote_author']);
        }
        
        if (isset($_POST['pratikwp_link_url'])) {
            $post_format_settings['link_url'] = esc_url_raw($_POST['pratikwp_link_url']);
        }
        
        if (isset($_POST['pratikwp_link_new_tab'])) {
            $post_format_settings['link_new_tab'] = sanitize_text_field($_POST['pratikwp_link_new_tab']);
        }
        
        if (!empty($post_format_settings)) {
            update_post_meta($post_id, '_pratikwp_post_format_settings', $post_format_settings);
        }

        // Auto-calculate reading time if not set
        if (empty($_POST['pratikwp_estimated_reading_time'])) {
            $content = get_post_field('post_content', $post_id);
            $word_count = str_word_count(strip_tags($content));
            $reading_time = max(1, ceil($word_count / 200)); // 200 words per minute, minimum 1 minute
            update_post_meta($post_id, '_pratikwp_estimated_reading_time', $reading_time);
        }

        // Hook for additional meta fields
        do_action('pratikwp_save_post_settings', $post_id, $_POST);
    }

    /**
     * Get post layout for current post
     */
    public static function get_post_layout($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $layout = get_post_meta($post_id, '_pratikwp_post_layout', true);
        
        if (empty($layout)) {
            $layout = get_theme_mod('default_post_layout', 'contained');
        }

        return apply_filters('pratikwp_post_layout', $layout, $post_id);
    }

    /**
     * Check if featured image should be hidden
     */
    public static function hide_featured_image($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $hide = get_post_meta($post_id, '_pratikwp_hide_featured_image', true);
        
        return apply_filters('pratikwp_hide_featured_image', $hide === '1', $post_id);
    }

    /**
     * Check if post meta should be hidden
     */
    public static function hide_post_meta($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $hide = get_post_meta($post_id, '_pratikwp_hide_post_meta', true);
        
        return apply_filters('pratikwp_hide_post_meta', $hide === '1', $post_id);
    }

    /**
     * Check if author box should be hidden
     */
    public static function hide_author_box($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $hide = get_post_meta($post_id, '_pratikwp_hide_author_box', true);
        
        return apply_filters('pratikwp_hide_author_box', $hide === '1', $post_id);
    }

    /**
     * Check if related posts should be hidden
     */
    public static function hide_related_posts($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $hide = get_post_meta($post_id, '_pratikwp_hide_related_posts', true);
        
        return apply_filters('pratikwp_hide_related_posts', $hide === '1', $post_id);
    }

    /**
     * Get custom read more text
     */
    public static function get_custom_read_more($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $read_more = get_post_meta($post_id, '_pratikwp_custom_read_more', true);
        
        if (empty($read_more)) {
            $read_more = __('Devamını Oku', 'pratikwp');
        }

        return apply_filters('pratikwp_custom_read_more', $read_more, $post_id);
    }

    /**
     * Get featured video URL
     */
    public static function get_featured_video($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $video_url = get_post_meta($post_id, '_pratikwp_featured_video', true);
        
        return apply_filters('pratikwp_featured_video', $video_url, $post_id);
    }

    /**
     * Get gallery images
     */
    public static function get_gallery_images($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $gallery_images = get_post_meta($post_id, '_pratikwp_gallery_images', true);
        
        if (!empty($gallery_images)) {
            $image_ids = explode(',', $gallery_images);
            $image_ids = array_filter(array_map('trim', $image_ids));
            return array_map('absint', $image_ids);
        }
        
        return [];
    }

    /**
     * Get post format settings
     */
    public static function get_post_format_settings($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $settings = get_post_meta($post_id, '_pratikwp_post_format_settings', true);
        
        return apply_filters('pratikwp_post_format_settings', $settings ?: [], $post_id);
    }

    /**
     * Get estimated reading time
     */
    public static function get_reading_time($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $reading_time = get_post_meta($post_id, '_pratikwp_estimated_reading_time', true);
        
        if (empty($reading_time)) {
            // Auto-calculate if not set
            $content = get_post_field('post_content', $post_id);
            $word_count = str_word_count(strip_tags($content));
            $reading_time = max(1, ceil($word_count / 200)); // 200 words per minute
            
            // Update meta for future use
            update_post_meta($post_id, '_pratikwp_estimated_reading_time', $reading_time);
        }

        return apply_filters('pratikwp_reading_time', $reading_time, $post_id);
    }

    /**
     * Get meta description
     */
    public static function get_meta_description($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $meta_description = get_post_meta($post_id, '_pratikwp_meta_description', true);
        
        if (empty($meta_description)) {
            // Fallback to excerpt or content
            $meta_description = get_the_excerpt($post_id);
            if (empty($meta_description)) {
                $content = get_post_field('post_content', $post_id);
                $meta_description = wp_trim_words(strip_tags($content), 25);
            }
        }

        return apply_filters('pratikwp_meta_description', $meta_description, $post_id);
    }

    /**
     * Get focus keyword
     */
    public static function get_focus_keyword($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $keyword = get_post_meta($post_id, '_pratikwp_focus_keyword', true);
        
        return apply_filters('pratikwp_focus_keyword', $keyword, $post_id);
    }

    /**
     * Get social media title
     */
    public static function get_social_title($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $social_title = get_post_meta($post_id, '_pratikwp_social_title', true);
        
        if (empty($social_title)) {
            $social_title = get_the_title($post_id);
        }

        return apply_filters('pratikwp_social_title', $social_title, $post_id);
    }

    /**
     * Get social media description
     */
    public static function get_social_description($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $social_description = get_post_meta($post_id, '_pratikwp_social_description', true);
        
        if (empty($social_description)) {
            $social_description = self::get_meta_description($post_id);
        }

        return apply_filters('pratikwp_social_description', $social_description, $post_id);
    }

    /**
     * Get social media image
     */
    public static function get_social_image($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $social_image_id = get_post_meta($post_id, '_pratikwp_social_image', true);
        
        if ($social_image_id) {
            return wp_get_attachment_image_url($social_image_id, 'full');
        }

        // Fallback to featured image
        if (has_post_thumbnail($post_id)) {
            return get_the_post_thumbnail_url($post_id, 'full');
        }

        // Fallback to default social image
        return get_theme_mod('default_social_image', '');
    }

    /**
     * Check if lazy loading should be disabled
     */
    public static function lazy_loading_disabled($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $disabled = get_post_meta($post_id, '_pratikwp_disable_lazy_loading', true);
        
        return apply_filters('pratikwp_disable_lazy_loading', $disabled === '1', $post_id);
    }

    /**
     * Check if critical CSS should be used
     */
    public static function use_critical_css($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $use_critical = get_post_meta($post_id, '_pratikwp_critical_css', true);
        
        return apply_filters('pratikwp_use_critical_css', $use_critical === '1', $post_id);
    }

    /**
     * Check if images should be preloaded
     */
    public static function should_preload_images($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $preload = get_post_meta($post_id, '_pratikwp_preload_images', true);
        
        return apply_filters('pratikwp_preload_images', $preload === '1', $post_id);
    }

    /**
     * Get all post settings as array
     */
    public static function get_all_settings($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        return [
            'layout' => self::get_post_layout($post_id),
            'sidebar_position' => PratikWp_Page_Settings::get_sidebar_position($post_id),
            'hide_featured_image' => self::hide_featured_image($post_id),
            'hide_post_meta' => self::hide_post_meta($post_id),
            'hide_author_box' => self::hide_author_box($post_id),
            'hide_related_posts' => self::hide_related_posts($post_id),
            'custom_read_more' => self::get_custom_read_more($post_id),
            'featured_video' => self::get_featured_video($post_id),
            'gallery_images' => self::get_gallery_images($post_id),
            'post_format_settings' => self::get_post_format_settings($post_id),
            'reading_time' => self::get_reading_time($post_id),
            'meta_description' => self::get_meta_description($post_id),
            'focus_keyword' => self::get_focus_keyword($post_id),
            'social_title' => self::get_social_title($post_id),
            'social_description' => self::get_social_description($post_id),
            'social_image' => self::get_social_image($post_id),
            'lazy_loading_disabled' => self::lazy_loading_disabled($post_id),
            'use_critical_css' => self::use_critical_css($post_id),
            'preload_images' => self::should_preload_images($post_id),
        ];
    }
}