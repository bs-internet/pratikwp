<?php
/**
 * Theme Settings Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_ThemeSettings {
    
    private $option_group = 'pratikwp_theme_group';
    private $page_slug = 'pratikwp-settings';
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('wp_ajax_pratikwp_reset_settings', [$this, 'reset_settings']);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Tema Ayarları', 'pratikwp'),
            __('Tema Ayarları', 'pratikwp'),
            'manage_options',
            $this->page_slug,
            [$this, 'settings_page'],
            'dashicons-admin-generic',
            61
        );
        
        // Add submenus
        add_submenu_page(
            $this->page_slug,
            __('Genel Ayarlar', 'pratikwp'),
            __('Genel', 'pratikwp'),
            'manage_options',
            $this->page_slug,
            [$this, 'settings_page']
        );
        
        add_submenu_page(
            $this->page_slug,
            __('Firma Bilgileri', 'pratikwp'),
            __('Firma Bilgileri', 'pratikwp'),
            'manage_options',
            $this->page_slug . '-company',
            [$this, 'company_page']
        );
        
        add_submenu_page(
            $this->page_slug,
            __('Sosyal Medya', 'pratikwp'),
            __('Sosyal Medya', 'pratikwp'),
            'manage_options',
            $this->page_slug . '-social',
            [$this, 'social_page']
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        // Company information
        $company_fields = [
            'firma_adi', 'firma_slogan', 'firma_adres', 'firma_il', 'firma_ilce', 'firma_postakodu',
            'firma_tel1', 'firma_tel2', 'firma_gsm1', 'firma_gsm2', 'firma_email1', 'firma_email2',
            'firma_website', 'firma_fax', 'firma_vergi_no', 'firma_ticaret_no'
        ];
        
        foreach ($company_fields as $field) {
            register_setting($this->option_group, $field, [
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
                'default' => ''
            ]);
        }
        
        // Social media
        $social_fields = [
            'sosyal_facebook', 'sosyal_x', 'sosyal_linkedin', 'sosyal_youtube', 
            'sosyal_instagram', 'sosyal_tiktok', 'sosyal_pinterest', 'sosyal_whatsapp'
        ];
        
        foreach ($social_fields as $field) {
            register_setting($this->option_group, $field, [
                'type' => 'string',
                'sanitize_callback' => 'esc_url_raw',
                'default' => ''
            ]);
        }
        
        // General settings
        $general_fields = [
            'enable_breadcrumbs' => ['type' => 'boolean', 'default' => true],
            'enable_search_widget' => ['type' => 'boolean', 'default' => true],
            'enable_comments' => ['type' => 'boolean', 'default' => true],
            'posts_per_page_archive' => ['type' => 'integer', 'default' => 10],
            'excerpt_length' => ['type' => 'integer', 'default' => 30]
        ];
        
        foreach ($general_fields as $field => $args) {
            register_setting($this->option_group, $field, [
                'type' => $args['type'],
                'sanitize_callback' => $args['type'] === 'boolean' ? 'rest_sanitize_boolean' : 'absint',
                'default' => $args['default']
            ]);
        }
    }

    /**
     * Main settings page
     */
    public function settings_page() {
        ?>
        <div class="wrap pratikwp-admin-page">
            <h1><?php esc_html_e('Tema Ayarları', 'pratikwp'); ?></h1>
            
            <div class="pratikwp-admin-header">
                <div class="pratikwp-version">
                    <strong><?php esc_html_e('Sürüm:', 'pratikwp'); ?></strong> <?php echo PRATIKWP_VERSION; ?>
                </div>
            </div>
            
            <form method="post" action="options.php">
                <?php settings_fields($this->option_group); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Breadcrumb Göster', 'pratikwp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="enable_breadcrumbs" value="1" <?php checked(get_option('enable_breadcrumbs', true)); ?> />
                                <?php esc_html_e('Breadcrumb navigasyonunu etkinleştir', 'pratikwp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Arama Widget', 'pratikwp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="enable_search_widget" value="1" <?php checked(get_option('enable_search_widget', true)); ?> />
                                <?php esc_html_e('Arama widget\'ını etkinleştir', 'pratikwp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Yorumlar', 'pratikwp'); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="enable_comments" value="1" <?php checked(get_option('enable_comments', true)); ?> />
                                <?php esc_html_e('Yorum sistemini etkinleştir', 'pratikwp'); ?>
                            </label>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Arşiv Sayfa Yazı Sayısı', 'pratikwp'); ?></th>
                        <td>
                            <input type="number" name="posts_per_page_archive" value="<?php echo esc_attr(get_option('posts_per_page_archive', 10)); ?>" min="1" max="50" />
                            <p class="description"><?php esc_html_e('Arşiv sayfalarında gösterilecek yazı sayısı', 'pratikwp'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row"><?php esc_html_e('Özet Uzunluğu', 'pratikwp'); ?></th>
                        <td>
                            <input type="number" name="excerpt_length" value="<?php echo esc_attr(get_option('excerpt_length', 30)); ?>" min="10" max="100" />
                            <p class="description"><?php esc_html_e('Yazı özetlerinde gösterilecek kelime sayısı', 'pratikwp'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Company information page
     */
    public function company_page() {
        ?>
        <div class="wrap pratikwp-admin-page">
            <h1><?php esc_html_e('Firma Bilgileri', 'pratikwp'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields($this->option_group); ?>
                
                <table class="form-table">
                    <?php
                    $company_fields = [
                        'firma_adi' => __('Firma Adı', 'pratikwp'),
                        'firma_slogan' => __('Slogan', 'pratikwp'),
                        'firma_adres' => __('Adres', 'pratikwp'),
                        'firma_il' => __('İl', 'pratikwp'),
                        'firma_ilce' => __('İlçe', 'pratikwp'),
                        'firma_postakodu' => __('Posta Kodu', 'pratikwp'),
                        'firma_tel1' => __('Telefon 1', 'pratikwp'),
                        'firma_tel2' => __('Telefon 2', 'pratikwp'),
                        'firma_gsm1' => __('GSM 1', 'pratikwp'),
                        'firma_gsm2' => __('GSM 2', 'pratikwp'),
                        'firma_email1' => __('E-mail 1', 'pratikwp'),
                        'firma_email2' => __('E-mail 2', 'pratikwp'),
                        'firma_website' => __('Website', 'pratikwp'),
                        'firma_fax' => __('Fax', 'pratikwp'),
                        'firma_vergi_no' => __('Vergi No', 'pratikwp'),
                        'firma_ticaret_no' => __('Ticaret Sicil No', 'pratikwp')
                    ];
                    
                    foreach ($company_fields as $key => $label):
                        $input_type = 'text';
                        if (strpos($key, 'email') !== false) {
                            $input_type = 'email';
                        } elseif (strpos($key, 'website') !== false) {
                            $input_type = 'url';
                        }
                    ?>
                    <tr>
                        <th scope="row"><?php echo esc_html($label); ?></th>
                        <td>
                            <input type="<?php echo esc_attr($input_type); ?>" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_option($key)); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <div class="pratikwp-shortcodes">
                <h3><?php esc_html_e('Shortcode Kullanımı', 'pratikwp'); ?></h3>
                <p><?php esc_html_e('Firma bilgilerini sayfalarda göstermek için aşağıdaki shortcode\'ları kullanabilirsiniz:', 'pratikwp'); ?></p>
                <code>[firma key="firma_adi"]</code> - <?php esc_html_e('Firma adını gösterir', 'pratikwp'); ?><br>
                <code>[firma key="firma_tel1"]</code> - <?php esc_html_e('Telefon numarasını gösterir', 'pratikwp'); ?><br>
                <code>[firma key="firma_email1"]</code> - <?php esc_html_e('E-mail adresini gösterir', 'pratikwp'); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Social media page
     */
    public function social_page() {
        ?>
        <div class="wrap pratikwp-admin-page">
            <h1><?php esc_html_e('Sosyal Medya', 'pratikwp'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields($this->option_group); ?>
                
                <table class="form-table">
                    <?php
                    $social_fields = [
                        'sosyal_facebook' => ['label' => 'Facebook', 'placeholder' => 'https://facebook.com/username'],
                        'sosyal_x' => ['label' => 'X (Twitter)', 'placeholder' => 'https://x.com/username'],
                        'sosyal_linkedin' => ['label' => 'LinkedIn', 'placeholder' => 'https://linkedin.com/company/username'],
                        'sosyal_youtube' => ['label' => 'YouTube', 'placeholder' => 'https://youtube.com/channel/username'],
                        'sosyal_instagram' => ['label' => 'Instagram', 'placeholder' => 'https://instagram.com/username'],
                        'sosyal_tiktok' => ['label' => 'TikTok', 'placeholder' => 'https://tiktok.com/@username'],
                        'sosyal_pinterest' => ['label' => 'Pinterest', 'placeholder' => 'https://pinterest.com/username'],
                        'sosyal_whatsapp' => ['label' => 'WhatsApp', 'placeholder' => 'https://wa.me/905xxxxxxxxx']
                    ];
                    
                    foreach ($social_fields as $key => $data):
                    ?>
                    <tr>
                        <th scope="row"><?php echo esc_html($data['label']); ?></th>
                        <td>
                            <input type="url" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_option($key)); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <div class="pratikwp-shortcodes">
                <h3><?php esc_html_e('Shortcode Kullanımı', 'pratikwp'); ?></h3>
                <p><?php esc_html_e('Sosyal medya linklerini sayfalarda göstermek için aşağıdaki shortcode\'ları kullanabilirsiniz:', 'pratikwp'); ?></p>
                <code>[sosyal key="sosyal_facebook"]</code> - <?php esc_html_e('Facebook linkini gösterir', 'pratikwp'); ?><br>
                <code>[sosyal key="sosyal_instagram"]</code> - <?php esc_html_e('Instagram linkini gösterir', 'pratikwp'); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Reset settings via AJAX
     */
    public function reset_settings() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Yetkiniz yok.', 'pratikwp'));
        }
        
        // Get all registered settings
        $all_settings = [
            'firma_adi', 'firma_slogan', 'firma_adres', 'firma_il', 'firma_ilce', 'firma_postakodu',
            'firma_tel1', 'firma_tel2', 'firma_gsm1', 'firma_gsm2', 'firma_email1', 'firma_email2',
            'sosyal_facebook', 'sosyal_x', 'sosyal_linkedin', 'sosyal_youtube', 'sosyal_instagram'
        ];
        
        foreach ($all_settings as $setting) {
            delete_option($setting);
        }
        
        wp_send_json_success(__('Ayarlar başarıyla sıfırlandı.', 'pratikwp'));
    }
}