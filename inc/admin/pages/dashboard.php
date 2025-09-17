<?php
/**
 * Admin Dashboard Page
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Admin Dashboard handler
 */
class PratikWp_Admin_Dashboard {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_pratikwp_dashboard_stats', [$this, 'get_dashboard_stats']);
        add_action('wp_ajax_pratikwp_system_check', [$this, 'run_system_check']);
        add_action('wp_ajax_pratikwp_clear_cache', [$this, 'clear_cache']);
        add_action('wp_ajax_pratikwp_optimize_database', [$this, 'optimize_database']);
    }

    /**
     * Render dashboard page
     */
    public function render_page() {
        ?>
        <div class="wrap pratikwp-admin-page pratikwp-dashboard">
            <div class="pratikwp-dashboard-header">
                <div class="header-content">
                    <h1><?php esc_html_e('PratikWp Dashboard', 'pratikwp'); ?></h1>
                    <p class="dashboard-subtitle"><?php esc_html_e('Temanızın durumunu takip edin ve performansını optimize edin', 'pratikwp'); ?></p>
                </div>
                <div class="header-actions">
                    <button type="button" class="button button-primary" id="refresh-dashboard">
                        <i class="dashicons dashicons-update"></i>
                        <?php esc_html_e('Yenile', 'pratikwp'); ?>
                    </button>
                </div>
            </div>

            <div class="pratikwp-dashboard-grid">
                <!-- Quick Stats Cards -->
                <div class="dashboard-section stats-cards">
                    <div class="stats-grid">
                        <div class="stat-card posts">
                            <div class="stat-icon">
                                <i class="dashicons dashicons-admin-post"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="posts-count"><?php echo wp_count_posts()->publish; ?></div>
                                <div class="stat-label"><?php esc_html_e('Yayınlanan Yazı', 'pratikwp'); ?></div>
                            </div>
                        </div>

                        <div class="stat-card pages">
                            <div class="stat-icon">
                                <i class="dashicons dashicons-admin-page"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="pages-count"><?php echo wp_count_posts('page')->publish; ?></div>
                                <div class="stat-label"><?php esc_html_e('Yayınlanan Sayfa', 'pratikwp'); ?></div>
                            </div>
                        </div>

                        <div class="stat-card comments">
                            <div class="stat-icon">
                                <i class="dashicons dashicons-admin-comments"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="comments-count"><?php echo wp_count_comments()->approved; ?></div>
                                <div class="stat-label"><?php esc_html_e('Onaylanan Yorum', 'pratikwp'); ?></div>
                            </div>
                        </div>

                        <div class="stat-card users">
                            <div class="stat-icon">
                                <i class="dashicons dashicons-admin-users"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="users-count"><?php echo count_users()['total_users']; ?></div>
                                <div class="stat-label"><?php esc_html_e('Toplam Kullanıcı', 'pratikwp'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="dashboard-section system-status">
                    <div class="section-header">
                        <h3><?php esc_html_e('Sistem Durumu', 'pratikwp'); ?></h3>
                        <button type="button" class="button button-secondary" id="system-check">
                            <?php esc_html_e('Kontrol Et', 'pratikwp'); ?>
                        </button>
                    </div>
                    <div class="system-checks" id="system-checks-container">
                        <?php $this->render_system_checks(); ?>
                    </div>
                </div>

                <!-- Performance Monitor -->
                <div class="dashboard-section performance-monitor">
                    <div class="section-header">
                        <h3><?php esc_html_e('Performans İzleyici', 'pratikwp'); ?></h3>
                        <div class="performance-score" id="performance-score">
                            <span class="score-number">--</span>
                            <span class="score-label">/100</span>
                        </div>
                    </div>
                    <div class="performance-metrics">
                        <div class="metric">
                            <span class="metric-label"><?php esc_html_e('Sayfa Yükleme Hızı', 'pratikwp'); ?></span>
                            <span class="metric-value" id="page-load-time">-- ms</span>
                        </div>
                        <div class="metric">
                            <span class="metric-label"><?php esc_html_e('Veritabanı Sorgu Sayısı', 'pratikwp'); ?></span>
                            <span class="metric-value" id="db-queries"><?php echo get_num_queries(); ?></span>
                        </div>
                        <div class="metric">
                            <span class="metric-label"><?php esc_html_e('Bellek Kullanımı', 'pratikwp'); ?></span>
                            <span class="metric-value" id="memory-usage"><?php echo size_format(memory_get_peak_usage(true)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="dashboard-section quick-actions">
                    <div class="section-header">
                        <h3><?php esc_html_e('Hızlı İşlemler', 'pratikwp'); ?></h3>
                    </div>
                    <div class="actions-grid">
                        <button type="button" class="action-btn clear-cache" data-action="clear_cache">
                            <i class="dashicons dashicons-trash"></i>
                            <span><?php esc_html_e('Cache Temizle', 'pratikwp'); ?></span>
                        </button>
                        <button type="button" class="action-btn optimize-db" data-action="optimize_database">
                            <i class="dashicons dashicons-database-view"></i>
                            <span><?php esc_html_e('Veritabanı Optimize Et', 'pratikwp'); ?></span>
                        </button>
                        <button type="button" class="action-btn export-settings" data-action="export_settings">
                            <i class="dashicons dashicons-download"></i>
                            <span><?php esc_html_e('Ayarları Dışa Aktar', 'pratikwp'); ?></span>
                        </button>
                        <button type="button" class="action-btn check-updates" data-action="check_updates">
                            <i class="dashicons dashicons-update"></i>
                            <span><?php esc_html_e('Güncellemeleri Kontrol Et', 'pratikwp'); ?></span>
                        </button>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-section recent-activity">
                    <div class="section-header">
                        <h3><?php esc_html_e('Son Aktiviteler', 'pratikwp'); ?></h3>
                    </div>
                    <div class="activity-list">
                        <?php $this->render_recent_activity(); ?>
                    </div>
                </div>

                <!-- Theme Information -->
                <div class="dashboard-section theme-info">
                    <div class="section-header">
                        <h3><?php esc_html_e('Tema Bilgileri', 'pratikwp'); ?></h3>
                    </div>
                    <div class="theme-details">
                        <div class="theme-logo">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="PratikWp" />
                        </div>
                        <div class="theme-meta">
                            <h4>PratikWp</h4>
                            <p class="version"><?php esc_html_e('Sürüm:', 'pratikwp'); ?> <?php echo PRATIKWP_VERSION; ?></p>
                            <p class="description"><?php esc_html_e('Modern ve performans odaklı WordPress teması', 'pratikwp'); ?></p>
                            <div class="theme-links">
                                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary">
                                    <?php esc_html_e('Özelleştir', 'pratikwp'); ?>
                                </a>
                                <a href="https://pratikwp.com/docs" target="_blank" class="button button-secondary">
                                    <?php esc_html_e('Dokümantasyon', 'pratikwp'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- News & Updates -->
                <div class="dashboard-section news-updates">
                    <div class="section-header">
                        <h3><?php esc_html_e('Haberler ve Güncellemeler', 'pratikwp'); ?></h3>
                    </div>
                    <div class="news-list" id="news-list">
                        <?php $this->render_news_feed(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div class="pratikwp-loading-overlay" style="display: none;">
            <div class="loading-spinner"></div>
            <div class="loading-text"><?php esc_html_e('İşlem yapılıyor...', 'pratikwp'); ?></div>
        </div>
        <?php
    }

    /**
     * Render system checks
     */
    private function render_system_checks() {
        $checks = [
            'php_version' => [
                'label' => 'PHP Sürümü',
                'status' => version_compare(PHP_VERSION, '7.4', '>=') ? 'good' : 'warning',
                'value' => PHP_VERSION,
                'message' => version_compare(PHP_VERSION, '7.4', '>=') ? 'Uygun' : 'PHP 7.4 veya üzeri öneriliyor',
            ],
            'wp_version' => [
                'label' => 'WordPress Sürümü',
                'status' => version_compare(get_bloginfo('version'), '5.0', '>=') ? 'good' : 'warning',
                'value' => get_bloginfo('version'),
                'message' => version_compare(get_bloginfo('version'), '5.0', '>=') ? 'Güncel' : 'WordPress 5.0 veya üzeri gerekli',
            ],
            'memory_limit' => [
                'label' => 'PHP Bellek Limiti',
                'status' => $this->check_memory_limit(),
                'value' => ini_get('memory_limit'),
                'message' => $this->get_memory_limit_message(),
            ],
            'max_upload_size' => [
                'label' => 'Maksimum Upload Boyutu',
                'status' => $this->check_upload_size(),
                'value' => size_format(wp_max_upload_size()),
                'message' => $this->get_upload_size_message(),
            ],
            'elementor_status' => [
                'label' => 'Elementor Durumu',
                'status' => is_plugin_active('elementor/elementor.php') ? 'good' : 'error',
                'value' => is_plugin_active('elementor/elementor.php') ? 'Aktif' : 'İnaktif',
                'message' => is_plugin_active('elementor/elementor.php') ? 'Elementor yüklü ve aktif' : 'Elementor eklentisi gerekli',
            ],
        ];

        foreach ($checks as $check_id => $check) {
            ?>
            <div class="system-check <?php echo esc_attr($check['status']); ?>">
                <div class="check-icon">
                    <i class="dashicons dashicons-<?php echo $check['status'] === 'good' ? 'yes-alt' : ($check['status'] === 'warning' ? 'warning' : 'dismiss'); ?>"></i>
                </div>
                <div class="check-content">
                    <div class="check-label"><?php echo esc_html($check['label']); ?></div>
                    <div class="check-value"><?php echo esc_html($check['value']); ?></div>
                    <div class="check-message"><?php echo esc_html($check['message']); ?></div>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render recent activity
     */
    private function render_recent_activity() {
        $activities = [
            [
                'type' => 'post',
                'title' => 'Yeni yazı yayınlandı',
                'description' => 'Son blog yazınız başarıyla yayınlandı',
                'time' => '2 saat önce',
                'icon' => 'admin-post'
            ],
            [
                'type' => 'comment',
                'title' => 'Yeni yorum onaylandı',
                'description' => '3 yeni yorum onaylandı',
                'time' => '4 saat önce',
                'icon' => 'admin-comments'
            ],
            [
                'type' => 'update',
                'title' => 'Tema güncellendi',
                'description' => 'PratikWp v1.0.2 sürümüne güncellendi',
                'time' => '1 gün önce',
                'icon' => 'update'
            ],
        ];

        foreach ($activities as $activity) {
            ?>
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="dashicons dashicons-<?php echo esc_attr($activity['icon']); ?>"></i>
                </div>
                <div class="activity-content">
                    <div class="activity-title"><?php echo esc_html($activity['title']); ?></div>
                    <div class="activity-description"><?php echo esc_html($activity['description']); ?></div>
                    <div class="activity-time"><?php echo esc_html($activity['time']); ?></div>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render news feed
     */
    private function render_news_feed() {
        $news = [
            [
                'title' => 'PratikWp v1.0.3 Güncelleme Notları',
                'excerpt' => 'Yeni özellikler ve hata düzeltmeleri ile daha iyi performans.',
                'date' => '15 Mart 2024',
                'link' => '#'
            ],
            [
                'title' => 'Elementor Pro Uyumluluk Güncellemesi',
                'excerpt' => 'Elementor Pro\'nun son sürümü ile tam uyumluluk sağlandı.',
                'date' => '10 Mart 2024',
                'link' => '#'
            ],
            [
                'title' => 'Yeni Widget\'lar Eklendi',
                'excerpt' => 'Sosyal medya ve firma bilgileri widget\'ları tema ile birlikte geliyor.',
                'date' => '5 Mart 2024',
                'link' => '#'
            ],
        ];

        foreach ($news as $item) {
            ?>
            <div class="news-item">
                <div class="news-content">
                    <h4 class="news-title"><?php echo esc_html($item['title']); ?></h4>
                    <p class="news-excerpt"><?php echo esc_html($item['excerpt']); ?></p>
                    <div class="news-meta">
                        <span class="news-date"><?php echo esc_html($item['date']); ?></span>
                        <a href="<?php echo esc_url($item['link']); ?>" class="news-link"><?php esc_html_e('Devamını Oku', 'pratikwp'); ?></a>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Check memory limit
     */
    private function check_memory_limit() {
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        return $memory_limit >= 134217728 ? 'good' : 'warning'; // 128MB
    }

    /**
     * Get memory limit message
     */
    private function get_memory_limit_message() {
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        return $memory_limit >= 134217728 ? 'Yeterli bellek' : '128MB veya üzeri öneriliyor';
    }

    /**
     * Check upload size
     */
    private function check_upload_size() {
        $upload_size = wp_max_upload_size();
        return $upload_size >= 8388608 ? 'good' : 'warning'; // 8MB
    }

    /**
     * Get upload size message
     */
    private function get_upload_size_message() {
        $upload_size = wp_max_upload_size();
        return $upload_size >= 8388608 ? 'Yeterli upload boyutu' : '8MB veya üzeri öneriliyor';
    }

    /**
     * AJAX: Get dashboard stats
     */
    public function get_dashboard_stats() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        $stats = [
            'posts' => wp_count_posts()->publish,
            'pages' => wp_count_posts('page')->publish,
            'comments' => wp_count_comments()->approved,
            'users' => count_users()['total_users'],
            'db_queries' => get_num_queries(),
            'memory_usage' => memory_get_peak_usage(true),
            'performance_score' => $this->calculate_performance_score(),
        ];

        wp_send_json_success($stats);
    }

    /**
     * AJAX: Run system check
     */
    public function run_system_check() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        ob_start();
        $this->render_system_checks();
        $html = ob_get_clean();

        wp_send_json_success(['html' => $html]);
    }

    /**
     * AJAX: Clear cache
     */
    public function clear_cache() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        // Clear WordPress cache
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        // Clear Elementor cache
        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }

        // Clear other cache plugins
        $this->clear_third_party_cache();

        wp_send_json_success(['message' => __('Cache başarıyla temizlendi', 'pratikwp')]);
    }

    /**
     * AJAX: Optimize database
     */
    public function optimize_database() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        global $wpdb;

        // Optimize database tables
        $tables = $wpdb->get_results("SHOW TABLES", ARRAY_N);
        $optimized_tables = 0;

        foreach ($tables as $table) {
            $table_name = $table[0];
            $result = $wpdb->query("OPTIMIZE TABLE `{$table_name}`");
            if ($result) {
                $optimized_tables++;
            }
        }

        wp_send_json_success([
            'message' => sprintf(__('%d tablo optimize edildi', 'pratikwp'), $optimized_tables)
        ]);
    }

    /**
     * Calculate performance score
     */
    private function calculate_performance_score() {
        $score = 100;

        // Memory usage check
        $memory_usage = memory_get_peak_usage(true);
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        $memory_percentage = ($memory_usage / $memory_limit) * 100;

        if ($memory_percentage > 80) {
            $score -= 20;
        } elseif ($memory_percentage > 60) {
            $score -= 10;
        }

        // Query count check
        $query_count = get_num_queries();
        if ($query_count > 50) {
            $score -= 15;
        } elseif ($query_count > 30) {
            $score -= 8;
        }

        // Plugin count check
        $active_plugins = count(get_option('active_plugins'));
        if ($active_plugins > 30) {
            $score -= 10;
        } elseif ($active_plugins > 20) {
            $score -= 5;
        }

        return max($score, 0);
    }

    /**
     * Clear third party cache
     */
    private function clear_third_party_cache() {
        // WP Rocket
        if (function_exists('rocket_clean_domain')) {
            rocket_clean_domain();
        }

        // W3 Total Cache
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
        }

        // WP Super Cache
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }

        // LiteSpeed Cache
        if (class_exists('LiteSpeed\Purge')) {
            LiteSpeed\Purge::purge_all();
        }
    }
}