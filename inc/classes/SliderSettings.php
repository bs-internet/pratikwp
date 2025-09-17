<?php
/**
 * Slider Settings Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_SliderSettings {
    
    private $option_group = 'pratikwp_slider_group';
    private $page_slug = 'pratikwp-slider';
    private $max_slides = 10;
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_ajax_pratikwp_slider_preview', [$this, 'slider_preview']);
        add_action('wp_ajax_pratikwp_slider_reorder', [$this, 'slider_reorder']);
        add_shortcode('pratikwp_slider', [$this, 'slider_shortcode']);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Slider Ayarları', 'pratikwp'),
            __('Slider', 'pratikwp'),
            'manage_options',
            $this->page_slug,
            [$this, 'slider_page'],
            'dashicons-images-alt2',
            62
        );
        
        add_submenu_page(
            $this->page_slug,
            __('Slider Yönetimi', 'pratikwp'),
            __('Slider Yönetimi', 'pratikwp'),
            'manage_options',
            $this->page_slug,
            [$this, 'slider_page']
        );
        
        add_submenu_page(
            $this->page_slug,
            __('Slider Ayarları', 'pratikwp'),
            __('Genel Ayarlar', 'pratikwp'),
            'manage_options',
            $this->page_slug . '-settings',
            [$this, 'slider_settings_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Slider items
        for ($i = 1; $i <= $this->max_slides; $i++) {
            register_setting($this->option_group, "slider_desktop_$i", [
                'type' => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default' => ''
            ]);
            
            register_setting($this->option_group, "slider_mobile_$i", [
                'type' => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default' => ''
            ]);
            
            register_setting($this->option_group, "slider_yazi_$i", [
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            ]);
            
            register_setting($this->option_group, "slider_aciklama_$i", [
                'type' => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'default' => ''
            ]);
            
            register_setting($this->option_group, "slider_link_$i", [
                'type' => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default' => ''
            ]);
            
            register_setting($this->option_group, "slider_buton_text_$i", [
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            ]);
            
            register_setting($this->option_group, "slider_active_$i", [
                'type' => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default' => false
            ]);
            
            register_setting($this->option_group, "slider_order_$i", [
                'type' => 'integer',
                'sanitize_callback' => 'absint',
                'default' => $i
            ]);
        }
        
        // General slider settings
        register_setting($this->option_group, 'slider_autoplay', [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => true
        ]);
        
        register_setting($this->option_group, 'slider_autoplay_delay', [
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 5000
        ]);
        
        register_setting($this->option_group, 'slider_show_arrows', [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => true
        ]);
        
        register_setting($this->option_group, 'slider_show_dots', [
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default' => true
        ]);
        
        register_setting($this->option_group, 'slider_transition_effect', [
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'slide'
        ]);
        
        register_setting($this->option_group, 'slider_height_desktop', [
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 500
        ]);
        
        register_setting($this->option_group, 'slider_height_mobile', [
            'type' => 'integer',
            'sanitize_callback' => 'absint',
            'default' => 300
        ]);
    }

    /**
     * Main slider page
     */
    public function slider_page() {
        $active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'slider1';
        $slide_num = (int) str_replace('slider', '', $active_tab);
        
        if ($slide_num < 1 || $slide_num > $this->max_slides) {
            $slide_num = 1;
            $active_tab = 'slider1';
        }
        ?>
        <div class="wrap pratikwp-slider-admin">
            <h1><?php esc_html_e('Slider Yönetimi', 'pratikwp'); ?></h1>
            
            <div class="pratikwp-slider-header">
                <div class="pratikwp-slider-stats">
                    <span class="stat-item">
                        <strong><?php esc_html_e('Aktif Slider:', 'pratikwp'); ?></strong>
                        <?php echo $this->get_active_slides_count(); ?>
                    </span>
                    <span class="stat-item">
                        <strong><?php esc_html_e('Toplam Slider:', 'pratikwp'); ?></strong>
                        <?php echo $this->max_slides; ?>
                    </span>
                </div>
                
                <div class="pratikwp-slider-actions">
                    <button type="button" class="button button-secondary" id="preview-slider">
                        <?php esc_html_e('Önizleme', 'pratikwp'); ?>
                    </button>
                    <a href="<?php echo admin_url('admin.php?page=' . $this->page_slug . '-settings'); ?>" class="button">
                        <?php esc_html_e('Genel Ayarlar', 'pratikwp'); ?>
                    </a>
                </div>
            </div>
            
            <h2 class="nav-tab-wrapper">
                <?php for ($i = 1; $i <= $this->max_slides; $i++): 
                    $is_active = get_option("slider_active_$i", false);
                    $tab_class = 'nav-tab';
                    if ($active_tab === "slider$i") {
                        $tab_class .= ' nav-tab-active';
                    }
                    if ($is_active) {
                        $tab_class .= ' nav-tab-enabled';
                    }
                    $url = admin_url('admin.php?page=' . $this->page_slug . "&tab=slider$i");
                ?>
                    <a href="<?php echo esc_url($url); ?>" class="<?php echo esc_attr($tab_class); ?>">
                        <?php printf(__('Slider %d', 'pratikwp'), $i); ?>
                        <?php if ($is_active): ?>
                            <span class="slider-status active">●</span>
                        <?php endif; ?>
                    </a>
                <?php endfor; ?>
            </h2>

            <form method="post" action="options.php" class="pratikwp-slider-form">
                <?php settings_fields($this->option_group); ?>
                
                <div class="slider-edit-container">
                    <div class="slider-preview-section">
                        <h3><?php printf(__('Slider %d Düzenle', 'pratikwp'), $slide_num); ?></h3>
                        
                        <div class="slider-preview">
                            <?php
                            $desktop_image = get_option("slider_desktop_$slide_num");
                            $mobile_image = get_option("slider_mobile_$slide_num");
                            if ($desktop_image):
                            ?>
                                <img src="<?php echo esc_url($desktop_image); ?>" alt="Slider Preview" class="slider-preview-image" />
                            <?php else: ?>
                                <div class="slider-preview-placeholder">
                                    <span><?php esc_html_e('Görsel yükleyin', 'pratikwp'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="slider-fields-section">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Aktif', 'pratikwp'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="slider_active_<?php echo $slide_num; ?>" value="1" <?php checked(get_option("slider_active_$slide_num")); ?> />
                                        <?php esc_html_e('Bu slider\'ı aktif et', 'pratikwp'); ?>
                                    </label>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Masaüstü Görsel', 'pratikwp'); ?></th>
                                <td>
                                    <div class="media-upload-wrapper">
                                        <input type="url" id="slider_desktop_<?php echo $slide_num; ?>" name="slider_desktop_<?php echo $slide_num; ?>" value="<?php echo esc_attr(get_option("slider_desktop_$slide_num")); ?>" class="regular-text" />
                                        <button class="button pratikwp-upload-button" data-target="slider_desktop_<?php echo $slide_num; ?>" type="button">
                                            <?php esc_html_e('Görsel Seç', 'pratikwp'); ?>
                                        </button>
                                        <button class="button pratikwp-remove-image" data-target="slider_desktop_<?php echo $slide_num; ?>" type="button">
                                            <?php esc_html_e('Kaldır', 'pratikwp'); ?>
                                        </button>
                                    </div>
                                    <p class="description"><?php esc_html_e('Önerilen boyut: 1920x500px', 'pratikwp'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Mobil Görsel', 'pratikwp'); ?></th>
                                <td>
                                    <div class="media-upload-wrapper">
                                        <input type="url" id="slider_mobile_<?php echo $slide_num; ?>" name="slider_mobile_<?php echo $slide_num; ?>" value="<?php echo esc_attr(get_option("slider_mobile_$slide_num")); ?>" class="regular-text" />
                                        <button class="button pratikwp-upload-button" data-target="slider_mobile_<?php echo $slide_num; ?>" type="button">
                                            <?php esc_html_e('Görsel Seç', 'pratikwp'); ?>
                                        </button>
                                        <button class="button pratikwp-remove-image" data-target="slider_mobile_<?php echo $slide_num; ?>" type="button">
                                            <?php esc_html_e('Kaldır', 'pratikwp'); ?>
                                        </button>
                                    </div>
                                    <p class="description"><?php esc_html_e('Önerilen boyut: 768x400px (boş bırakılırsa masaüstü görsel kullanılır)', 'pratikwp'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Başlık', 'pratikwp'); ?></th>
                                <td>
                                    <input type="text" name="slider_yazi_<?php echo $slide_num; ?>" value="<?php echo esc_attr(get_option("slider_yazi_$slide_num")); ?>" class="regular-text" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Açıklama', 'pratikwp'); ?></th>
                                <td>
                                    <textarea name="slider_aciklama_<?php echo $slide_num; ?>" rows="3" class="large-text"><?php echo esc_textarea(get_option("slider_aciklama_$slide_num")); ?></textarea>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Link URL', 'pratikwp'); ?></th>
                                <td>
                                    <input type="url" name="slider_link_<?php echo $slide_num; ?>" value="<?php echo esc_attr(get_option("slider_link_$slide_num")); ?>" class="regular-text" />
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Buton Metni', 'pratikwp'); ?></th>
                                <td>
                                    <input type="text" name="slider_buton_text_<?php echo $slide_num; ?>" value="<?php echo esc_attr(get_option("slider_buton_text_$slide_num")); ?>" class="regular-text" />
                                    <p class="description"><?php esc_html_e('Boş bırakılırsa "Devamını Oku" kullanılır', 'pratikwp'); ?></p>
                                </td>
                            </tr>
                            
                            <tr>
                                <th scope="row"><?php esc_html_e('Sıralama', 'pratikwp'); ?></th>
                                <td>
                                    <input type="number" name="slider_order_<?php echo $slide_num; ?>" value="<?php echo esc_attr(get_option("slider_order_$slide_num", $slide_num)); ?>" min="1" max="<?php echo $this->max_slides; ?>" />
                                    <p class="description"><?php esc_html_e('Slider\'ın gösterim sırası', 'pratikwp'); ?></p>
                                </td>
                            </tr>
                        </table>
                        
                        <?php submit_button(); ?>
                    </div>
                </div>
            </form>
            
            <div class="pratikwp-slider-help">
                <h3><?php esc_html_e('Slider Kullanımı', 'pratikwp'); ?></h3>
                <p><?php esc_html_e('Slider\'ı sayfalarınızda göstermek için aşağıdaki yöntemleri kullanabilirsiniz:', 'pratikwp'); ?></p>
                <ul>
                    <li><strong>Shortcode:</strong> <code>[pratikwp_slider]</code></li>
                    <li><strong>PHP Kodu:</strong> <code>&lt;?php echo do_shortcode('[pratikwp_slider]'); ?&gt;</code></li>
                    <li><strong>Fonksiyon:</strong> <code>&lt;?php pratikwp_slider(); ?&gt;</code></li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Slider settings page
     */
    public function slider_settings_page() {
        ?>
        <div class="wrap pratikwp-slider-admin">
            <h1><?php esc_html_e('Slider Genel Ayarları', 'pratikwp'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields($this->option_group); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Otomatik Oynatma', 'pratikwp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="slider_autoplay" value="1" <?php checked(get_option('slider_autoplay', true)); ?> />
                                <?php esc_html_e('Slider\'ı otomatik olarak ilerlet', 'pratikwp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Otomatik Oynatma Gecikmesi', 'pratikwp'); ?></th>
                        <td>
                            <input type="number" name="slider_autoplay_delay" value="<?php echo esc_attr(get_option('slider_autoplay_delay', 5000)); ?>" min="1000" max="10000" step="500" />
                            <p class="description"><?php esc_html_e('Milisaniye cinsinden gecikme süresi', 'pratikwp'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Yön Okları', 'pratikwp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="slider_show_arrows" value="1" <?php checked(get_option('slider_show_arrows', true)); ?> />
                                <?php esc_html_e('İleri/Geri oklarlını göster', 'pratikwp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Navigasyon Noktaları', 'pratikwp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="slider_show_dots" value="1" <?php checked(get_option('slider_show_dots', true)); ?> />
                                <?php esc_html_e('Alt navigasyon noktalarını göster', 'pratikwp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Geçiş Efekti', 'pratikwp'); ?></th>
                        <td>
                            <select name="slider_transition_effect">
                                <option value="slide" <?php selected(get_option('slider_transition_effect', 'slide'), 'slide'); ?>><?php esc_html_e('Kaydırma', 'pratikwp'); ?></option>
                                <option value="fade" <?php selected(get_option('slider_transition_effect', 'slide'), 'fade'); ?>><?php esc_html_e('Soldurma', 'pratikwp'); ?></option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Masaüstü Yükseklik', 'pratikwp'); ?></th>
                        <td>
                            <input type="number" name="slider_height_desktop" value="<?php echo esc_attr(get_option('slider_height_desktop', 500)); ?>" min="200" max="800" />
                            <p class="description"><?php esc_html_e('Piksel cinsinden slider yüksekliği', 'pratikwp'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Mobil Yükseklik', 'pratikwp'); ?></th>
                        <td>
                            <input type="number" name="slider_height_mobile" value="<?php echo esc_attr(get_option('slider_height_mobile', 300)); ?>" min="150" max="500" />
                            <p class="description"><?php esc_html_e('Piksel cinsinden mobil slider yüksekliği', 'pratikwp'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Get active slides count
     */
    public function get_active_slides_count() {
        $count = 0;
        for ($i = 1; $i <= $this->max_slides; $i++) {
            if (get_option("slider_active_$i", false)) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Get active slides data
     */
    public function get_active_slides() {
        $slides = [];
        
        for ($i = 1; $i <= $this->max_slides; $i++) {
            if (get_option("slider_active_$i", false)) {
                $slides[] = [
                    'id' => $i,
                    'desktop_image' => get_option("slider_desktop_$i"),
                    'mobile_image' => get_option("slider_mobile_$i"),
                    'title' => get_option("slider_yazi_$i"),
                    'description' => get_option("slider_aciklama_$i"),
                    'link' => get_option("slider_link_$i"),
                    'button_text' => get_option("slider_buton_text_$i"),
                    'order' => get_option("slider_order_$i", $i)
                ];
            }
        }
        
        // Sort by order
        usort($slides, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        return $slides;
    }

    /**
     * Slider shortcode
     */
    public function slider_shortcode($atts) {
        $atts = shortcode_atts([
            'id' => '',
            'class' => '',
            'autoplay' => get_option('slider_autoplay', true),
            'arrows' => get_option('slider_show_arrows', true),
            'dots' => get_option('slider_show_dots', true)
        ], $atts);
        
        $slides = $this->get_active_slides();
        
        if (empty($slides)) {
            return '';
        }
        
        ob_start();
        $this->render_slider($slides, $atts);
        return ob_get_clean();
    }

    /**
     * Render slider HTML
     */
    public function render_slider($slides, $args = []) {
        $slider_id = !empty($args['id']) ? $args['id'] : 'pratikwp-slider';
        $slider_class = 'pratikwp-slider carousel slide';
        if (!empty($args['class'])) {
            $slider_class .= ' ' . $args['class'];
        }
        
        $autoplay = isset($args['autoplay']) ? $args['autoplay'] : get_option('slider_autoplay', true);
        $show_arrows = isset($args['arrows']) ? $args['arrows'] : get_option('slider_show_arrows', true);
        $show_dots = isset($args['dots']) ? $args['dots'] : get_option('slider_show_dots', true);
        ?>
        <div id="<?php echo esc_attr($slider_id); ?>" class="<?php echo esc_attr($slider_class); ?>" data-bs-ride="<?php echo $autoplay ? 'carousel' : 'false'; ?>" data-bs-interval="<?php echo esc_attr(get_option('slider_autoplay_delay', 5000)); ?>">
            
            <?php if ($show_dots && count($slides) > 1): ?>
            <div class="carousel-indicators">
                <?php foreach ($slides as $index => $slide): ?>
                <button type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>" data-bs-slide-to="<?php echo $index; ?>" <?php echo $index === 0 ? 'class="active" aria-current="true"' : ''; ?> aria-label="<?php printf(__('Slide %d', 'pratikwp'), $index + 1); ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <div class="carousel-inner">
                <?php foreach ($slides as $index => $slide): 
                    $is_active = $index === 0;
                    $desktop_image = $slide['desktop_image'];
                    $mobile_image = $slide['mobile_image'] ?: $desktop_image;
                ?>
                <div class="carousel-item <?php echo $is_active ? 'active' : ''; ?>">
                    <?php if ($slide['link']): ?>
                    <a href="<?php echo esc_url($slide['link']); ?>" class="slider-link">
                    <?php endif; ?>
                    
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php echo esc_url($mobile_image); ?>">
                        <img src="<?php echo esc_url($desktop_image); ?>" class="d-block w-100 slider-image" alt="<?php echo esc_attr($slide['title']); ?>" loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>">
                    </picture>
                    
                    <?php if ($slide['link']): ?>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($slide['title'] || $slide['description']): ?>
                    <div class="carousel-caption d-none d-md-block">
                        <?php if ($slide['title']): ?>
                        <h5 class="slider-title"><?php echo esc_html($slide['title']); ?></h5>
                        <?php endif; ?>
                        
                        <?php if ($slide['description']): ?>
                        <p class="slider-description"><?php echo esc_html($slide['description']); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($slide['link']): ?>
                        <a href="<?php echo esc_url($slide['link']); ?>" class="btn btn-primary slider-button">
                            <?php echo esc_html($slide['button_text'] ?: __('Devamını Oku', 'pratikwp')); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if ($show_arrows && count($slides) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php esc_html_e('Önceki', 'pratikwp'); ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#<?php echo esc_attr($slider_id); ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php esc_html_e('Sonraki', 'pratikwp'); ?></span>
            </button>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Legacy function support
     */
    public function legacy_slider_function() {
        echo $this->slider_shortcode([]);
    }
}

// Legacy function support
function pratikwp_slider() {
    global $slider_settings;
    if ($slider_settings instanceof PratikWp_SliderSettings) {
        $slider_settings->legacy_slider_function();
    }
}