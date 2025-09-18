<?php
/**
 * Page Settings Meta Box
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Page Settings Meta Box handler
 */
class PratikWp_Page_Settings {

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
        add_meta_box(
            'pratikwp_page_settings',
            __('PratikWp Sayfa Ayarları', 'pratikwp'),
            [$this, 'render_meta_box'],
            'page',
            'side',
            'high'
        );

        // Add to custom post types if needed
        $post_types = apply_filters('pratikwp_page_settings_post_types', ['page']);
        
        foreach ($post_types as $post_type) {
            if ($post_type !== 'page') {
                add_meta_box(
                    'pratikwp_page_settings',
                    __('PratikWp Sayfa Ayarları', 'pratikwp'),
                    [$this, 'render_meta_box'],
                    $post_type,
                    'side',
                    'high'
                );
            }
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
        if (!$post || !in_array($post->post_type, ['page'])) {
            return;
        }

        wp_enqueue_style(
            'pratikwp-page-settings',
            get_template_directory_uri() . '/assets/css/admin.css',
            [],
            PRATIKWP_VERSION
        );

        wp_enqueue_script(
            'pratikwp-page-settings',
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
        wp_nonce_field('pratikwp_page_settings_nonce', 'pratikwp_page_settings_nonce');

        // Get current values
        $page_layout = get_post_meta($post->ID, '_pratikwp_page_layout', true);
        $sidebar_position = get_post_meta($post->ID, '_pratikwp_sidebar_position', true);
        $header_transparent = get_post_meta($post->ID, '_pratikwp_header_transparent', true);
        $hide_page_title = get_post_meta($post->ID, '_pratikwp_hide_page_title', true);
        $custom_body_class = get_post_meta($post->ID, '_pratikwp_custom_body_class', true);
        $custom_css = get_post_meta($post->ID, '_pratikwp_custom_css', true);
        $page_header_bg = get_post_meta($post->ID, '_pratikwp_page_header_bg', true);
        $page_header_overlay = get_post_meta($post->ID, '_pratikwp_page_header_overlay', true);
        $disable_comments = get_post_meta($post->ID, '_pratikwp_disable_comments', true);
        $custom_excerpt = get_post_meta($post->ID, '_pratikwp_custom_excerpt', true);

        ?>
        <div class="pratikwp-meta-box">
            <!-- Page Layout -->
            <div class="meta-box-section">
                <label for="pratikwp_page_layout" class="meta-label">
                    <strong><?php esc_html_e('Sayfa Düzeni', 'pratikwp'); ?></strong>
                </label>
                <select name="pratikwp_page_layout" id="pratikwp_page_layout" class="widefat">
                    <option value=""><?php esc_html_e('Varsayılan (Tema Ayarından)', 'pratikwp'); ?></option>
                    <option value="full-width" <?php selected($page_layout, 'full-width'); ?>><?php esc_html_e('Tam Genişlik', 'pratikwp'); ?></option>
                    <option value="contained" <?php selected($page_layout, 'contained'); ?>><?php esc_html_e('Container İçinde', 'pratikwp'); ?></option>
                    <option value="boxed" <?php selected($page_layout, 'boxed'); ?>><?php esc_html_e('Kutulu Düzen', 'pratikwp'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Bu sayfanın ana düzenini belirler', 'pratikwp'); ?></p>
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
                <p class="description"><?php esc_html_e('Sidebar\'ın görüntüleneceği konum', 'pratikwp'); ?></p>
            </div>

            <!-- Header Options -->
            <div class="meta-box-section">
                <label class="meta-label">
                    <strong><?php esc_html_e('Header Seçenekleri', 'pratikwp'); ?></strong>
                </label>
                
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_header_transparent" value="1" <?php checked($header_transparent, '1'); ?> />
                    <?php esc_html_e('Transparent Header', 'pratikwp'); ?>
                </label>
                <p class="description"><?php esc_html_e('Header\'ı sayfa içeriğinin üzerine yerleştirir', 'pratikwp'); ?></p>
            </div>

            <!-- Page Title -->
            <div class="meta-box-section">
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_hide_page_title" value="1" <?php checked($hide_page_title, '1'); ?> />
                    <strong><?php esc_html_e('Sayfa Başlığını Gizle', 'pratikwp'); ?></strong>
                </label>
                <p class="description"><?php esc_html_e('Bu sayfada sayfa başlığını göstermez', 'pratikwp'); ?></p>
            </div>

            <!-- Page Header Background -->
            <div class="meta-box-section">
                <label for="pratikwp_page_header_bg" class="meta-label">
                    <strong><?php esc_html_e('Sayfa Header Arkaplanı', 'pratikwp'); ?></strong>
                </label>
                <div class="image-upload-wrapper">
                    <input type="hidden" name="pratikwp_page_header_bg" id="pratikwp_page_header_bg" value="<?php echo esc_attr($page_header_bg); ?>" />
                    <button type="button" class="button button-secondary upload-image-button">
                        <?php esc_html_e('Görsel Seç', 'pratikwp'); ?>
                    </button>
                    <button type="button" class="button button-link-delete remove-image-button" <?php echo empty($page_header_bg) ? 'style="display:none;"' : ''; ?>>
                        <?php esc_html_e('Kaldır', 'pratikwp'); ?>
                    </button>
                    <div class="image-preview" <?php echo empty($page_header_bg) ? 'style="display:none;"' : ''; ?>>
                        <?php if ($page_header_bg): ?>
                            <img src="<?php echo esc_url(wp_get_attachment_image_url($page_header_bg, 'medium')); ?>" alt="" />
                        <?php endif; ?>
                    </div>
                </div>
                <p class="description"><?php esc_html_e('Sayfa başlığı bölümünde gösterilecek arkaplan görseli', 'pratikwp'); ?></p>
            </div>

            <!-- Page Header Overlay -->
            <div class="meta-box-section">
                <label for="pratikwp_page_header_overlay" class="meta-label">
                    <strong><?php esc_html_e('Header Overlay Rengi', 'pratikwp'); ?></strong>
                </label>
                <input type="text" name="pratikwp_page_header_overlay" id="pratikwp_page_header_overlay" 
                       value="<?php echo esc_attr($page_header_overlay); ?>" class="color-picker" />
                <p class="description"><?php esc_html_e('Arkaplan görseli üzerine renk overlay ekler', 'pratikwp'); ?></p>
            </div>

            <!-- Custom Body Class -->
            <div class="meta-box-section">
                <label for="pratikwp_custom_body_class" class="meta-label">
                    <strong><?php esc_html_e('Özel Body Class', 'pratikwp'); ?></strong>
                </label>
                <input type="text" name="pratikwp_custom_body_class" id="pratikwp_custom_body_class" 
                       value="<?php echo esc_attr($custom_body_class); ?>" class="widefat" 
                       placeholder="<?php esc_attr_e('custom-page special-layout', 'pratikwp'); ?>" />
                <p class="description"><?php esc_html_e('Bu sayfaya özel CSS sınıfları ekler', 'pratikwp'); ?></p>
            </div>

            <!-- Comments -->
            <div class="meta-box-section">
                <label class="checkbox-label">
                    <input type="checkbox" name="pratikwp_disable_comments" value="1" <?php checked($disable_comments, '1'); ?> />
                    <strong><?php esc_html_e('Yorumları Devre Dışı Bırak', 'pratikwp'); ?></strong>
                </label>
                <p class="description"><?php esc_html_e('Bu sayfada yorum bölümünü gizler', 'pratikwp'); ?></p>
            </div>

            <!-- Custom Excerpt -->
            <div class="meta-box-section">
                <label for="pratikwp_custom_excerpt" class="meta-label">
                    <strong><?php esc_html_e('Özel Özet', 'pratikwp'); ?></strong>
                </label>
                <textarea name="pratikwp_custom_excerpt" id="pratikwp_custom_excerpt" 
                          rows="3" class="widefat" 
                          placeholder="<?php esc_attr_e('Bu sayfa için özel bir özet yazın...', 'pratikwp'); ?>"><?php echo esc_textarea($custom_excerpt); ?></textarea>
                <p class="description"><?php esc_html_e('Meta açıklama ve paylaşım kartları için kullanılır', 'pratikwp'); ?></p>
            </div>

            <!-- Custom CSS -->
            <div class="meta-box-section">
                <label for="pratikwp_custom_css" class="meta-label">
                    <strong><?php esc_html_e('Özel CSS', 'pratikwp'); ?></strong>
                </label>
                <textarea name="pratikwp_custom_css" id="pratikwp_custom_css" 
                          rows="8" class="widefat code-textarea" 
                          placeholder="<?php esc_attr_e('/* Bu sayfaya özel CSS kodları */\n.custom-style {\n    color: #333;\n}', 'pratikwp'); ?>"><?php echo esc_textarea($custom_css); ?></textarea>
                <p class="description"><?php esc_html_e('Sadece bu sayfada geçerli olacak CSS kodları', 'pratikwp'); ?></p>
            </div>

            <!-- Advanced Settings Toggle -->
            <div class="meta-box-section">
                <button type="button" class="button button-secondary toggle-advanced-settings">
                    <?php esc_html_e('Gelişmiş Ayarlar', 'pratikwp'); ?>
                    <span class="dashicons dashicons-arrow-down-alt2"></span>
                </button>
            </div>

            <!-- Advanced Settings (Hidden by default) -->
            <div class="advanced-settings" style="display: none;">
                <!-- SEO Settings -->
                <div class="meta-box-section">
                    <h4 class="section-title"><?php esc_html_e('SEO Ayarları', 'pratikwp'); ?></h4>
                    
                    <label for="pratikwp_custom_title" class="meta-label">
                        <strong><?php esc_html_e('Özel Sayfa Başlığı (Title)', 'pratikwp'); ?></strong>
                    </label>
                    <input type="text" name="pratikwp_custom_title" id="pratikwp_custom_title" 
                           value="<?php echo esc_attr(get_post_meta($post->ID, '_pratikwp_custom_title', true)); ?>" 
                           class="widefat" />
                    <p class="description"><?php esc_html_e('Browser tab\'ında ve arama motorlarında görünecek başlık', 'pratikwp'); ?></p>
                </div>

                <!-- Schema Markup -->
                <div class="meta-box-section">
                    <label for="pratikwp_schema_type" class="meta-label">
                        <strong><?php esc_html_e('Schema Tipi', 'pratikwp'); ?></strong>
                    </label>
                    <select name="pratikwp_schema_type" id="pratikwp_schema_type" class="widefat">
                        <option value=""><?php esc_html_e('Varsayılan', 'pratikwp'); ?></option>
                        <option value="Article" <?php selected(get_post_meta($post->ID, '_pratikwp_schema_type', true), 'Article'); ?>><?php esc_html_e('Makale', 'pratikwp'); ?></option>
                        <option value="Service" <?php selected(get_post_meta($post->ID, '_pratikwp_schema_type', true), 'Service'); ?>><?php esc_html_e('Hizmet', 'pratikwp'); ?></option>
                        <option value="Product" <?php selected(get_post_meta($post->ID, '_pratikwp_schema_type', true), 'Product'); ?>><?php esc_html_e('Ürün', 'pratikwp'); ?></option>
                        <option value="Organization" <?php selected(get_post_meta($post->ID, '_pratikwp_schema_type', true), 'Organization'); ?>><?php esc_html_e('Organizasyon', 'pratikwp'); ?></option>
                    </select>
                </div>

                <!-- Social Media -->
                <div class="meta-box-section">
                    <h4 class="section-title"><?php esc_html_e('Sosyal Medya', 'pratikwp'); ?></h4>
                    
                    <label for="pratikwp_og_image" class="meta-label">
                        <strong><?php esc_html_e('Paylaşım Görseli (OG Image)', 'pratikwp'); ?></strong>
                    </label>
                    <div class="image-upload-wrapper">
                        <input type="hidden" name="pratikwp_og_image" id="pratikwp_og_image" 
                               value="<?php echo esc_attr(get_post_meta($post->ID, '_pratikwp_og_image', true)); ?>" />
                        <button type="button" class="button button-secondary upload-image-button">
                            <?php esc_html_e('Görsel Seç', 'pratikwp'); ?>
                        </button>
                        <button type="button" class="button button-link-delete remove-image-button" 
                                <?php echo empty(get_post_meta($post->ID, '_pratikwp_og_image', true)) ? 'style="display:none;"' : ''; ?>>
                            <?php esc_html_e('Kaldır', 'pratikwp'); ?>
                        </button>
                        <div class="image-preview" 
                             <?php echo empty(get_post_meta($post->ID, '_pratikwp_og_image', true)) ? 'style="display:none;"' : ''; ?>>
                            <?php 
                            $og_image = get_post_meta($post->ID, '_pratikwp_og_image', true);
                            if ($og_image): 
                            ?>
                                <img src="<?php echo esc_url(wp_get_attachment_image_url($og_image, 'medium')); ?>" alt="" />
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="description"><?php esc_html_e('Facebook, Twitter vb. platformlarda paylaşıldığında görünecek görsel', 'pratikwp'); ?></p>
                </div>
            </div>

            <!-- Import/Export Settings -->
            <div class="meta-box-section import-export-section">
                <h4 class="section-title"><?php esc_html_e('Ayarları İçe/Dışa Aktar', 'pratikwp'); ?></h4>
                <div class="import-export-actions">
                    <button type="button" class="button button-secondary" id="export-page-settings">
                        <?php esc_html_e('Ayarları Dışa Aktar', 'pratikwp'); ?>
                    </button>
                    <button type="button" class="button button-secondary" id="import-page-settings">
                        <?php esc_html_e('Ayarları İçe Aktar', 'pratikwp'); ?>
                    </button>
                </div>
                <input type="file" id="import-file" accept=".json" style="display: none;" />
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            // Color picker
            $('.color-picker').wpColorPicker();

            // Image upload
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

            // Toggle advanced settings
            $('.toggle-advanced-settings').on('click', function() {
                var button = $(this);
                var icon = button.find('.dashicons');
                var advanced = $('.advanced-settings');
                
                advanced.slideToggle();
                icon.toggleClass('dashicons-arrow-down-alt2 dashicons-arrow-up-alt2');
            });

            // Export page settings
            $('#export-page-settings').on('click', function() {
                var settings = {};
                $('.pratikwp-meta-box input, .pratikwp-meta-box select, .pratikwp-meta-box textarea').each(function() {
                    var input = $(this);
                    var name = input.attr('name');
                    var value = input.val();
                    
                    if (input.attr('type') === 'checkbox') {
                        value = input.is(':checked');
                    }
                    
                    if (name && name.startsWith('pratikwp_')) {
                        settings[name] = value;
                    }
                });
                
                var dataStr = JSON.stringify(settings, null, 2);
                var dataBlob = new Blob([dataStr], {type: 'application/json'});
                var url = URL.createObjectURL(dataBlob);
                var link = document.createElement('a');
                link.href = url;
                link.download = 'page-settings-<?php echo $post->ID; ?>.json';
                link.click();
            });

            // Import page settings
            $('#import-page-settings').on('click', function() {
                $('#import-file').click();
            });

            $('#import-file').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            var settings = JSON.parse(e.target.result);
                            for (var name in settings) {
                                var input = $('[name="' + name + '"]');
                                if (input.length) {
                                    if (input.attr('type') === 'checkbox') {
                                        input.prop('checked', settings[name]);
                                    } else {
                                        input.val(settings[name]);
                                    }
                                }
                            }
                            alert('<?php esc_html_e('Ayarlar başarıyla içe aktarıldı!', 'pratikwp'); ?>');
                        } catch (error) {
                            alert('<?php esc_html_e('Geçersiz dosya formatı!', 'pratikwp'); ?>');
                        }
                    };
                    reader.readAsText(file);
                }
            });
        });
        </script>

        <style>
        .pratikwp-meta-box .meta-box-section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .pratikwp-meta-box .meta-box-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .pratikwp-meta-box .meta-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .pratikwp-meta-box .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
        }
        
        .pratikwp-meta-box .description {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
            margin-bottom: 0;
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
        
        .pratikwp-meta-box .code-textarea {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .pratikwp-meta-box .section-title {
            margin: 0 0 15px 0;
            padding: 10px 0 5px 0;
            border-bottom: 1px solid #007cba;
            color: #007cba;
            font-size: 14px;
        }
        
        .pratikwp-meta-box .toggle-advanced-settings {
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .pratikwp-meta-box .import-export-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        
        .pratikwp-meta-box .advanced-settings {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px dashed #ddd;
        }
        </style>
        <?php
    }

    /**
     * Save meta box data
     */
    public function save_meta_boxes($post_id) {
        // Check nonce
        if (!isset($_POST['pratikwp_page_settings_nonce']) || 
            !wp_verify_nonce($_POST['pratikwp_page_settings_nonce'], 'pratikwp_page_settings_nonce')) {
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
            '_pratikwp_page_layout' => 'sanitize_text_field',
            '_pratikwp_sidebar_position' => 'sanitize_text_field',
            '_pratikwp_header_transparent' => 'sanitize_text_field',
            '_pratikwp_hide_page_title' => 'sanitize_text_field',
            '_pratikwp_custom_body_class' => 'sanitize_text_field',
            '_pratikwp_custom_css' => 'wp_strip_all_tags',
            '_pratikwp_page_header_bg' => 'absint',
            '_pratikwp_page_header_overlay' => 'sanitize_hex_color',
            '_pratikwp_disable_comments' => 'sanitize_text_field',
            '_pratikwp_custom_excerpt' => 'sanitize_textarea_field',
            '_pratikwp_custom_title' => 'sanitize_text_field',
            '_pratikwp_schema_type' => 'sanitize_text_field',
            '_pratikwp_disable_elementor_css' => 'sanitize_text_field',
            '_pratikwp_preload_fonts' => 'sanitize_text_field',
            '_pratikwp_og_image' => 'absint',
        ];

        foreach ($meta_fields as $meta_key => $sanitize_callback) {
            $post_key = str_replace('_pratikwp_', 'pratikwp_', $meta_key);
            
            if (isset($_POST[$post_key])) {
                $value = call_user_func($sanitize_callback, $_POST[$post_key]);
                update_post_meta($post_id, $meta_key, $value);
            } else {
                // For checkboxes, delete meta if not checked
                if (in_array($meta_key, [
                    '_pratikwp_header_transparent',
                    '_pratikwp_hide_page_title', 
                    '_pratikwp_disable_comments',
                    '_pratikwp_disable_elementor_css',
                    '_pratikwp_preload_fonts'
                ])) {
                    delete_post_meta($post_id, $meta_key);
                }
            }
        }

        // Hook for additional meta fields
        do_action('pratikwp_save_page_settings', $post_id, $_POST);
    }

    /**
     * Get page layout for current page
     */
    public static function get_page_layout($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $layout = get_post_meta($post_id, '_pratikwp_page_layout', true);
        
        if (empty($layout)) {
            $layout = get_theme_mod('default_page_layout', 'contained');
        }

        return apply_filters('pratikwp_page_layout', $layout, $post_id);
    }

    /**
     * Get sidebar position for current page
     */
    public static function get_sidebar_position($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $position = get_post_meta($post_id, '_pratikwp_sidebar_position', true);
        
        if (empty($position)) {
            $position = get_theme_mod('default_sidebar_position', 'right');
        }

        return apply_filters('pratikwp_sidebar_position', $position, $post_id);
    }

    /**
     * Check if header should be transparent
     */
    public static function is_header_transparent($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $transparent = get_post_meta($post_id, '_pratikwp_header_transparent', true);
        
        return apply_filters('pratikwp_header_transparent', $transparent === '1', $post_id);
    }

    /**
     * Check if page title should be hidden
     */
    public static function hide_page_title($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $hide = get_post_meta($post_id, '_pratikwp_hide_page_title', true);
        
        return apply_filters('pratikwp_hide_page_title', $hide === '1', $post_id);
    }

    /**
     * Get custom body classes for page
     */
    public static function get_custom_body_classes($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $classes = get_post_meta($post_id, '_pratikwp_custom_body_class', true);
        
        if (!empty($classes)) {
            $classes = explode(' ', $classes);
            $classes = array_filter(array_map('trim', $classes));
        } else {
            $classes = [];
        }

        return apply_filters('pratikwp_custom_body_classes', $classes, $post_id);
    }

    /**
     * Get custom CSS for page
     */
    public static function get_custom_css($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $css = get_post_meta($post_id, '_pratikwp_custom_css', true);
        
        return apply_filters('pratikwp_page_custom_css', $css, $post_id);
    }

    /**
     * Get page header background
     */
    public static function get_page_header_background($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $bg_id = get_post_meta($post_id, '_pratikwp_page_header_bg', true);
        $overlay = get_post_meta($post_id, '_pratikwp_page_header_overlay', true);
        
        $background = [];
        
        if ($bg_id) {
            $background['image'] = wp_get_attachment_image_url($bg_id, 'full');
            $background['id'] = $bg_id;
        }
        
        if ($overlay) {
            $background['overlay'] = $overlay;
        }

        return apply_filters('pratikwp_page_header_background', $background, $post_id);
    }

    /**
     * Check if comments should be disabled
     */
    public static function comments_disabled($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $disabled = get_post_meta($post_id, '_pratikwp_disable_comments', true);
        
        return apply_filters('pratikwp_disable_comments', $disabled === '1', $post_id);
    }

    /**
     * Get custom excerpt for page
     */
    public static function get_custom_excerpt($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $excerpt = get_post_meta($post_id, '_pratikwp_custom_excerpt', true);
        
        return apply_filters('pratikwp_custom_excerpt', $excerpt, $post_id);
    }

    /**
     * Get custom title for page
     */
    public static function get_custom_title($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $title = get_post_meta($post_id, '_pratikwp_custom_title', true);
        
        return apply_filters('pratikwp_custom_title', $title, $post_id);
    }

    /**
     * Get schema type for page
     */
    public static function get_schema_type($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $schema = get_post_meta($post_id, '_pratikwp_schema_type', true);
        
        if (empty($schema)) {
            $schema = is_page() ? 'WebPage' : 'Article';
        }

        return apply_filters('pratikwp_schema_type', $schema, $post_id);
    }

    /**
     * Check if Elementor CSS should be disabled
     */
    public static function elementor_css_disabled($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $disabled = get_post_meta($post_id, '_pratikwp_disable_elementor_css', true);
        
        return apply_filters('pratikwp_disable_elementor_css', $disabled === '1', $post_id);
    }

    /**
     * Check if fonts should be preloaded
     */
    public static function should_preload_fonts($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $preload = get_post_meta($post_id, '_pratikwp_preload_fonts', true);
        
        return apply_filters('pratikwp_preload_fonts', $preload === '1', $post_id);
    }

    /**
     * Get OG image for page
     */
    public static function get_og_image($post_id = null) {
        if (!$post_id) {
            $post_id = get_the_ID();
        }

        $og_image_id = get_post_meta($post_id, '_pratikwp_og_image', true);
        
        if ($og_image_id) {
            return wp_get_attachment_image_url($og_image_id, 'full');
        }

        // Fallback to featured image
        if (has_post_thumbnail($post_id)) {
            return get_the_post_thumbnail_url($post_id, 'full');
        }

        // Fallback to default OG image from customizer
        return get_theme_mod('default_og_image', '');
    }

    /**
     * Export page settings to JSON
     */
    public static function export_page_settings($post_id) {
        $meta_fields = [
            '_pratikwp_page_layout',
            '_pratikwp_sidebar_position',
            '_pratikwp_header_transparent',
            '_pratikwp_hide_page_title',
            '_pratikwp_custom_body_class',
            '_pratikwp_custom_css',
            '_pratikwp_page_header_bg',
            '_pratikwp_page_header_overlay',
            '_pratikwp_disable_comments',
            '_pratikwp_custom_excerpt',
            '_pratikwp_custom_title',
            '_pratikwp_schema_type',
            '_pratikwp_disable_elementor_css',
            '_pratikwp_preload_fonts',
            '_pratikwp_og_image',
        ];

        $settings = [];
        
        foreach ($meta_fields as $meta_key) {
            $value = get_post_meta($post_id, $meta_key, true);
            if (!empty($value)) {
                $settings[str_replace('_pratikwp_', 'pratikwp_', $meta_key)] = $value;
            }
        }

        return json_encode($settings, JSON_PRETTY_PRINT);
    }

    /**
     * Import page settings from JSON
     */
    public static function import_page_settings($post_id, $json_data) {
        $settings = json_decode($json_data, true);
        
        if (!is_array($settings)) {
            return false;
        }

        $meta_fields = [
            'pratikwp_page_layout' => '_pratikwp_page_layout',
            'pratikwp_sidebar_position' => '_pratikwp_sidebar_position',
            'pratikwp_header_transparent' => '_pratikwp_header_transparent',
            'pratikwp_hide_page_title' => '_pratikwp_hide_page_title',
            'pratikwp_custom_body_class' => '_pratikwp_custom_body_class',
            'pratikwp_custom_css' => '_pratikwp_custom_css',
            'pratikwp_page_header_bg' => '_pratikwp_page_header_bg',
            'pratikwp_page_header_overlay' => '_pratikwp_page_header_overlay',
            'pratikwp_disable_comments' => '_pratikwp_disable_comments',
            'pratikwp_custom_excerpt' => '_pratikwp_custom_excerpt',
            'pratikwp_custom_title' => '_pratikwp_custom_title',
            'pratikwp_schema_type' => '_pratikwp_schema_type',
            'pratikwp_disable_elementor_css' => '_pratikwp_disable_elementor_css',
            'pratikwp_preload_fonts' => '_pratikwp_preload_fonts',
            'pratikwp_og_image' => '_pratikwp_og_image',
        ];

        foreach ($settings as $key => $value) {
            if (isset($meta_fields[$key])) {
                $meta_key = $meta_fields[$key];
                
                // Sanitize based on field type
                switch ($meta_key) {
                    case '_pratikwp_page_header_bg':
                    case '_pratikwp_og_image':
                        $value = absint($value);
                        break;
                    case '_pratikwp_page_header_overlay':
                        $value = sanitize_hex_color($value);
                        break;
                    case '_pratikwp_custom_css':
                        $value = wp_strip_all_tags($value);
                        break;
                    case '_pratikwp_custom_excerpt':
                        $value = sanitize_textarea_field($value);
                        break;
                    default:
                        $value = sanitize_text_field($value);
                }
                
                if (!empty($value)) {
                    update_post_meta($post_id, $meta_key, $value);
                } else {
                    delete_post_meta($post_id, $meta_key);
                }
            }
        }

        return true;
    }
}