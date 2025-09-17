<?php
/**
 * Admin Welcome Notice
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Admin Welcome Notice handler
 */
class PratikWp_Welcome_Notice {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_notices', [$this, 'show_welcome_notice']);
        add_action('wp_ajax_pratikwp_dismiss_welcome', [$this, 'dismiss_welcome_notice']);
        add_action('after_switch_theme', [$this, 'activation_notice']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Show welcome notice
     */
    public function show_welcome_notice() {
        // Don't show on PratikWp admin pages
        if (isset($_GET['page']) && strpos($_GET['page'], 'pratikwp') !== false) {
            return;
        }

        // Check if user dismissed the notice
        if (get_user_meta(get_current_user_id(), 'pratikwp_welcome_dismissed', true)) {
            return;
        }

        // Only show to users who can manage options
        if (!current_user_can('manage_options')) {
            return;
        }

        // Check if theme is recently activated
        $activation_time = get_option('pratikwp_activation_time');
        if (!$activation_time || (time() - $activation_time) > (7 * DAY_IN_SECONDS)) {
            return;
        }

        $this->render_welcome_notice();
    }

    /**
     * Render welcome notice
     */
    private function render_welcome_notice() {
        ?>
        <div class="notice notice-info is-dismissible pratikwp-welcome-notice" id="pratikwp-welcome-notice">
            <div class="pratikwp-welcome-content">
                <div class="welcome-header">
                    <div class="welcome-logo">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" 
                             alt="PratikWp" width="60" height="60" />
                    </div>
                    <div class="welcome-text">
                        <h2><?php esc_html_e('PratikWp Temasına Hoş Geldiniz!', 'pratikwp'); ?></h2>
                        <p><?php esc_html_e('Profesyonel web sitenizi oluşturmaya hazır mısınız? İşte başlamanız için bazı öneriler.', 'pratikwp'); ?></p>
                    </div>
                </div>

                <div class="welcome-steps">
                    <div class="step-item">
                        <div class="step-icon">
                            <span class="dashicons dashicons-download"></span>
                        </div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Demo İçerik Yükleyin', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Hızlı başlamak için hazır demo içeriklerini kullanın', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-demo-import'); ?>" class="button button-primary">
                                <?php esc_html_e('Demo İçe Aktar', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-icon">
                            <span class="dashicons dashicons-admin-customizer"></span>
                        </div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Temanızı Özelleştirin', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Logo, renkler ve yazı tiplerini ayarlayın', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary">
                                <?php esc_html_e('Özelleştir', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-icon">
                            <span class="dashicons dashicons-admin-plugins"></span>
                        </div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Eklentileri Kontrol Edin', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Önerilen eklentileri yükleyerek tema özelliklerini genişletin', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-recommended-plugins'); ?>" class="button button-secondary">
                                <?php esc_html_e('Eklentiler', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="step-item">
                        <div class="step-icon">
                            <span class="dashicons dashicons-admin-settings"></span>
                        </div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Tema Ayarlarını Keşfedin', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Gelişmiş tema seçenekleri ve performans ayarları', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-settings'); ?>" class="button button-secondary">
                                <?php esc_html_e('Ayarlar', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="welcome-features">
                    <h3><?php esc_html_e('Tema Özellikleri', 'pratikwp'); ?></h3>
                    <div class="features-grid">
                        <div class="feature-item">
                            <span class="feature-icon dashicons dashicons-smartphone"></span>
                            <div class="feature-content">
                                <h5><?php esc_html_e('Responsive Tasarım', 'pratikwp'); ?></h5>
                                <p><?php esc_html_e('Tüm cihazlarda mükemmel görünüm', 'pratikwp'); ?></p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-icon dashicons dashicons-performance"></span>
                            <div class="feature-content">
                                <h5><?php esc_html_e('Yüksek Performans', 'pratikwp'); ?></h5>
                                <p><?php esc_html_e('Optimize edilmiş kod ve hızlı yükleme', 'pratikwp'); ?></p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-icon dashicons dashicons-admin-appearance"></span>
                            <div class="feature-content">
                                <h5><?php esc_html_e('Elementor Uyumlu', 'pratikwp'); ?></h5>
                                <p><?php esc_html_e('Sürükle-bırak sayfa oluşturucu desteği', 'pratikwp'); ?></p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-icon dashicons dashicons-admin-tools"></span>
                            <div class="feature-content">
                                <h5><?php esc_html_e('Gelişmiş Özelleştirme', 'pratikwp'); ?></h5>
                                <p><?php esc_html_e('Kapsamlı tema seçenekleri', 'pratikwp'); ?></p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-icon dashicons dashicons-shield"></span>
                            <div class="feature-content">
                                <h5><?php esc_html_e('SEO Optimize', 'pratikwp'); ?></h5>
                                <p><?php esc_html_e('Arama motorları için optimize edilmiş', 'pratikwp'); ?></p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <span class="feature-icon dashicons dashicons-translation"></span>
                            <div class="feature-content">
                                <h5><?php esc_html_e('Çok Dilli Destek', 'pratikwp'); ?></h5>
                                <p><?php esc_html_e('WPML ve Polylang uyumlu', 'pratikwp'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="welcome-support">
                    <div class="support-grid">
                        <div class="support-item">
                            <h4><?php esc_html_e('Dokümantasyon', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Detaylı kullanım kılavuzu ve örnekler', 'pratikwp'); ?></p>
                            <a href="https://pratikwp.com/docs" target="_blank" class="button button-link">
                                <?php esc_html_e('Dokümantasyonu Görüntüle', 'pratikwp'); ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </div>

                        <div class="support-item">
                            <h4><?php esc_html_e('Video Eğitimler', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Adım adım video kılavuzlar', 'pratikwp'); ?></p>
                            <a href="https://pratikwp.com/tutorials" target="_blank" class="button button-link">
                                <?php esc_html_e('Videoları İzle', 'pratikwp'); ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </div>

                        <div class="support-item">
                            <h4><?php esc_html_e('Destek Forumu', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Topluluktan yardım alın', 'pratikwp'); ?></p>
                            <a href="https://pratikwp.com/support" target="_blank" class="button button-link">
                                <?php esc_html_e('Foruma Git', 'pratikwp'); ?>
                                <span class="dashicons dashicons-external"></span>
                            </a>
                        </div>

                        <div class="support-item">
                            <h4><?php esc_html_e('Tema Güncellemeleri', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Otomatik güncellemeler ve yeni özellikler', 'pratikwp'); ?></p>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-updates'); ?>" class="button button-link">
                                <?php esc_html_e('Güncellemeleri Kontrol Et', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="welcome-footer">
                    <div class="footer-content">
                        <div class="footer-text">
                            <p>
                                <?php esc_html_e('Teşekkürler! PratikWp temasını seçtiğiniz için mutluyuz.', 'pratikwp'); ?>
                                <strong><?php esc_html_e('İyi tasarımlar dileriz!', 'pratikwp'); ?></strong>
                            </p>
                        </div>
                        <div class="footer-actions">
                            <button type="button" class="button button-link dismiss-notice" data-dismiss="welcome">
                                <?php esc_html_e('Bu mesajı kapat', 'pratikwp'); ?>
                            </button>
                            <a href="<?php echo admin_url('admin.php?page=pratikwp-dashboard'); ?>" class="button button-primary">
                                <?php esc_html_e('Tema Dashboard\'una Git', 'pratikwp'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .pratikwp-welcome-notice {
            border-left-color: #007cba !important;
            padding: 0 !important;
            position: relative;
        }
        
        .pratikwp-welcome-content {
            padding: 25px;
        }
        
        .welcome-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        
        .welcome-logo img {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-text h2 {
            margin: 0 0 8px 0;
            font-size: 24px;
            color: #1d2327;
        }
        
        .welcome-text p {
            margin: 0;
            font-size: 16px;
            color: #646970;
            line-height: 1.5;
        }
        
        .welcome-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            transition: all 0.3s ease;
        }
        
        .step-item:hover {
            background: #f0f6fc;
            border-color: #007cba;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 124, 186, 0.1);
        }
        
        .step-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            background: #007cba;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .step-icon .dashicons {
            font-size: 20px;
            width: 20px;
            height: 20px;
        }
        
        .step-content h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            color: #1d2327;
        }
        
        .step-content p {
            margin: 0 0 12px 0;
            font-size: 14px;
            color: #646970;
            line-height: 1.4;
        }
        
        .welcome-features {
            margin-bottom: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }
        
        .welcome-features h3 {
            margin: 0 0 20px 0;
            text-align: center;
            color: white;
            font-size: 20px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }
        
        .feature-icon {
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .feature-content h5 {
            margin: 0 0 4px 0;
            font-size: 14px;
            color: white;
        }
        
        .feature-content p {
            margin: 0;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.3;
        }
        
        .welcome-support {
            margin-bottom: 20px;
        }
        
        .support-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .support-item {
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
        }
        
        .support-item h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #1d2327;
        }
        
        .support-item p {
            margin: 0 0 12px 0;
            font-size: 12px;
            color: #646970;
            line-height: 1.3;
        }
        
        .support-item .button {
            font-size: 12px;
            padding: 4px 8px;
            height: auto;
        }
        
        .support-item .button .dashicons {
            font-size: 12px;
            width: 12px;
            height: 12px;
            margin-left: 4px;
            vertical-align: text-top;
        }
        
        .welcome-footer {
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .footer-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }
        
        .footer-text p {
            margin: 0;
            font-size: 14px;
            color: #646970;
        }
        
        .footer-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        @media (max-width: 768px) {
            .welcome-header {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome-steps {
                grid-template-columns: 1fr;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .support-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }
        
        @media (max-width: 480px) {
            .support-grid {
                grid-template-columns: 1fr;
            }
        }
        </style>
        <?php
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook) {
        if (!isset($_GET['page']) || strpos($_GET['page'], 'pratikwp') === false) {
            wp_enqueue_script(
                'pratikwp-welcome-notice',
                get_template_directory_uri() . '/assets/js/admin-notices.js',
                ['jquery'],
                PRATIKWP_VERSION,
                true
            );

            wp_localize_script('pratikwp-welcome-notice', 'pratikwpNotices', [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('pratikwp_notices_nonce'),
                'dismissing' => __('Kapatılıyor...', 'pratikwp'),
            ]);
        }
    }

    /**
     * Set activation time on theme activation
     */
    public function activation_notice() {
        update_option('pratikwp_activation_time', time());
        
        // Remove dismissed flag so notice shows again for new activation
        delete_user_meta(get_current_user_id(), 'pratikwp_welcome_dismissed');
        
        // Set a transient to show activation success notice
        set_transient('pratikwp_activation_notice', true, 60);
    }

    /**
     * AJAX: Dismiss welcome notice
     */
    public function dismiss_welcome_notice() {
        check_ajax_referer('pratikwp_notices_nonce', 'nonce');

        $notice_type = sanitize_text_field($_POST['notice_type']);
        $user_id = get_current_user_id();

        switch ($notice_type) {
            case 'welcome':
                update_user_meta($user_id, 'pratikwp_welcome_dismissed', time());
                break;
            
            case 'plugin_recommendation':
                update_user_meta($user_id, 'pratikwp_plugin_recommendation_dismissed', time());
                break;
                
            case 'rating_request':
                update_user_meta($user_id, 'pratikwp_rating_request_dismissed', time());
                break;
        }

        wp_send_json_success([
            'message' => __('Bildirim kapatıldı', 'pratikwp')
        ]);
    }

    /**
     * Show activation success notice
     */
    public function show_activation_notice() {
        if (!get_transient('pratikwp_activation_notice')) {
            return;
        }

        delete_transient('pratikwp_activation_notice');
        ?>
        <div class="notice notice-success is-dismissible">
            <p>
                <strong><?php esc_html_e('PratikWp teması başarıyla etkinleştirildi!', 'pratikwp'); ?></strong>
                <?php esc_html_e('Temanızı özelleştirmeye başlayabilirsiniz.', 'pratikwp'); ?>
                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary" style="margin-left: 10px;">
                    <?php esc_html_e('Özelleştir', 'pratikwp'); ?>
                </a>
            </p>
        </div>
        <?php
    }

    /**
     * Check if should show plugin recommendation notice
     */
    public function should_show_plugin_notice() {
        // Don't show if dismissed
        if (get_user_meta(get_current_user_id(), 'pratikwp_plugin_recommendation_dismissed', true)) {
            return false;
        }

        // Only show after theme has been active for 3 days
        $activation_time = get_option('pratikwp_activation_time');
        if (!$activation_time || (time() - $activation_time) < (3 * DAY_IN_SECONDS)) {
            return false;
        }

        // Check if Elementor is installed
        if (!is_plugin_active('elementor/elementor.php')) {
            return true;
        }

        return false;
    }

    /**
     * Show plugin recommendation notice
     */
    public function show_plugin_recommendation_notice() {
        if (!$this->should_show_plugin_notice()) {
            return;
        }
        ?>
        <div class="notice notice-info is-dismissible pratikwp-plugin-notice">
            <div style="display: flex; align-items: center; gap: 15px; padding: 10px 0;">
                <div style="flex-shrink: 0;">
                    <span class="dashicons dashicons-admin-plugins" style="font-size: 24px; color: #007cba;"></span>
                </div>
                <div>
                    <h3 style="margin: 0 0 8px 0;"><?php esc_html_e('PratikWp ile Daha Fazlasını Yapın!', 'pratikwp'); ?></h3>
                    <p style="margin: 0 0 10px 0;">
                        <?php esc_html_e('Temanızın tüm özelliklerinden yararlanmak için Elementor eklentisini yüklemenizi öneririz.', 'pratikwp'); ?>
                    </p>
                    <a href="<?php echo admin_url('plugin-install.php?s=elementor&tab=search&type=term'); ?>" class="button button-primary">
                        <?php esc_html_e('Elementor\'ı Yükle', 'pratikwp'); ?>
                    </a>
                    <button type="button" class="button button-link dismiss-notice" data-dismiss="plugin_recommendation">
                        <?php esc_html_e('Daha sonra hatırlat', 'pratikwp'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Check if should show rating request
     */
    public function should_show_rating_request() {
        // Don't show if dismissed
        if (get_user_meta(get_current_user_id(), 'pratikwp_rating_request_dismissed', true)) {
            return false;
        }

        // Only show after theme has been active for 14 days
        $activation_time = get_option('pratikwp_activation_time');
        if (!$activation_time || (time() - $activation_time) < (14 * DAY_IN_SECONDS)) {
            return false;
        }

        return true;
    }

    /**
     * Show rating request notice
     */
    public function show_rating_request_notice() {
        if (!$this->should_show_rating_request()) {
            return;
        }
        ?>
        <div class="notice notice-info is-dismissible pratikwp-rating-notice">
            <div style="display: flex; align-items: center; gap: 15px; padding: 10px 0;">
                <div style="flex-shrink: 0;">
                    <span class="dashicons dashicons-star-filled" style="font-size: 24px; color: #ffc107;"></span>
                </div>
                <div>
                    <h3 style="margin: 0 0 8px 0;"><?php esc_html_e('PratikWp Temasından Memnun Kaldınız mı?', 'pratikwp'); ?></h3>
                    <p style="margin: 0 0 10px 0;">
                        <?php esc_html_e('Eğer temanızı beğendiyseniz, 5 yıldızlı bir değerlendirme bırakarak bizi destekleyebilirsiniz!', 'pratikwp'); ?>
                    </p>
                    <a href="https://wordpress.org/themes/pratikwp/" target="_blank" class="button button-primary">
                        <?php esc_html_e('⭐ Değerlendir', 'pratikwp'); ?>
                    </a>
                    <button type="button" class="button button-link dismiss-notice" data-dismiss="rating_request">
                        <?php esc_html_e('Daha sonra', 'pratikwp'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get welcome notice status
     */
    public static function is_dismissed() {
        return (bool) get_user_meta(get_current_user_id(), 'pratikwp_welcome_dismissed', true);
    }

    /**
     * Reset welcome notice (show again)
     */
    public static function reset_notice() {
        delete_user_meta(get_current_user_id(), 'pratikwp_welcome_dismissed');
        update_option('pratikwp_activation_time', time());
    }

    /**
     * Get theme usage statistics
     */
    private function get_usage_stats() {
        $activation_time = get_option('pratikwp_activation_time');
        $days_active = $activation_time ? floor((time() - $activation_time) / DAY_IN_SECONDS) : 0;
        
        $posts_count = wp_count_posts()->publish;
        $pages_count = wp_count_posts('page')->publish;
        
        return [
            'days_active' => $days_active,
            'posts_count' => $posts_count,
            'pages_count' => $pages_count,
            'customizer_used' => get_option('pratikwp_customizer_used', false),
            'demo_imported' => get_option('pratikwp_demo_imported', false),
        ];
    }
}