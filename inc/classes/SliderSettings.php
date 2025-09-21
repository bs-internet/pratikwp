<?php
/**
 * Slider Settings Class
 *
 * @package PratikWp
 * @version 1.3.1 
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_SliderSettings {
    
    private string $option_group = 'pratikwp_slider_options';
    private string $settings_option_name = 'pratikwp_slider_settings';
    private int $max_slides = 6;
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_shortcode('pratikwp_slider', [$this, 'slider_shortcode']);
    }

    public function add_admin_menu(): void {
        add_submenu_page(
            'pratikwp-dashboard',
            __('Slider Yönetimi', 'pratikwp'),
            __('Slider Yönetimi', 'pratikwp'),
            'manage_options',
            'pratikwp-slider',
            [$this, 'slider_page_callback'] // İsim değişikliği, çakışmayı önler
        );
        add_submenu_page(
            'pratikwp-dashboard',
            __('Slider Ayarları', 'pratikwp'),
            __('Slider Ayarları', 'pratikwp'),
            'manage_options',
            'pratikwp-slider-settings',
            [$this, 'slider_settings_page']
        );
    }

    public function register_settings(): void {
        // Genel ayarlar için Settings API
        register_setting($this->option_group, $this->settings_option_name, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_settings'],
            'default' => [
                'autoplay'       => 1,
                'delay'          => 5000,
                'arrows'         => 1,
                'dots'           => 1,
                'effect'         => 'slide',
                'height_desktop' => 500, // Eklendi
                'height_mobile'  => 300, // Eklendi
            ]
        ]);

        // Tekil slayt verileri için (bunlar manuel olarak kaydedilecek)
        for ($i = 1; $i <= $this->max_slides; $i++) {
            register_setting("pratikwp_slider_group_$i", "pratikwp_slide_$i", [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitize_slide']
            ]);
        }
    }
    
    public function sanitize_settings(array $settings): array {
        return [
            'autoplay'       => isset($settings['autoplay']) ? 1 : 0,
            'delay'          => absint($settings['delay'] ?? 5000),
            'arrows'         => isset($settings['arrows']) ? 1 : 0,
            'dots'           => isset($settings['dots']) ? 1 : 0,
            'effect'         => sanitize_key($settings['effect'] ?? 'slide'),
            'height_desktop' => absint($settings['height_desktop'] ?? 500), // Eklendi
            'height_mobile'  => absint($settings['height_mobile'] ?? 300),  // Eklendi
        ];
    }
    
    public function sanitize_slide(array $slide): array {
        return [
            'desktop_image' => esc_url_raw($slide['desktop_image'] ?? ''),
            'mobile_image'  => esc_url_raw($slide['mobile_image'] ?? ''),
            'title'         => sanitize_text_field($slide['title'] ?? ''),
            'link'          => esc_url_raw($slide['link'] ?? ''),
            'button_text'   => sanitize_text_field($slide['button_text'] ?? ''),
        ];
    }

    public function enqueue_slider_assets(): void {
        wp_enqueue_style('pratikwp-slider', PRATIKWP_ASSETS . '/css/slider.css', [], PRATIKWP_VERSION);
        wp_enqueue_script('pratikwp-slider', PRATIKWP_ASSETS . '/js/slider.js', [], PRATIKWP_VERSION, true);

        // Slider ayarlarını PHP'den JS'e aktar (wp_localize_script kaldırıldı çünkü JS artık data attribute'lerinden okuyacak)
    }

    public function slider_page_callback(): void {
        // Medya kütüphanesi script'lerini bu sayfaya yükle
        wp_enqueue_media();
    
        $active_tab = isset($_GET['tab']) ? absint($_GET['tab']) : 1;
        if ($active_tab < 1 || $active_tab > $this->max_slides) { $active_tab = 1; }
    
        if (isset($_POST['submit']) && check_admin_referer("pratikwp_slider_group_{$active_tab}_nonce")) {
            $slide_data = [
                'desktop_image' => $_POST['desktop_image'] ?? '',
                'mobile_image'  => $_POST['mobile_image'] ?? '',
                'title'         => $_POST['title'] ?? '',
                'link'          => $_POST['link'] ?? '',
                'button_text'   => $_POST['button_text'] ?? '',
            ];
            update_option("pratikwp_slide_{$active_tab}", $this->sanitize_slide($slide_data));
            ?>
            <div class="pratikwp-alert pratikwp-alert-success"><strong><?php esc_html_e('Başarılı!', 'pratikwp'); ?></strong> <?php printf(__('Slider %d kaydedildi.', 'pratikwp'), $active_tab); ?></div>
            <?php
        }
        
        $slide = get_option("pratikwp_slide_{$active_tab}", []);
        ?>
        <div class="wrap pratikwp-admin-wrap">
            <h1><?php esc_html_e('Slider Yönetimi', 'pratikwp'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <?php for ($i = 1; $i <= $this->max_slides; $i++): 
                    $slide_data = get_option("pratikwp_slide_{$i}", []); $has_image = !empty($slide_data['desktop_image']); ?>
                    <a href="<?php echo esc_url(admin_url("admin.php?page=pratikwp-slider&tab={$i}")); ?>" class="nav-tab <?php if ($active_tab === $i) echo 'nav-tab-active'; ?>">
                        <?php printf(__('Slider %d', 'pratikwp'), $i); ?>
                        <?php if ($has_image): ?><span class="pratikwp-badge pratikwp-badge-success">●</span><?php endif; ?>
                    </a>
                <?php endfor; ?>
            </h2>
            <form method="post" action="">
                <?php wp_nonce_field("pratikwp_slider_group_{$active_tab}_nonce"); ?>
                <div class="pratikwp-card">
                    <div class="card-header"><h3><?php printf(__('Slider %d İçeriği', 'pratikwp'), $active_tab); ?></h3></div>
                    <div class="card-body">
                        
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label><?php esc_html_e('Masaüstü Görsel', 'pratikwp'); ?></label><p class="description"><?php esc_html_e('Önerilen: 1920x500px', 'pratikwp'); ?></p></div>
                            <div class="pratikwp-setting-field">
                                <div class="pratikwp-media-uploader"><input type="url" id="desktop_image_url" name="desktop_image" value="<?php echo esc_attr($slide['desktop_image'] ?? ''); ?>" class="regular-text" /><button type="button" class="button pratikwp-media-button" data-target-input="desktop_image_url" data-target-preview="desktop_image_preview"><?php esc_html_e('Medya Seç', 'pratikwp'); ?></button></div>
                                <div id="desktop_image_preview" class="pratikwp-media-preview"><?php if (!empty($slide['desktop_image'])): ?><img src="<?php echo esc_url($slide['desktop_image']); ?>" alt="<?php esc_attr_e('Önizleme', 'pratikwp'); ?>"><?php endif; ?></div>
                            </div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label><?php esc_html_e('Mobil Görsel', 'pratikwp'); ?></label><p class="description"><?php esc_html_e('Boşsa masaüstü kullanılır.', 'pratikwp'); ?></p></div>
                            <div class="pratikwp-setting-field">
                                <div class="pratikwp-media-uploader"><input type="url" id="mobile_image_url" name="mobile_image" value="<?php echo esc_attr($slide['mobile_image'] ?? ''); ?>" class="regular-text" /><button type="button" class="button pratikwp-media-button" data-target-input="mobile_image_url" data-target-preview="mobile_image_preview"><?php esc_html_e('Medya Seç', 'pratikwp'); ?></button></div>
                                <div id="mobile_image_preview" class="pratikwp-media-preview"><?php if (!empty($slide['mobile_image'])): ?><img src="<?php echo esc_url($slide['mobile_image']); ?>" alt="<?php esc_attr_e('Önizleme', 'pratikwp'); ?>"><?php endif; ?></div>
                            </div>
                        </div>
                        
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Başlık', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="text" name="title" value="<?php echo esc_attr($slide['title'] ?? ''); ?>" class="regular-text" /></div></div>
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Link URL', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="url" name="link" value="<?php echo esc_attr($slide['link'] ?? ''); ?>" class="regular-text" placeholder="https://" /></div></div>
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Buton Metni', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="text" name="button_text" value="<?php echo esc_attr($slide['button_text'] ?? ''); ?>" class="regular-text" placeholder="<?php esc_attr_e('Devamını Oku', 'pratikwp'); ?>" /></div></div>

                    </div>
                    <div class="card-footer"><?php submit_button(__('Slider\'ı Kaydet', 'pratikwp'), 'primary', 'submit', false); ?></div>
                </div>
            </form>
        </div>
        <?php
        $this->add_media_uploader_script();
    }
    
    private function add_media_uploader_script(): void { ?>
        <script>jQuery(document).ready(function($){'use strict';$(document).on('click','.pratikwp-media-button',function(e){e.preventDefault();var button=$(this);var inputTarget=button.data('target-input');var previewTarget=button.data('target-preview');var inputField=$('#'+inputTarget);var previewContainer=$('#'+previewTarget);var frame=wp.media({title:'<?php esc_attr_e("Görsel Seç veya Yükle","pratikwp");?>',button:{text:'<?php esc_attr_e("Görseli Kullan","pratikwp");?>'},multiple:false});frame.on('select',function(){var attachment=frame.state().get('selection').first().toJSON();inputField.val(attachment.url);previewContainer.html('<img src="'+attachment.url+'" alt="<?php esc_attr_e("Önizleme","pratikwp");?>">');});frame.open();});});</script>
        <style>.pratikwp-media-uploader{display:flex;gap:10px;}.pratikwp-media-uploader .regular-text{flex-grow:1;}.pratikwp-media-preview{margin-top:10px;}.pratikwp-media-preview img{max-width:300px;height:auto;border:1px solid #ddd;border-radius:4px;padding:3px;}</style>
    <?php }

    public function slider_settings_page(): void {
        $settings = get_option($this->settings_option_name, []);
        ?>
        <div class="wrap pratikwp-admin-wrap">
            <h1><?php esc_html_e('Slider Genel Ayarları', 'pratikwp'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields($this->option_group); ?>
                <div class="pratikwp-card">
                    <div class="card-header"><h3><?php esc_html_e('Davranış Ayarları', 'pratikwp'); ?></h3></div>
                    <div class="card-body">
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Otomatik Oynatma', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="checkbox" name="<?php echo $this->settings_option_name; ?>[autoplay]" value="1" <?php checked($settings['autoplay'] ?? 1); ?> /></div></div>
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Gecikme Süresi (ms)', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="number" name="<?php echo $this->settings_option_name; ?>[delay]" value="<?php echo esc_attr($settings['delay'] ?? 5000); ?>" class="small-text" /></div></div>
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Geçiş Efekti', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><select name="<?php echo $this->settings_option_name; ?>[effect]"><option value="slide" <?php selected($settings['effect'] ?? 'slide', 'slide'); ?>><?php esc_html_e('Kaydırma', 'pratikwp'); ?></option><option value="fade" <?php selected($settings['effect'] ?? 'slide', 'fade'); ?>><?php esc_html_e('Soldurma', 'pratikwp'); ?></option></select></div></div>
                    </div>
                </div>
                <div class="pratikwp-card">
                    <div class="card-header"><h3><?php esc_html_e('Kontrol Elemanları', 'pratikwp'); ?></h3></div>
                    <div class="card-body">
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Yön Okları', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="checkbox" name="<?php echo $this->settings_option_name; ?>[arrows]" value="1" <?php checked($settings['arrows'] ?? 1); ?> /></div></div>
                        <div class="pratikwp-setting-row"><div class="pratikwp-setting-label"><label><?php esc_html_e('Navigasyon Noktaları', 'pratikwp'); ?></label></div><div class="pratikwp-setting-field"><input type="checkbox" name="<?php echo $this->settings_option_name; ?>[dots]" value="1" <?php checked($settings['dots'] ?? 1); ?> /></div></div>
                    </div>
                </div>
                <div class="pratikwp-card">
                    <div class="card-header"><h3><?php esc_html_e('Görünüm Ayarları', 'pratikwp'); ?></h3></div>
                    <div class="card-body">
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label><?php esc_html_e('Masaüstü Yüksekliği (px)', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="number" name="<?php echo $this->settings_option_name; ?>[height_desktop]" value="<?php echo esc_attr($settings['height_desktop'] ?? 500); ?>" class="small-text" /></div>
                        </div>
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label><?php esc_html_e('Mobil Yüksekliği (px)', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="number" name="<?php echo $this->settings_option_name; ?>[height_mobile]" value="<?php echo esc_attr($settings['height_mobile'] ?? 300); ?>" class="small-text" /></div>
                        </div>
                    </div>
                </div>

                <div class="pratikwp-card">
                    <div class="card-footer" style="justify-content: flex-start;"><?php submit_button(__('Ayarları Kaydet', 'pratikwp'), 'primary', 'submit', false); ?></div>
                </div>
            </form>
        </div>
        <?php
    }

    public function get_active_slides(): array {
        $slides = [];
        for ($i = 1; $i <= $this->max_slides; $i++) {
            $slide_data = get_option("pratikwp_slide_{$i}", []);
            if (!empty($slide_data['desktop_image'])) {
                $slides[] = array_merge(['id' => $i], $slide_data);
            }
        }
        return $slides;
    }

    public function slider_shortcode($atts): string {
        $slides = $this->get_active_slides();
        if (empty($slides)) return '';
        $this->enqueue_slider_assets();
        ob_start();
        $this->render_slider($slides, shortcode_atts([], $atts));
        return ob_get_clean();
    }

    public function render_slider(array $slides, array $args = []): void {
        $default_settings = get_option($this->settings_option_name, []);
        $final_settings = wp_parse_args($args, $default_settings);

        $slider_id = 'pratikwp-slider-' . wp_unique_id();
        
        $slider_classes = ['pratik-slider-container'];
        if (!empty($final_settings['effect']) && $final_settings['effect'] === 'fade') {
            $slider_classes[] = 'is-fade';
        }
        
        $data_attrs = [
            'data-autoplay' => ($final_settings['autoplay'] ?? 1) ? 'true' : 'false',
            'data-delay'    => (int) ($final_settings['delay'] ?? 5000),
            'data-effect'   => esc_attr($final_settings['effect'] ?? 'slide'),
        ];
        
        $data_string = '';
        foreach ($data_attrs as $key => $value) {
            $data_string .= $key . '="' . $value . '" ';
        }
        
        // Yükseklik değerlerini CSS değişkeni olarak ekle
        $style_attr = sprintf(
            'style="--slider-height-desktop: %dpx; --slider-height-mobile: %dpx;"',
            absint($final_settings['height_desktop'] ?? 500),
            absint($final_settings['height_mobile'] ?? 300)
        );
        ?>
        <div id="<?php echo esc_attr($slider_id); ?>" class="<?php echo esc_attr(implode(' ', $slider_classes)); ?>" <?php echo trim($data_string); ?> <?php echo $style_attr; ?>>
            
            <div class="pratik-slider-track">
                <?php foreach ($slides as $index => $slide): 
                    $is_active = $index === 0;
                    $desktop_image = $slide['desktop_image'];
                    $mobile_image = !empty($slide['mobile_image']) ? $slide['mobile_image'] : $desktop_image;
                ?>
                <div class="pratik-slider-slide<?php echo $is_active ? ' active' : ''; ?>">
                    <?php if (!empty($slide['link'])): ?><a href="<?php echo esc_url($slide['link']); ?>" class="pratik-slider-link" aria-label="<?php echo esc_attr($slide['title']); ?>"></a><?php endif; ?>
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php echo esc_url($mobile_image); ?>">
                        <img src="<?php echo esc_url($desktop_image); ?>" class="slider-image" alt="<?php echo esc_attr($slide['title']); ?>" loading="<?php echo $is_active ? 'eager' : 'lazy'; ?>">
                    </picture>
                    
                    <?php if (!empty($slide['title'])): ?>
                    <div class="pratik-slider-caption">
                        <h5 class="slider-title"><?php echo esc_html($slide['title']); ?></h5>
                        <?php if (!empty($slide['link'])): ?>
                        <span class="btn btn-primary slider-button">
                            <?php echo esc_html(!empty($slide['button_text']) ? $slide['button_text'] : __('Devamını Oku', 'pratikwp')); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if (($final_settings['dots'] ?? 1) && count($slides) > 1): ?>
            <div class="pratik-slider-dots">
                <?php foreach ($slides as $index => $slide): ?>
                <button type="button" class="pratik-slider-dot<?php echo $index === 0 ? ' active' : ''; ?>" aria-label="<?php printf(__('Slide %d', 'pratikwp'), $index + 1); ?>"></button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <?php if (($final_settings['arrows'] ?? 1) && count($slides) > 1): ?>
            <button class="pratik-slider-nav is-prev" type="button" aria-label="<?php esc_attr_e('Önceki Slayt', 'pratikwp'); ?>">
                <span class="pratik-slider-nav-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php esc_html_e('Önceki', 'pratikwp'); ?></span>
            </button>
            <button class="pratik-slider-nav is-next" type="button" aria-label="<?php esc_attr_e('Sonraki Slayt', 'pratikwp'); ?>">
                <span class="pratik-slider-nav-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?php esc_html_e('Sonraki', 'pratikwp'); ?></span>
            </button>
            <?php endif; ?>

        </div>
        <?php
    }
}

function pratikwp_slider() {
    global $slider_settings;
    if ($slider_settings instanceof PratikWp_SliderSettings) {
        echo $slider_settings->slider_shortcode([]);
    }
}