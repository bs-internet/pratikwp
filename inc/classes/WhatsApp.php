<?php
/**
 * WhatsApp Button Class
 *
 * @package PratikWp
 * @version 1.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_WhatsApp {
    
    private $option_group = 'pratikwp_whatsapp_options';
    private $option_name = 'pratikwp_whatsapp_settings';

    public function __construct() {
        add_action('wp_footer', [$this, 'render_whatsapp_button']);
        add_action('admin_init', [$this, 'register_whatsapp_settings']);
        add_action('admin_footer', [$this, 'add_preview_script']);
    }

    /**
     * Register WhatsApp settings using the Settings API.
     */
    public function register_whatsapp_settings() {
        register_setting($this->option_group, $this->option_name, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_whatsapp_settings'],
            'default' => $this->get_default_settings()
        ]);
    }
    
    /**
     * Get default settings.
     */
    private function get_default_settings() {
        return [
            'enable' => 0,
            'phone' => '',
            'position_horizontal' => 'right',
            'position_horizontal_value' => 20,
            'position_vertical_value' => 20,
            'time_enable' => 0,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'display_style' => 'icon_only',
            'button_text' => __('İletişime Geçin', 'pratikwp'),
        ];
    }

    /**
     * Sanitize WhatsApp settings.
     */
    public function sanitize_whatsapp_settings($settings) {
        $defaults = $this->get_default_settings();
        return [
            'enable' => isset($settings['enable']) ? 1 : 0,
            'phone' => sanitize_text_field($settings['phone'] ?? ''),
            'position_horizontal' => sanitize_key($settings['position_horizontal'] ?? $defaults['position_horizontal']),
            'position_horizontal_value' => absint($settings['position_horizontal_value'] ?? $defaults['position_horizontal_value']),
            'position_vertical_value' => absint($settings['position_vertical_value'] ?? $defaults['position_vertical_value']),
            'time_enable' => isset($settings['time_enable']) ? 1 : 0,
            'start_time' => sanitize_text_field($settings['start_time'] ?? $defaults['start_time']),
            'end_time' => sanitize_text_field($settings['end_time'] ?? $defaults['end_time']),
            'display_style' => sanitize_key($settings['display_style'] ?? $defaults['display_style']),
            'button_text' => sanitize_text_field($settings['button_text'] ?? $defaults['button_text']),
        ];
    }

    /**
     * Render the WhatsApp button on the front-end.
     */
    public function render_whatsapp_button() {
        $settings = get_option($this->option_name, $this->get_default_settings());
        
        if (empty($settings['enable']) || empty($settings['phone']) || is_admin()) {
            return;
        }
        
        if (!empty($settings['time_enable']) && !$this->is_time_active($settings)) {
            return;
        }

        $this->enqueue_whatsapp_assets();
        
        $horizontal = $settings['position_horizontal'];
        $horizontal_value = $settings['position_horizontal_value'];
        $vertical_value = $settings['position_vertical_value'];
        $phone = $this->clean_phone_number($settings['phone']);
        $whatsapp_url = 'https://wa.me/' . $phone;
        $display_style = $settings['display_style'];
        $button_text = $settings['button_text'];

        $wrapper_classes = [
            'pratikwp-whatsapp-button',
            'style-' . esc_attr($display_style),
            'position-' . esc_attr($horizontal)
        ];
        ?>
        <div id="pratikwp-whatsapp" class="<?php echo implode(' ', $wrapper_classes); ?>" style="<?php echo esc_attr($horizontal); ?>: <?php echo esc_attr($horizontal_value); ?>px; bottom: <?php echo esc_attr($vertical_value); ?>px;">
            <a href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener noreferrer" class="pratikwp-whatsapp-link" title="<?php esc_attr_e('WhatsApp İletişim', 'pratikwp'); ?>" aria-label="<?php esc_attr_e('WhatsApp İletişim', 'pratikwp'); ?>">
                <?php
                $icon_html = '<div class="pratikwp-whatsapp-icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="currentColor"><path d="M14 0C6.28 0 0 6.28 0 14s6.28 14 14 14 14-6.28 14-14S21.72 0 14 0zm7.56 18.92c-.28.8-1.4 1.4-2.24 1.68-.56.14-1.12.28-3.36-.28-2.24-.56-4.76-2.24-6.44-4.2-1.96-2.24-2.8-4.48-2.94-5.6-.14-1.12.14-2.1.7-2.8.56-.7 1.26-1.12 2.1-1.12h.56c.42 0 .7.14.84.42.14.28.28.7.42 1.12.28.84.7 2.38.84 2.66.14.28.14.56 0 .84-.14.28-.28.42-.56.7-.28.28-.42.42-.56.7-.14.28-.14.56 0 .84.28.56 1.12 2.1 2.52 3.22 1.4 1.12 2.66 1.54 3.22 1.68.28.14.56.14.84 0 .28-.14.56-.28.84-.56.28-.28.56-.42.84-.56.28-.14.56-.14.84 0l2.52 1.26c.28.14.56.28.7.42.14.28.14.7 0 1.12z"/></svg></div>';
                $text_html = '<span class="pratikwp-whatsapp-text">' . esc_html($button_text) . '</span>';
                
                if ($display_style === 'icon_and_text') {
                    if ($horizontal === 'right') {
                        echo $text_html . $icon_html;
                    } else {
                        echo $icon_html . $text_html;
                    }
                } else {
                    echo $icon_html;
                }
                ?>
            </a>
        </div>
        <?php
    }

    /**
     * Enqueue WhatsApp assets conditionally.
     */
    public function enqueue_whatsapp_assets() {
        wp_enqueue_style('pratikwp-whatsapp', PRATIKWP_ASSETS . '/css/whatsapp.css', [], PRATIKWP_VERSION);
    }

    private function is_time_active($settings) {
        $tz = new DateTimeZone(wp_timezone_string());
        $now = new DateTime('now', $tz);
        $start = DateTime::createFromFormat('H:i', $settings['start_time'], $tz);
        $end = DateTime::createFromFormat('H:i', $settings['end_time'], $tz);

        if ($start > $end) {
            if ($now >= $start || $now < $end) {
                return true;
            }
        } else {
            if ($now >= $start && $now < $end) {
                return true;
            }
        }
        return false;
    }
    
    private function clean_phone_number($phone) {
        return preg_replace('/[^0-9]/', '', $phone);
    }

    /**
     * WhatsApp settings page - Redesigned with Settings API and modern layout.
     */
    public static function whatsapp_settings_page() {
        $instance = new self();
        $settings = get_option($instance->option_name, $instance->get_default_settings());
        ?>
        <div class="wrap pratikwp-admin-wrap">
            <h1><?php esc_html_e('WhatsApp Ayarları', 'pratikwp'); ?></h1>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <form method="post" action="options.php">
                    <?php settings_fields('pratikwp_whatsapp_options'); ?>
                    
                    <div class="pratikwp-card">
                        <div class="card-header"><h3><?php esc_html_e('Genel Ayarlar', 'pratikwp'); ?></h3></div>
                        <div class="card-body">
                            <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label"><label><?php esc_html_e('Durum', 'pratikwp'); ?></label></div>
                                <div class="pratikwp-setting-field">
                                    <label>
                                        <input type="checkbox" name="pratikwp_whatsapp_settings[enable]" value="1" <?php checked($settings['enable'], 1); ?> />
                                        <?php esc_html_e('WhatsApp butonu sitede aktif olsun.', 'pratikwp'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label">
                                    <label for="whatsapp_phone"><?php esc_html_e('Telefon Numarası', 'pratikwp'); ?></label>
                                    <p class="description"><?php esc_html_e('Ülke kodu ile birlikte yazın.', 'pratikwp'); ?></p>
                                </div>
                                <div class="pratikwp-setting-field">
                                    <input type="text" id="whatsapp_phone" name="pratikwp_whatsapp_settings[phone]" value="<?php echo esc_attr($settings['phone']); ?>" class="regular-text" placeholder="+905xxxxxxxxx" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pratikwp-card">
                        <div class="card-header"><h3><?php esc_html_e('Görünüm ve Konum', 'pratikwp'); ?></h3></div>
                        <div class="card-body">
                             <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label"><label><?php esc_html_e('Buton Stili', 'pratikwp'); ?></label></div>
                                <div class="pratikwp-setting-field">
                                    <fieldset>
                                        <label style="margin-right: 15px;">
                                            <input type="radio" name="pratikwp_whatsapp_settings[display_style]" value="icon_only" <?php checked($settings['display_style'], 'icon_only'); ?>>
                                            <?php esc_html_e('Sadece İkon', 'pratikwp'); ?>
                                        </label>
                                        <label>
                                            <input type="radio" name="pratikwp_whatsapp_settings[display_style]" value="icon_and_text" <?php checked($settings['display_style'], 'icon_and_text'); ?>>
                                            <?php esc_html_e('İkon ve Yazı', 'pratikwp'); ?>
                                        </label>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="pratikwp-setting-row" id="button-text-row" style="<?php echo $settings['display_style'] === 'icon_and_text' ? '' : 'display: none;'; ?>">
                                <div class="pratikwp-setting-label">
                                    <label for="button_text"><?php esc_html_e('Buton Yazısı', 'pratikwp'); ?></label>
                                </div>
                                <div class="pratikwp-setting-field">
                                    <input type="text" id="button_text" name="pratikwp_whatsapp_settings[button_text]" value="<?php echo esc_attr($settings['button_text']); ?>" class="regular-text" />
                                </div>
                            </div>
                            <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label"><label for="position_horizontal"><?php esc_html_e('Yatay Konum', 'pratikwp'); ?></label></div>
                                <div class="pratikwp-setting-field">
                                    <select id="position_horizontal" name="pratikwp_whatsapp_settings[position_horizontal]">
                                        <option value="left" <?php selected($settings['position_horizontal'], 'left'); ?>><?php esc_html_e('Sol', 'pratikwp'); ?></option>
                                        <option value="right" <?php selected($settings['position_horizontal'], 'right'); ?>><?php esc_html_e('Sağ', 'pratikwp'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label">
                                    <label for="position_horizontal_value"><?php esc_html_e('Kenar Boşlukları (px)', 'pratikwp'); ?></label>
                                    <p class="description"><?php esc_html_e('Ekranın kenarlarından uzaklık.', 'pratikwp'); ?></p>
                                </div>
                                <div class="pratikwp-setting-field" style="display: flex; gap: 15px;">
                                    <input type="number" id="position_horizontal_value" name="pratikwp_whatsapp_settings[position_horizontal_value]" value="<?php echo esc_attr($settings['position_horizontal_value']); ?>" min="0" max="200" class="small-text" placeholder="<?php esc_attr_e('Yatay', 'pratikwp'); ?>" />
                                    <input type="number" id="position_vertical_value" name="pratikwp_whatsapp_settings[position_vertical_value]" value="<?php echo esc_attr($settings['position_vertical_value']); ?>" min="0" max="200" class="small-text" placeholder="<?php esc_attr_e('Dikey', 'pratikwp'); ?>" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pratikwp-card">
                        <div class="card-header"><h3><?php esc_html_e('Zamanlama', 'pratikwp'); ?></h3></div>
                        <div class="card-body">
                            <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label"><label><?php esc_html_e('Aktif Saatler', 'pratikwp'); ?></label></div>
                                <div class="pratikwp-setting-field">
                                    <label>
                                        <input type="checkbox" name="pratikwp_whatsapp_settings[time_enable]" value="1" <?php checked($settings['time_enable'], 1); ?> />
                                        <?php esc_html_e('Sadece belirtilen saatlerde görünsün.', 'pratikwp'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="pratikwp-setting-row">
                                <div class="pratikwp-setting-label"><label for="start_time"><?php esc_html_e('Başlangıç / Bitiş Saati', 'pratikwp'); ?></label></div>
                                <div class="pratikwp-setting-field" style="display: flex; gap: 15px;">
                                    <input type="time" id="start_time" name="pratikwp_whatsapp_settings[start_time]" value="<?php echo esc_attr($settings['start_time']); ?>" />
                                    <input type="time" name="pratikwp_whatsapp_settings[end_time]" value="<?php echo esc_attr($settings['end_time']); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <?php submit_button(__('Ayarları Kaydet', 'pratikwp'), 'primary', 'submit', false); ?>
                        </div>
                    </div>
                </form>
                
                <div class="pratikwp-card" id="pratikwp-whatsapp-preview-wrapper" style="position: sticky; top: 50px;">
                    <div class="card-header"><h3><?php esc_html_e('Önizleme', 'pratikwp'); ?></h3></div>
                    <div class="card-body">
                        <div id="pratikwp-whatsapp-preview-area">
                            <div id="pratikwp-whatsapp-preview" class="pratikwp-whatsapp-button">
                                <a href="#" onclick="return false;" class="pratikwp-whatsapp-link">
                                    </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <style>
            #pratikwp-whatsapp-preview-area {
                position: relative;
                height: 200px;
                background: #f0f0f1;
                border: 1px dashed #ccc;
                border-radius: 4px;
                overflow: hidden;
            }
            #pratikwp-whatsapp-preview {
                position: absolute;
                transition: all 0.3s ease;
            }
        </style>
        <?php
    }
    
    /**
     * Add JavaScript for live preview in admin settings page.
     */
    public function add_preview_script() {
        // Sadece kendi ayar sayfamızda bu script'i yükle
        $screen = get_current_screen();
        if (!$screen || strpos($screen->id, 'pratikwp-whatsapp') === false) {
            return;
        }
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const preview = document.getElementById('pratikwp-whatsapp-preview');
            const previewLink = preview.querySelector('a');
            const form = document.querySelector('form[action="options.php"]');
            
            const inputs = {
                displayStyle: form.querySelectorAll('input[name="pratikwp_whatsapp_settings[display_style]"]'),
                buttonText: form.querySelector('#button_text'),
                horizontalPos: form.querySelector('#position_horizontal'),
                horizontalVal: form.querySelector('#position_horizontal_value'),
                verticalVal: form.querySelector('#position_vertical_value')
            };

            const iconHtml = `<div class="pratikwp-whatsapp-icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="currentColor"><path d="M14 0C6.28 0 0 6.28 0 14s6.28 14 14 14 14-6.28 14-14S21.72 0 14 0zm7.56 18.92c-.28.8-1.4 1.4-2.24 1.68-.56.14-1.12.28-3.36-.28-2.24-.56-4.76-2.24-6.44-4.2-1.96-2.24-2.8-4.48-2.94-5.6-.14-1.12.14-2.1.7-2.8.56-.7 1.26-1.12 2.1-1.12h.56c.42 0 .7.14.84.42.14.28.28.7.42 1.12.28.84.7 2.38.84 2.66.14.28.14.56 0 .84-.14.28-.28.42-.56.7-.28.28-.42.42-.56.7-.14.28-.14.56 0 .84.28.56 1.12 2.1 2.52 3.22 1.4 1.12 2.66 1.54 3.22 1.68.28.14.56.14.84 0 .28-.14.56-.28.84-.56.28-.28.56-.42.84-.56.28-.14.56-.14.84 0l2.52 1.26c.28.14.56.28.7.42.14.28.14.7 0 1.12z"/></svg></div>`;

            function updatePreview() {
                // Style and position
                const hPos = inputs.horizontalPos.value;
                const hVal = inputs.horizontalVal.value + 'px';
                const vVal = inputs.verticalVal.value + 'px';

                preview.style.left = hPos === 'left' ? hVal : 'auto';
                preview.style.right = hPos === 'right' ? hVal : 'auto';
                preview.style.bottom = vVal;
                
                // Content
                const displayStyle = document.querySelector('input[name="pratikwp_whatsapp_settings[display_style]"]:checked').value;
                const buttonText = inputs.buttonText.value;
                const textHtml = `<span class="pratikwp-whatsapp-text">${buttonText}</span>`;
                
                // Toggle text input visibility
                document.getElementById('button-text-row').style.display = (displayStyle === 'icon_and_text') ? '' : 'none';

                preview.className = `pratikwp-whatsapp-button style-${displayStyle} position-${hPos}`;

                if (displayStyle === 'icon_and_text') {
                    if (hPos === 'right') {
                        previewLink.innerHTML = textHtml + iconHtml;
                    } else {
                        previewLink.innerHTML = iconHtml + textHtml;
                    }
                } else {
                    previewLink.innerHTML = iconHtml;
                }
            }

            // Initial call
            updatePreview();

            // Event listeners
            form.addEventListener('input', updatePreview);
            form.addEventListener('change', updatePreview);
        });
        </script>
        <?php
    }
}