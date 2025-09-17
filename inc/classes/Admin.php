<?php
/**
 * Admin Interface Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Admin {
    
    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_pages']);
        add_action('admin_init', [$this, 'init_admin']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('admin_notices', [$this, 'admin_notices']);
        add_action('admin_bar_menu', [$this, 'add_admin_bar_menu'], 999);
        
        // Dashboard widgets
        add_action('wp_dashboard_setup', [$this, 'add_dashboard_widgets']);
        
        // Meta boxes
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post', [$this, 'save_meta_boxes']);
        
        // AJAX handlers
        add_action('wp_ajax_pratikwp_dismiss_notice', [$this, 'dismiss_notice']);
        add_action('wp_ajax_pratikwp_system_info', [$this, 'get_system_info']);
        add_action('wp_ajax_pratikwp_performance_check', [$this, 'performance_check']);
        
        // Theme activation/deactivation
        add_action('after_switch_theme', [$this, 'theme_activation']);
        add_action('switch_theme', [$this, 'theme_deactivation']);
    }

    /**
     * Add admin pages
     */
    public function add_admin_pages() {
        // Main dashboard page
        add_menu_page(
            __('PratikWp Dashboard', 'pratikwp'),
            __('PratikWp', 'pratikwp'),
            'manage_options',
            'pratikwp-dashboard',
            [$this, 'dashboard_page'],
            'dashicons-admin-appearance',
            3
        );
        
        // System info page
        add_submenu_page(
            'pratikwp-dashboard',
            __('Sistem Bilgileri', 'pratikwp'),
            __('Sistem Bilgileri', 'pratikwp'),
            'manage_options',
            'pratikwp-system-info',
            [$this, 'system_info_page']
        );
        
        // Demo import page
        add_submenu_page(
            'pratikwp-dashboard',
            __('Demo İçe Aktarma', 'pratikwp'),
            __('Demo İçe Aktarma', 'pratikwp'),
            'manage_options',
            'pratikwp-demo-import',
            [$this, 'demo_import_page']
        );
        
        // Getting started page (only show for new installations)
        if (get_option('pratikwp_show_welcome', true)) {
            add_submenu_page(
                'pratikwp-dashboard',
                __('Başlangıç Rehberi', 'pratikwp'),
                __('Başlangıç', 'pratikwp'),
                'manage_options',
                'pratikwp-getting-started',
                [$this, 'getting_started_page']
            );
        }
    }

    /**
     * Initialize admin
     */
    public function init_admin() {
        // Register settings
        register_setting('pratikwp_admin_options', 'pratikwp_dashboard_settings');
        
        // Check if theme needs updates
        $this->check_theme_updates();
        
        // Setup admin notices
        $this->setup_admin_notices();
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        // Only load on PratikWp admin pages
        if (strpos($hook, 'pratikwp') === false) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_script('wp-color-picker');
        
        wp_enqueue_style(
            'pratikwp-admin-style',
            PRATIKWP_ASSETS . '/css/admin.css',
            ['wp-color-picker'],
            PRATIKWP_VERSION
        );
        
        wp_enqueue_script(
            'pratikwp-admin-script',
            PRATIKWP_ASSETS . '/js/admin.js',
            ['jquery', 'wp-color-picker', 'media-upload'],
            PRATIKWP_VERSION,
            true
        );
        
        wp_localize_script('pratikwp-admin-script', 'pratikwpAdmin', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pratikwp_admin_nonce'),
            'strings' => [
                'confirm_demo_import' => __('Demo içeriği mevcut içeriğin üzerine yazacak. Devam etmek istediğinizden emin misiniz?', 'pratikwp'),
                'importing' => __('İçe aktarılıyor...', 'pratikwp'),
                'import_success' => __('Demo içeriği başarıyla içe aktarıldı!', 'pratikwp'),
                'import_error' => __('İçe aktarma sırasında bir hata oluştu.', 'pratikwp'),
                'copying_text' => __('Kopyalanıyor...', 'pratikwp'),
                'copied_text' => __('Kopyalandı!', 'pratikwp'),
            ]
        ]);
    }

    /**
     * Dashboard page
     */
    public function dashboard_page() {
        ?>
        <div class="wrap pratikwp-admin-page">
            <div class="pratikwp-admin-header">
                <div class="pratikwp-admin-header-content">
                    <h1><?php esc_html_e('PratikWp Dashboard', 'pratikwp'); ?></h1>
                    <p class="description"><?php esc_html_e('Tema yönetimi ve hızlı erişim paneli', 'pratikwp'); ?></p>
                </div>
                <div class="pratikwp-admin-header-actions">
                    <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                        <?php esc_html_e('Temayı Özelleştir', 'pratikwp'); ?>
                    </a>
                    <a href="<?php echo admin_url('admin.php?page=pratikwp-settings'); ?>" class="button">
                        <?php esc_html_e('Tema Ayarları', 'pratikwp'); ?>
                    </a>
                </div>
            </div>
            
            <div class="pratikwp-dashboard-grid">
                <!-- Quick Stats -->
                <div class="pratikwp-dashboard-card">
                    <h3><?php esc_html_e('Hızlı İstatistikler', 'pratikwp'); ?></h3>
                    <div class="pratikwp-stats-grid">
                        <div class="stat-item">
                            <div class="stat-number"><?php echo wp_count_posts()->publish; ?></div>
                            <div class="stat-label"><?php esc_html_e('Yayınlanan Yazı', 'pratikwp'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo wp_count_posts('page')->publish; ?></div>
                            <div class="stat-label"><?php esc_html_e('Sayfa', 'pratikwp'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo wp_count_comments()->approved; ?></div>
                            <div class="stat-label"><?php esc_html_e('Yorum', 'pratikwp'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?php echo PratikWp_Performance::get_performance_score(); ?>%</div>
                            <div class="stat-label"><?php esc_html_e('Performans Skoru', 'pratikwp'); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="pratikwp-dashboard-card">
                    <h3><?php esc_html_e('Hızlı İşlemler', 'pratikwp'); ?></h3>
                    <div class="pratikwp-quick-actions">
                        <a href="<?php echo admin_url('post-new.php'); ?>" class="quick-action">
                            <span class="dashicons dashicons-edit"></span>
                            <?php esc_html_e('Yeni Yazı', 'pratikwp'); ?>
                        </a>
                        <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="quick-action">
                            <span class="dashicons dashicons-admin-page"></span>
                            <?php esc_html_e('Yeni Sayfa', 'pratikwp'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=pratikwp-slider'); ?>" class="quick-action">
                            <span class="dashicons dashicons-images-alt2"></span>
                            <?php esc_html_e('Slider Yönetimi', 'pratikwp'); ?>
                        </a>
                        <a href="<?php echo admin_url('nav-menus.php'); ?>" class="quick-action">
                            <span class="dashicons dashicons-menu"></span>
                            <?php esc_html_e('Menü Düzenle', 'pratikwp'); ?>
                        </a>
                        <a href="<?php echo admin_url('widgets.php'); ?>" class="quick-action">
                            <span class="dashicons dashicons-admin-generic"></span>
                            <?php esc_html_e('Widget\'lar', 'pratikwp'); ?>
                        </a>
                        <a href="<?php echo admin_url('admin.php?page=pratikwp-demo-import'); ?>" class="quick-action">
                            <span class="dashicons dashicons-download"></span>
                            <?php esc_html_e('Demo İçe Aktar', 'pratikwp'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Theme Info -->
                <div class="pratikwp-dashboard-card">
                    <h3><?php esc_html_e('Tema Bilgileri', 'pratikwp'); ?></h3>
                    <div class="theme-info">
                        <div class="info-row">
                            <span class="info-label"><?php esc_html_e('Sürüm:', 'pratikwp'); ?></span>
                            <span class="info-value"><?php echo PRATIKWP_VERSION; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><?php esc_html_e('WordPress Sürümü:', 'pratikwp'); ?></span>
                            <span class="info-value"><?php echo get_bloginfo('version'); ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><?php esc_html_e('PHP Sürümü:', 'pratikwp'); ?></span>
                            <span class="info-value"><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label"><?php esc_html_e('Elementor:', 'pratikwp'); ?></span>
                            <span class="info-value">
                                <?php echo class_exists('\Elementor\Plugin') ? '✅ ' . __('Aktif', 'pratikwp') : '❌ ' . __('Pasif', 'pratikwp'); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="theme-actions mt-3">
                        <a href="<?php echo admin_url('admin.php?page=pratikwp-system-info'); ?>" class="button">
                            <?php esc_html_e('Detaylı Sistem Bilgileri', 'pratikwp'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Performance -->
                <div class="pratikwp-dashboard-card">
                    <h3><?php esc_html_e('Performans Önerileri', 'pratikwp'); ?></h3>
                    <div class="performance-info">
                        <div class="performance-score">
                            <div class="score-circle">
                                <span class="score-number"><?php echo PratikWp_Performance::get_performance_score(); ?>%</span>
                            </div>
                        </div>
                        
                        <div class="performance-recommendations">
                            <?php
                            $recommendations = PratikWp_Performance::get_optimization_recommendations();
                            if (!empty($recommendations)):
                            ?>
                                <ul>
                                    <?php foreach ($recommendations as $recommendation): ?>
                                    <li><?php echo esc_html($recommendation); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p class="success"><?php esc_html_e('Tüm performans optimizasyonları aktif!', 'pratikwp'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="performance-actions mt-3">
                        <a href="<?php echo admin_url('admin.php?page=pratikwp-settings-performance'); ?>" class="button">
                            <?php esc_html_e('Performans Ayarları', 'pratikwp'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * System info page
     */
    public function system_info_page() {
        $system_info = $this->get_system_info_data();
        ?>
        <div class="wrap pratikwp-admin-page">
            <h1><?php esc_html_e('Sistem Bilgileri', 'pratikwp'); ?></h1>
            
            <div class="pratikwp-system-info">
                <div class="system-info-actions">
                    <button type="button" id="copy-system-info" class="button button-primary">
                        <?php esc_html_e('Sistem Bilgilerini Kopyala', 'pratikwp'); ?>
                    </button>
                    <button type="button" id="download-system-info" class="button">
                        <?php esc_html_e('Dosya Olarak İndir', 'pratikwp'); ?>
                    </button>
                </div>
                
                <textarea id="system-info-textarea" readonly><?php echo esc_textarea($system_info); ?></textarea>
            </div>
        </div>
        <?php
    }

    /**
     * Demo import page
     */
    public function demo_import_page() {
        ?>
        <div class="wrap pratikwp-admin-page">
            <h1><?php esc_html_e('Demo İçe Aktarma', 'pratikwp'); ?></h1>
            
            <div class="pratikwp-demo-import">
                <div class="demo-import-warning">
                    <h3><?php esc_html_e('⚠️ Önemli Uyarı', 'pratikwp'); ?></h3>
                    <p><?php esc_html_e('Demo içeriği içe aktarmak mevcut içeriğinizin üzerine yazacaktır. Bu işlem geri alınamaz. Lütfen devam etmeden önce sitenizin yedeğini alın.', 'pratikwp'); ?></p>
                </div>
                
                <div class="demo-previews">
                    <div class="demo-preview">
                        <div class="demo-screenshot">
                            <img src="<?php echo PRATIKWP_ASSETS . '/images/demo-preview.jpg'; ?>" alt="Demo Preview" />
                        </div>
                        <div class="demo-info">
                            <h3><?php esc_html_e('Varsayılan Demo', 'pratikwp'); ?></h3>
                            <p><?php esc_html_e('İş ve kurumsal siteler için hazırlanmış demo içerik.', 'pratikwp'); ?></p>
                            <div class="demo-includes">
                                <ul>
                                    <li><?php esc_html_e('✅ Ana sayfa tasarımı', 'pratikwp'); ?></li>
                                    <li><?php esc_html_e('✅ Blog sayfaları', 'pratikwp'); ?></li>
                                    <li><?php esc_html_e('✅ İletişim sayfası', 'pratikwp'); ?></li>
                                    <li><?php esc_html_e('✅ Hakkımızda sayfası', 'pratikwp'); ?></li>
                                    <li><?php esc_html_e('✅ Menü yapısı', 'pratikwp'); ?></li>
                                    <li><?php esc_html_e('✅ Widget ayarları', 'pratikwp'); ?></li>
                                </ul>
                            </div>
                            <button type="button" class="button button-primary demo-import-btn" data-demo="default">
                                <?php esc_html_e('Demo İçe Aktar', 'pratikwp'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div id="import-progress" class="import-progress" style="display: none;">
                    <div class="progress-bar">
                        <div class="progress-fill"></div>
                    </div>
                    <div class="progress-text"><?php esc_html_e('İçe aktarılıyor...', 'pratikwp'); ?></div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Getting started page
     */
    public function getting_started_page() {
        ?>
        <div class="wrap pratikwp-admin-page">
            <div class="pratikwp-welcome-header">
                <h1><?php esc_html_e('PratikWp Temasına Hoş Geldiniz!', 'pratikwp'); ?></h1>
                <p class="about-text"><?php esc_html_e('Profesyonel web sitenizi oluşturmak için ihtiyacınız olan her şey burada.', 'pratikwp'); ?></p>
            </div>
            
            <div class="pratikwp-getting-started">
                <div class="getting-started-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('Demo İçeriği İçe Aktarın', 'pratikwp'); ?></h3>
                            <p><?php esc_html_e('Hızlı başlamak için hazır demo içeriğini kullanın.', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-demo-import'); ?>" class="button button-primary">
                                <?php esc_html_e('Demo İçe Aktar', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('Temayı Özelleştirin', 'pratikwp'); ?></h3>
                            <p><?php esc_html_e('Renkler, fontlar ve layout\'u sitenize uygun hale getirin.', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                                <?php esc_html_e('Özelleştirmeye Başla', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('Firma Bilgilerini Girin', 'pratikwp'); ?></h3>
                            <p><?php esc_html_e('İletişim bilgilerinizi ve sosyal medya hesaplarınızı ekleyin.', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-settings-company'); ?>" class="button button-primary">
                                <?php esc_html_e('Firma Bilgileri', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('Elementor ile Sayfalar Oluşturun', 'pratikwp'); ?></h3>
                            <p><?php esc_html_e('Sürükle-bırak editörü ile profesyonel sayfalar tasarlayın.', 'pratikwp'); ?></p>
                            <?php if (class_exists('\Elementor\Plugin')): ?>
                                <a href="<?php echo admin_url('post-new.php?post_type=page'); ?>" class="button button-primary">
                                    <?php esc_html_e('Yeni Sayfa Oluştur', 'pratikwp'); ?>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>" class="button">
                                    <?php esc_html_e('Elementor\'u Yükle', 'pratikwp'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="getting-started-resources">
                    <h3><?php esc_html_e('Yararlı Kaynaklar', 'pratikwp'); ?></h3>
                    <div class="resources-grid">
                        <div class="resource-item">
                            <h4><?php esc_html_e('📚 Dokümantasyon', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Detaylı kullanım kılavuzu ve örnekler', 'pratikwp'); ?></p>
                            <a href="#" class="button"><?php esc_html_e('Dokümanlara Git', 'pratikwp'); ?></a>
                        </div>
                        <div class="resource-item">
                            <h4><?php esc_html_e('🎥 Video Eğitimler', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Adım adım video rehberleri', 'pratikwp'); ?></p>
                            <a href="#" class="button"><?php esc_html_e('Videoları İzle', 'pratikwp'); ?></a>
                        </div>
                        <div class="resource-item">
                            <h4><?php esc_html_e('💬 Destek', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Teknik destek ve yardım', 'pratikwp'); ?></p>
                            <a href="#" class="button"><?php esc_html_e('Destek Al', 'pratikwp'); ?></a>
                        </div>
                    </div>
                </div>
                
                <div class="getting-started-footer">
                    <button type="button" class="button" onclick="pratikwpDismissWelcome()">
                        <?php esc_html_e('Bu sayfayı tekrar gösterme', 'pratikwp'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <script>
        function pratikwpDismissWelcome() {
            jQuery.post(ajaxurl, {
                action: 'pratikwp_dismiss_notice',
                notice: 'welcome',
                nonce: '<?php echo wp_create_nonce('pratikwp_admin_nonce'); ?>'
            }, function() {
                window.location.href = '<?php echo admin_url('admin.php?page=pratikwp-dashboard'); ?>';
            });
        }
        </script>
        <?php
    }

    /**
     * Add dashboard widgets
     */
    public function add_dashboard_widgets() {
        wp_add_dashboard_widget(
            'pratikwp_dashboard_widget',
            __('PratikWp Tema', 'pratikwp'),
            [$this, 'dashboard_widget_content']
        );
    }

    /**
     * Dashboard widget content
     */
    public function dashboard_widget_content() {
        ?>
        <div class="pratikwp-dashboard-widget">
            <p><?php esc_html_e('PratikWp tema dashboard\'una hızlı erişim:', 'pratikwp'); ?></p>
            <div class="widget-actions">
                <a href="<?php echo admin_url('admin.php?page=pratikwp-dashboard'); ?>" class="button button-primary">
                    <?php esc_html_e('Tema Dashboard', 'pratikwp'); ?>
                </a>
                <a href="<?php echo admin_url('customize.php'); ?>" class="button">
                    <?php esc_html_e('Özelleştir', 'pratikwp'); ?>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'pratikwp_page_settings',
            __('PratikWp Sayfa Ayarları', 'pratikwp'),
            [$this, 'page_settings_meta_box'],
            ['page', 'post'],
            'side',
            'default'
        );
    }

    /**
     * Page settings meta box
     */
    public function page_settings_meta_box($post) {
        wp_nonce_field('pratikwp_page_settings', 'pratikwp_page_settings_nonce');
        
        $sidebar = get_post_meta($post->ID, '_pratikwp_sidebar', true);
        $disable_title = get_post_meta($post->ID, '_pratikwp_disable_title', true);
        $meta_description = get_post_meta($post->ID, '_pratikwp_meta_description', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="pratikwp_sidebar"><?php esc_html_e('Sidebar', 'pratikwp'); ?></label></th>
                <td>
                    <select name="pratikwp_sidebar" id="pratikwp_sidebar">
                        <option value=""><?php esc_html_e('Varsayılan', 'pratikwp'); ?></option>
                        <option value="show" <?php selected($sidebar, 'show'); ?>><?php esc_html_e('Göster', 'pratikwp'); ?></option>
                        <option value="none" <?php selected($sidebar, 'none'); ?>><?php esc_html_e('Gizle', 'pratikwp'); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="pratikwp_disable_title"><?php esc_html_e('Sayfa Başlığı', 'pratikwp'); ?></label></th>
                <td>
                    <label>
                        <input type="checkbox" name="pratikwp_disable_title" id="pratikwp_disable_title" value="1" <?php checked($disable_title, '1'); ?> />
                        <?php esc_html_e('Sayfa başlığını gizle', 'pratikwp'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <th><label for="pratikwp_meta_description"><?php esc_html_e('Meta Açıklama', 'pratikwp'); ?></label></th>
                <td>
                    <textarea name="pratikwp_meta_description" id="pratikwp_meta_description" rows="3" class="widefat"><?php echo esc_textarea($meta_description); ?></textarea>
                    <p class="description"><?php esc_html_e('SEO için sayfa açıklaması (160 karakter önerilir)', 'pratikwp'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id) {
        if (!isset($_POST['pratikwp_page_settings_nonce']) || !wp_verify_nonce($_POST['pratikwp_page_settings_nonce'], 'pratikwp_page_settings')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        // Save sidebar setting
        if (isset($_POST['pratikwp_sidebar'])) {
            update_post_meta($post_id, '_pratikwp_sidebar', sanitize_text_field($_POST['pratikwp_sidebar']));
        }
        
        // Save disable title setting
        $disable_title = isset($_POST['pratikwp_disable_title']) ? '1' : '0';
        update_post_meta($post_id, '_pratikwp_disable_title', $disable_title);
        
        // Save meta description
        if (isset($_POST['pratikwp_meta_description'])) {
            update_post_meta($post_id, '_pratikwp_meta_description', sanitize_textarea_field($_POST['pratikwp_meta_description']));
        }
    }

    /**
     * Add admin bar menu
     */
    public function add_admin_bar_menu($wp_admin_bar) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $wp_admin_bar->add_menu([
            'id' => 'pratikwp-menu',
            'title' => __('PratikWp', 'pratikwp'),
            'href' => admin_url('admin.php?page=pratikwp-dashboard'),
        ]);
        
        $wp_admin_bar->add_menu([
            'parent' => 'pratikwp-menu',
            'id' => 'pratikwp-dashboard',
            'title' => __('Dashboard', 'pratikwp'),
            'href' => admin_url('admin.php?page=pratikwp-dashboard'),
        ]);
        
        $wp_admin_bar->add_menu([
            'parent' => 'pratikwp-menu',
            'id' => 'pratikwp-customizer',
            'title' => __('Özelleştir', 'pratikwp'),
            'href' => admin_url('customize.php'),
        ]);
        
        $wp_admin_bar->add_menu([
            'parent' => 'pratikwp-menu',
            'id' => 'pratikwp-settings',
            'title' => __('Tema Ayarları', 'pratikwp'),
            'href' => admin_url('admin.php?page=pratikwp-settings'),
        ]);
    }

    /**
     * Admin notices
     */
    public function admin_notices() {
        // Welcome notice
        if (get_option('pratikwp_show_welcome', true) && current_user_can('manage_options')) {
            ?>
            <div class="notice notice-info is-dismissible pratikwp-welcome-notice">
                <p>
                    <strong><?php esc_html_e('PratikWp Temasına Hoş Geldiniz!', 'pratikwp'); ?></strong>
                    <?php esc_html_e('Başlamak için', 'pratikwp'); ?>
                    <a href="<?php echo admin_url('admin.php?page=pratikwp-getting-started'); ?>">
                        <?php esc_html_e('başlangıç rehberini', 'pratikwp'); ?>
                    </a>
                    <?php esc_html_e('ziyaret edin.', 'pratikwp'); ?>
                </p>
            </div>
            <?php
        }
        
        // Elementor recommendation
        if (!class_exists('\Elementor\Plugin') && current_user_can('install_plugins')) {
            ?>
            <div class="notice notice-warning is-dismissible pratikwp-elementor-notice">
                <p>
                    <strong><?php esc_html_e('Önerilen Eklenti', 'pratikwp'); ?></strong>
                    <?php esc_html_e('PratikWp teması Elementor ile en iyi performansı gösterir.', 'pratikwp'); ?>
                    <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>" class="button button-primary">
                        <?php esc_html_e('Elementor\'u Yükle', 'pratikwp'); ?>
                    </a>
                </p>
            </div>
            <?php
        }
        
        // Performance warnings
        $this->show_performance_notices();
    }

    /**
     * Show performance notices
     */
    private function show_performance_notices() {
        $score = PratikWp_Performance::get_performance_score();
        
        if ($score < 70 && current_user_can('manage_options')) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>
                    <strong><?php esc_html_e('Performans Uyarısı', 'pratikwp'); ?></strong>
                    <?php printf(__('Sitenizin performans skoru %d%%. Performansı artırmak için', 'pratikwp'), $score); ?>
                    <a href="<?php echo admin_url('admin.php?page=pratikwp-settings-performance'); ?>">
                        <?php esc_html_e('performans ayarlarını', 'pratikwp'); ?>
                    </a>
                    <?php esc_html_e('kontrol edin.', 'pratikwp'); ?>
                </p>
            </div>
            <?php
        }
    }

    /**
     * Setup admin notices
     */
    private function setup_admin_notices() {
        // Check for dismissed notices
        $dismissed_notices = get_option('pratikwp_dismissed_notices', []);
        
        if (in_array('welcome', $dismissed_notices)) {
            update_option('pratikwp_show_welcome', false);
        }
    }

    /**
     * Check theme updates
     */
    private function check_theme_updates() {
        // This would connect to update server to check for theme updates
        // Implementation depends on your update server setup
    }

    /**
     * Dismiss notice AJAX handler
     */
    public function dismiss_notice() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Yetkiniz yok.', 'pratikwp'));
        }
        
        $notice = sanitize_text_field($_POST['notice']);
        $dismissed_notices = get_option('pratikwp_dismissed_notices', []);
        
        if (!in_array($notice, $dismissed_notices)) {
            $dismissed_notices[] = $notice;
            update_option('pratikwp_dismissed_notices', $dismissed_notices);
        }
        
        if ($notice === 'welcome') {
            update_option('pratikwp_show_welcome', false);
        }
        
        wp_send_json_success();
    }

    /**
     * Get system info AJAX handler
     */
    public function get_system_info() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Yetkiniz yok.', 'pratikwp'));
        }
        
        wp_send_json_success($this->get_system_info_data());
    }

    /**
     * Performance check AJAX handler
     */
    public function performance_check() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Yetkiniz yok.', 'pratikwp'));
        }
        
        $score = PratikWp_Performance::get_performance_score();
        $recommendations = PratikWp_Performance::get_optimization_recommendations();
        
        wp_send_json_success([
            'score' => $score,
            'recommendations' => $recommendations
        ]);
    }

    /**
     * Get system info data
     */
    private function get_system_info_data() {
        global $wpdb;
        
        $theme = wp_get_theme();
        
        $info = "=== PratikWp Tema Sistem Bilgileri ===\n\n";
        
        // WordPress Info
        $info .= "-- WordPress Bilgileri --\n";
        $info .= "Sürüm: " . get_bloginfo('version') . "\n";
        $info .= "Site URL: " . get_site_url() . "\n";
        $info .= "Home URL: " . get_home_url() . "\n";
        $info .= "Dil: " . get_locale() . "\n";
        $info .= "Multisite: " . (is_multisite() ? 'Evet' : 'Hayır') . "\n";
        $info .= "Debug Mode: " . (defined('WP_DEBUG') && WP_DEBUG ? 'Aktif' : 'Pasif') . "\n\n";
        
        // Theme Info
        $info .= "-- Tema Bilgileri --\n";
        $info .= "Tema Adı: " . $theme->get('Name') . "\n";
        $info .= "Tema Sürümü: " . $theme->get('Version') . "\n";
        $info .= "Tema Dizini: " . get_template_directory() . "\n";
        $info .= "Child Theme: " . (is_child_theme() ? 'Evet' : 'Hayır') . "\n\n";
        
        // Server Info
        $info .= "-- Sunucu Bilgileri --\n";
        $info .= "PHP Sürümü: " . PHP_VERSION . "\n";
        $info .= "MySQL Sürümü: " . $wpdb->db_version() . "\n";
        $info .= "Web Sunucusu: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
        $info .= "Max Execution Time: " . ini_get('max_execution_time') . " saniye\n";
        $info .= "Memory Limit: " . ini_get('memory_limit') . "\n";
        $info .= "Post Max Size: " . ini_get('post_max_size') . "\n";
        $info .= "Upload Max Size: " . ini_get('upload_max_filesize') . "\n\n";
        
        // Active Plugins
        $info .= "-- Aktif Eklentiler --\n";
        $active_plugins = get_option('active_plugins', []);
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $info .= $plugin_data['Name'] . " (v" . $plugin_data['Version'] . ")\n";
        }
        $info .= "\n";
        
        // Theme Settings
        $info .= "-- Tema Ayarları --\n";
        $info .= "Elementor: " . (class_exists('\Elementor\Plugin') ? 'Aktif' : 'Pasif') . "\n";
        $info .= "Performans Skoru: " . PratikWp_Performance::get_performance_score() . "%\n";
        $info .= "Lazy Loading: " . (get_theme_mod('enable_lazy_loading', true) ? 'Aktif' : 'Pasif') . "\n";
        $info .= "GZIP: " . (get_theme_mod('enable_gzip', true) ? 'Aktif' : 'Pasif') . "\n";
        $info .= "Emoji Devre Dışı: " . (get_theme_mod('disable_emojis', true) ? 'Evet' : 'Hayır') . "\n";
        
        return $info;
    }

    /**
     * Theme activation
     */
    public function theme_activation() {
        // Set default options
        update_option('pratikwp_show_welcome', true);
        delete_option('pratikwp_dismissed_notices');
        
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Schedule welcome redirect
        set_transient('pratikwp_welcome_redirect', true, 30);
    }

    /**
     * Theme deactivation
     */
    public function theme_deactivation() {
        // Clean up options if needed
        delete_transient('pratikwp_welcome_redirect');
    }
}