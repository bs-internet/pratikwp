<?php
/**
 * Admin Updates Notice
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Admin Updates Notice handler
 */
class PratikWp_Updates_Notice {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_notices', [$this, 'show_update_notices']);
        add_action('wp_ajax_pratikwp_dismiss_update_notice', [$this, 'dismiss_update_notice']);
        add_action('wp_ajax_pratikwp_check_theme_updates', [$this, 'check_theme_updates']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('init', [$this, 'schedule_update_check']);
        add_action('pratikwp_check_updates', [$this, 'automated_update_check']);
    }

    /**
     * Schedule automated update checks
     */
    public function schedule_update_check() {
        if (!wp_next_scheduled('pratikwp_check_updates')) {
            wp_schedule_event(time(), 'daily', 'pratikwp_check_updates');
        }
    }

    /**
     * Show update notices
     */
    public function show_update_notices() {
        // Only show to administrators
        if (!current_user_can('update_themes')) {
            return;
        }

        // Don't show on PratikWp admin pages to avoid clutter
        if (isset($_GET['page']) && strpos($_GET['page'], 'pratikwp') !== false) {
            return;
        }

        $this->show_theme_update_notice();
        $this->show_plugin_compatibility_notice();
        $this->show_security_update_notice();
        $this->show_feature_announcement_notice();
    }

    /**
     * Show theme update notice
     */
    private function show_theme_update_notice() {
        $update_data = $this->get_theme_update_data();
        
        if (!$update_data || !$update_data['has_update']) {
            return;
        }

        // Check if user dismissed this specific version
        $dismissed_version = get_user_meta(get_current_user_id(), 'pratikwp_dismissed_update_version', true);
        if ($dismissed_version === $update_data['new_version']) {
            return;
        }

        ?>
        <div class="notice notice-warning is-dismissible pratikwp-update-notice" data-notice-type="theme-update">
            <div class="update-notice-content">
                <div class="notice-header">
                    <div class="notice-icon">
                        <span class="dashicons dashicons-update"></span>
                    </div>
                    <div class="notice-text">
                        <h3><?php esc_html_e('PratikWp Tema Güncellemesi Mevcut', 'pratikwp'); ?></h3>
                        <p>
                            <?php 
                            printf(
                                esc_html__('Tema sürümü %s mevcut (şu anki sürüm: %s). Yeni özellikler ve hata düzeltmeleri içerir.', 'pratikwp'),
                                '<strong>' . esc_html($update_data['new_version']) . '</strong>',
                                '<strong>' . esc_html(PRATIKWP_VERSION) . '</strong>'
                            ); 
                            ?>
                        </p>
                    </div>
                </div>

                <?php if (!empty($update_data['changelog'])): ?>
                <div class="changelog-section">
                    <h4><?php esc_html_e('Bu Güncellemede Neler Var?', 'pratikwp'); ?></h4>
                    <div class="changelog-content">
                        <?php echo wp_kses_post($update_data['changelog']); ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="update-actions">
                    <a href="<?php echo wp_nonce_url(admin_url('update.php?action=upgrade-theme&theme=' . get_stylesheet()), 'upgrade-theme_' . get_stylesheet()); ?>" 
                       class="button button-primary button-hero">
                        <?php esc_html_e('Şimdi Güncelle', 'pratikwp'); ?>
                    </a>
                    <a href="<?php echo esc_url($update_data['details_url']); ?>" 
                       target="_blank" class="button button-secondary">
                        <?php esc_html_e('Güncelleme Detayları', 'pratikwp'); ?>
                    </a>
                    <button type="button" class="button button-link dismiss-update-notice" 
                            data-version="<?php echo esc_attr($update_data['new_version']); ?>">
                        <?php esc_html_e('Bu Sürüm İçin Hatırlat', 'pratikwp'); ?>
                    </button>
                </div>

                <?php if (!empty($update_data['backup_warning'])): ?>
                <div class="backup-warning">
                    <span class="dashicons dashicons-warning"></span>
                    <strong><?php esc_html_e('Önemli:', 'pratikwp'); ?></strong>
                    <?php esc_html_e('Güncelleme öncesi site yedeği almayı unutmayın.', 'pratikwp'); ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Show plugin compatibility notice
     */
    private function show_plugin_compatibility_notice() {
        // Check if user dismissed this notice
        if (get_user_meta(get_current_user_id(), 'pratikwp_plugin_compatibility_dismissed', true)) {
            return;
        }

        $incompatible_plugins = $this->get_incompatible_plugins();
        
        if (empty($incompatible_plugins)) {
            return;
        }

        ?>
        <div class="notice notice-error is-dismissible pratikwp-compatibility-notice" data-notice-type="plugin-compatibility">
            <div class="compatibility-notice-content">
                <div class="notice-header">
                    <div class="notice-icon">
                        <span class="dashicons dashicons-warning"></span>
                    </div>
                    <div class="notice-text">
                        <h3><?php esc_html_e('Eklenti Uyumluluk Uyarısı', 'pratikwp'); ?></h3>
                        <p><?php esc_html_e('Bazı aktif eklentiler PratikWp teması ile uyumluluk sorunu yaşayabilir.', 'pratikwp'); ?></p>
                    </div>
                </div>

                <div class="incompatible-plugins">
                    <h4><?php esc_html_e('Sorunlu Eklentiler:', 'pratikwp'); ?></h4>
                    <ul>
                        <?php foreach ($incompatible_plugins as $plugin): ?>
                            <li>
                                <strong><?php echo esc_html($plugin['name']); ?></strong>
                                <span class="plugin-version">(v<?php echo esc_html($plugin['version']); ?>)</span>
                                <p class="plugin-issue"><?php echo esc_html($plugin['issue']); ?></p>
                                <?php if (!empty($plugin['solution'])): ?>
                                    <p class="plugin-solution">
                                        <strong><?php esc_html_e('Çözüm:', 'pratikwp'); ?></strong>
                                        <?php echo esc_html($plugin['solution']); ?>
                                    </p>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="compatibility-actions">
                    <a href="<?php echo admin_url('plugins.php'); ?>" class="button button-primary">
                        <?php esc_html_e('Eklentileri Yönet', 'pratikwp'); ?>
                    </a>
                    <a href="https://pratikwp.com/compatibility" target="_blank" class="button button-secondary">
                        <?php esc_html_e('Uyumluluk Rehberi', 'pratikwp'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Show security update notice
     */
    private function show_security_update_notice() {
        $security_notice = get_transient('pratikwp_security_notice');
        
        if (!$security_notice) {
            return;
        }

        // Check if user dismissed this specific security notice
        $dismissed_security = get_user_meta(get_current_user_id(), 'pratikwp_dismissed_security_' . $security_notice['id'], true);
        if ($dismissed_security) {
            return;
        }

        ?>
        <div class="notice notice-error pratikwp-security-notice" data-notice-type="security" data-security-id="<?php echo esc_attr($security_notice['id']); ?>">
            <div class="security-notice-content">
                <div class="notice-header">
                    <div class="notice-icon">
                        <span class="dashicons dashicons-shield-alt"></span>
                    </div>
                    <div class="notice-text">
                        <h3><?php esc_html_e('Güvenlik Bildirimi', 'pratikwp'); ?></h3>
                        <p><strong><?php echo esc_html($security_notice['title']); ?></strong></p>
                        <p><?php echo wp_kses_post($security_notice['message']); ?></p>
                    </div>
                </div>

                <div class="security-actions">
                    <?php if (!empty($security_notice['update_url'])): ?>
                        <a href="<?php echo esc_url($security_notice['update_url']); ?>" class="button button-primary">
                            <?php esc_html_e('Güvenlik Güncellemesi Yap', 'pratikwp'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($security_notice['info_url'])): ?>
                        <a href="<?php echo esc_url($security_notice['info_url']); ?>" target="_blank" class="button button-secondary">
                            <?php esc_html_e('Daha Fazla Bilgi', 'pratikwp'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <button type="button" class="button button-link dismiss-security-notice">
                        <?php esc_html_e('Anladım', 'pratikwp'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Show feature announcement notice
     */
    private function show_feature_announcement_notice() {
        $announcement = get_transient('pratikwp_feature_announcement');
        
        if (!$announcement) {
            return;
        }

        // Check if user dismissed this announcement
        $dismissed_announcement = get_user_meta(get_current_user_id(), 'pratikwp_dismissed_announcement_' . $announcement['id'], true);
        if ($dismissed_announcement) {
            return;
        }

        ?>
        <div class="notice notice-info is-dismissible pratikwp-announcement-notice" data-notice-type="announcement" data-announcement-id="<?php echo esc_attr($announcement['id']); ?>">
            <div class="announcement-content">
                <div class="notice-header">
                    <div class="notice-icon">
                        <span class="dashicons dashicons-megaphone"></span>
                    </div>
                    <div class="notice-text">
                        <h3><?php echo esc_html($announcement['title']); ?></h3>
                        <p><?php echo wp_kses_post($announcement['message']); ?></p>
                    </div>
                </div>

                <?php if (!empty($announcement['features'])): ?>
                <div class="new-features">
                    <h4><?php esc_html_e('Yeni Özellikler:', 'pratikwp'); ?></h4>
                    <ul>
                        <?php foreach ($announcement['features'] as $feature): ?>
                            <li>
                                <span class="dashicons dashicons-yes-alt"></span>
                                <?php echo esc_html($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="announcement-actions">
                    <?php if (!empty($announcement['action_url'])): ?>
                        <a href="<?php echo esc_url($announcement['action_url']); ?>" class="button button-primary">
                            <?php echo esc_html($announcement['action_text'] ?: __('Daha Fazla Bilgi', 'pratikwp')); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Get theme update data
     */
    private function get_theme_update_data() {
        $cached_data = get_transient('pratikwp_theme_update_data');
        
        if ($cached_data !== false) {
            return $cached_data;
        }

        // Check for updates from remote server
        $response = wp_remote_get('https://api.pratikwp.com/v1/theme-updates', [
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'PratikWp/' . PRATIKWP_VERSION . '; ' . home_url(),
            ]
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!$data || !isset($data['version'])) {
            return false;
        }

        $update_data = [
            'has_update' => version_compare($data['version'], PRATIKWP_VERSION, '>'),
            'new_version' => $data['version'],
            'changelog' => $data['changelog'] ?? '',
            'details_url' => $data['details_url'] ?? 'https://pratikwp.com/changelog',
            'backup_warning' => $data['requires_backup'] ?? false,
        ];

        // Cache for 12 hours
        set_transient('pratikwp_theme_update_data', $update_data, 12 * HOUR_IN_SECONDS);

        return $update_data;
    }

    /**
     * Get incompatible plugins
     */
    private function get_incompatible_plugins() {
        $active_plugins = get_option('active_plugins');
        $incompatible = [];

        // Known problematic plugins
        $problematic_plugins = [
            'old-elementor/elementor.php' => [
                'name' => 'Elementor (Eski Sürüm)',
                'issue' => 'Eski Elementor sürümü tema ile uyumlu değil',
                'solution' => 'Elementor\'ı en son sürüme güncelleyin'
            ],
            'jetpack/jetpack.php' => [
                'name' => 'Jetpack',
                'issue' => 'Bazı Jetpack modülleri tema stilleri ile çakışabilir',
                'solution' => 'Jetpack CSS modülünü devre dışı bırakın'
            ],
        ];

        foreach ($active_plugins as $plugin) {
            if (isset($problematic_plugins[$plugin])) {
                $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
                $incompatible[] = array_merge($problematic_plugins[$plugin], [
                    'version' => $plugin_data['Version'],
                    'file' => $plugin
                ]);
            }
        }

        // Check Elementor version specifically
        if (is_plugin_active('elementor/elementor.php')) {
            $elementor_data = get_plugin_data(WP_PLUGIN_DIR . '/elementor/elementor.php');
            if (version_compare($elementor_data['Version'], '3.0.0', '<')) {
                $incompatible[] = [
                    'name' => 'Elementor',
                    'version' => $elementor_data['Version'],
                    'issue' => 'PratikWp, Elementor 3.0.0 ve üzeri sürümleri destekler',
                    'solution' => 'Elementor\'ı güncelleyin'
                ];
            }
        }

        return $incompatible;
    }

    /**
     * Enqueue scripts
     */
    public function enqueue_scripts($hook) {
        wp_enqueue_script(
            'pratikwp-update-notices',
            get_template_directory_uri() . '/assets/js/admin-notices.js',
            ['jquery'],
            PRATIKWP_VERSION,
            true
        );

        wp_localize_script('pratikwp-update-notices', 'pratikwpUpdates', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('pratikwp_updates_nonce'),
            'messages' => [
                'updating' => __('Güncelleniyor...', 'pratikwp'),
                'error' => __('Bir hata oluştu', 'pratikwp'),
                'success' => __('Başarıyla tamamlandı', 'pratikwp'),
            ]
        ]);
    }

    /**
     * AJAX: Dismiss update notice
     */
    public function dismiss_update_notice() {
        check_ajax_referer('pratikwp_updates_nonce', 'nonce');

        $notice_type = sanitize_text_field($_POST['notice_type']);
        $user_id = get_current_user_id();

        switch ($notice_type) {
            case 'theme-update':
                $version = sanitize_text_field($_POST['version']);
                update_user_meta($user_id, 'pratikwp_dismissed_update_version', $version);
                break;
                
            case 'plugin-compatibility':
                update_user_meta($user_id, 'pratikwp_plugin_compatibility_dismissed', time());
                break;
                
            case 'security':
                $security_id = sanitize_text_field($_POST['security_id']);
                update_user_meta($user_id, 'pratikwp_dismissed_security_' . $security_id, time());
                break;
                
            case 'announcement':
                $announcement_id = sanitize_text_field($_POST['announcement_id']);
                update_user_meta($user_id, 'pratikwp_dismissed_announcement_' . $announcement_id, time());
                break;
        }

        wp_send_json_success(['message' => __('Bildirim kapatıldı', 'pratikwp')]);
    }

    /**
     * AJAX: Check for theme updates
     */
    public function check_theme_updates() {
        check_ajax_referer('pratikwp_updates_nonce', 'nonce');

        // Clear cached update data to force fresh check
        delete_transient('pratikwp_theme_update_data');
        
        $update_data = $this->get_theme_update_data();

        if ($update_data && $update_data['has_update']) {
            wp_send_json_success([
                'has_update' => true,
                'new_version' => $update_data['new_version'],
                'current_version' => PRATIKWP_VERSION,
                'changelog' => $update_data['changelog']
            ]);
        } else {
            wp_send_json_success([
                'has_update' => false,
                'message' => __('Tema güncel, yeni sürüm mevcut değil.', 'pratikwp')
            ]);
        }
    }

    /**
     * Automated update check (runs daily)
     */
    public function automated_update_check() {
        // Clear cache to get fresh data
        delete_transient('pratikwp_theme_update_data');
        
        // Fetch update information
        $this->get_theme_update_data();
        
        // Check for security notices
        $this->check_security_notices();
        
        // Check for feature announcements
        $this->check_feature_announcements();
    }

    /**
     * Check for security notices
     */
    private function check_security_notices() {
        $response = wp_remote_get('https://api.pratikwp.com/v1/security-notices', [
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'PratikWp/' . PRATIKWP_VERSION . '; ' . home_url(),
            ]
        ]);

        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if ($data && isset($data['notices']) && !empty($data['notices'])) {
                foreach ($data['notices'] as $notice) {
                    // Only show critical security notices
                    if ($notice['priority'] === 'critical') {
                        set_transient('pratikwp_security_notice', $notice, 7 * DAY_IN_SECONDS);
                        break; // Only show one critical notice at a time
                    }
                }
            }
        }
    }

    /**
     * Check for feature announcements
     */
    private function check_feature_announcements() {
        $response = wp_remote_get('https://api.pratikwp.com/v1/announcements', [
            'timeout' => 15,
            'headers' => [
                'User-Agent' => 'PratikWp/' . PRATIKWP_VERSION . '; ' . home_url(),
            ]
        ]);

        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if ($data && isset($data['announcement'])) {
                set_transient('pratikwp_feature_announcement', $data['announcement'], 14 * DAY_IN_SECONDS);
            }
        }
    }

    /**
     * Manual update check (for admin button)
     */
    public function manual_update_check() {
        delete_transient('pratikwp_theme_update_data');
        delete_transient('pratikwp_security_notice');
        delete_transient('pratikwp_feature_announcement');
        
        $this->automated_update_check();
        
        return $this->get_theme_update_data();
    }

    /**
     * Get update status for dashboard
     */
    public function get_update_status() {
        $update_data = $this->get_theme_update_data();
        
        return [
            'has_updates' => $update_data && $update_data['has_update'],
            'current_version' => PRATIKWP_VERSION,
            'latest_version' => $update_data ? $update_data['new_version'] : PRATIKWP_VERSION,
            'last_checked' => get_transient('pratikwp_theme_update_data') ? 
                             get_option('_transient_timeout_pratikwp_theme_update_data') : false,
            'security_notices' => get_transient('pratikwp_security_notice') ? true : false,
            'announcements' => get_transient('pratikwp_feature_announcement') ? true : false,
        ];
    }
}