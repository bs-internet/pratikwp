<?php
/**
 * Admin System Info Page
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Admin System Info handler
 */
class PratikWp_System_Info {

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_pratikwp_export_system_info', [$this, 'export_system_info']);
        add_action('wp_ajax_pratikwp_run_performance_test', [$this, 'run_performance_test']);
        add_action('wp_ajax_pratikwp_check_file_permissions', [$this, 'check_file_permissions']);
    }

    /**
     * Render system info page
     */
    public function render_page() {
        ?>
        <div class="wrap pratikwp-admin-page pratikwp-system-info">
            <div class="pratikwp-system-header">
                <h1><?php esc_html_e('Sistem Bilgileri', 'pratikwp'); ?></h1>
                <p class="system-description"><?php esc_html_e('Sitenizin teknik detayları, performans bilgileri ve sistem durumu', 'pratikwp'); ?></p>
                <div class="header-actions">
                    <button type="button" class="button button-primary" id="export-system-info">
                        <i class="dashicons dashicons-download"></i>
                        <?php esc_html_e('Sistem Bilgilerini Dışa Aktar', 'pratikwp'); ?>
                    </button>
                    <button type="button" class="button button-secondary" id="run-performance-test">
                        <i class="dashicons dashicons-performance"></i>
                        <?php esc_html_e('Performans Testi Çalıştır', 'pratikwp'); ?>
                    </button>
                </div>
            </div>

            <div class="system-info-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#system-overview" class="nav-tab nav-tab-active"><?php esc_html_e('Sistem Özeti', 'pratikwp'); ?></a>
                    <a href="#server-info" class="nav-tab"><?php esc_html_e('Sunucu Bilgileri', 'pratikwp'); ?></a>
                    <a href="#wordpress-info" class="nav-tab"><?php esc_html_e('WordPress Bilgileri', 'pratikwp'); ?></a>
                    <a href="#theme-info" class="nav-tab"><?php esc_html_e('Tema Bilgileri', 'pratikwp'); ?></a>
                    <a href="#plugin-info" class="nav-tab"><?php esc_html_e('Eklenti Bilgileri', 'pratikwp'); ?></a>
                    <a href="#performance-info" class="nav-tab"><?php esc_html_e('Performans', 'pratikwp'); ?></a>
                    <a href="#security-info" class="nav-tab"><?php esc_html_e('Güvenlik', 'pratikwp'); ?></a>
                </nav>

                <!-- System Overview Tab -->
                <div id="system-overview" class="tab-content active">
                    <div class="system-overview-grid">
                        <div class="overview-card server-status">
                            <div class="card-header">
                                <h3><?php esc_html_e('Sunucu Durumu', 'pratikwp'); ?></h3>
                                <div class="status-indicator <?php echo $this->get_server_status_class(); ?>">
                                    <?php echo $this->get_server_status_text(); ?>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('PHP Sürümü:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo PHP_VERSION; ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('MySQL Sürümü:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo $this->get_mysql_version(); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('Web Sunucu:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo $this->get_web_server(); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="overview-card wp-status">
                            <div class="card-header">
                                <h3><?php esc_html_e('WordPress Durumu', 'pratikwp'); ?></h3>
                                <div class="status-indicator <?php echo $this->get_wp_status_class(); ?>">
                                    <?php echo $this->get_wp_status_text(); ?>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('WP Sürümü:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo get_bloginfo('version'); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('Multisite:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo is_multisite() ? __('Evet', 'pratikwp') : __('Hayır', 'pratikwp'); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('Debug Modu:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo WP_DEBUG ? __('Aktif', 'pratikwp') : __('İnaktif', 'pratikwp'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="overview-card performance-status">
                            <div class="card-header">
                                <h3><?php esc_html_e('Performans Durumu', 'pratikwp'); ?></h3>
                                <div class="performance-score">
                                    <span class="score"><?php echo $this->calculate_performance_score(); ?></span>
                                    <span class="score-label">/100</span>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('Bellek Kullanımı:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo $this->get_memory_usage(); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('Sorgu Sayısı:', 'pratikwp'); ?></span>
                                    <span class="value"><?php echo get_num_queries(); ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="label"><?php esc_html_e('Yükleme Süresi:', 'pratikwp'); ?></span>
                                    <span class="value" id="page-load-time">--</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Health Checks -->
                    <div class="system-health-section">
                        <h3><?php esc_html_e('Sistem Sağlık Kontrolleri', 'pratikwp'); ?></h3>
                        <div class="health-checks-grid">
                            <?php $this->render_health_checks(); ?>
                        </div>
                    </div>
                </div>

                <!-- Server Info Tab -->
                <div id="server-info" class="tab-content">
                    <div class="info-table-wrapper">
                        <table class="system-info-table">
                            <tbody>
                                <tr>
                                    <td class="label"><?php esc_html_e('İşletim Sistemi:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo PHP_OS; ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Web Sunucu:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo $this->get_web_server(); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('PHP Sürümü:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo PHP_VERSION; ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('PHP SAPI:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo php_sapi_name(); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('MySQL Sürümü:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo $this->get_mysql_version(); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Bellek Limiti:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo ini_get('memory_limit'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Maksimum Yürütme Süresi:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo ini_get('max_execution_time') . ' ' . __('saniye', 'pratikwp'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Maksimum Upload Boyutu:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo size_format(wp_max_upload_size()); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('POST Maksimum Boyut:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo ini_get('post_max_size'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Maksimum Input Değişken:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo ini_get('max_input_vars'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- PHP Extensions -->
                    <div class="php-extensions-section">
                        <h3><?php esc_html_e('PHP Eklentileri', 'pratikwp'); ?></h3>
                        <div class="extensions-grid">
                            <?php $this->render_php_extensions(); ?>
                        </div>
                    </div>
                </div>

                <!-- WordPress Info Tab -->
                <div id="wordpress-info" class="tab-content">
                    <div class="info-table-wrapper">
                        <table class="system-info-table">
                            <tbody>
                                <tr>
                                    <td class="label"><?php esc_html_e('WordPress Sürümü:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo get_bloginfo('version'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Site URL:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo home_url(); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('WordPress URL:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo site_url(); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Content Dizini:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo WP_CONTENT_DIR; ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Uploads Dizini:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo wp_upload_dir()['basedir']; ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Multisite:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo is_multisite() ? __('Evet', 'pratikwp') : __('Hayır', 'pratikwp'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Debug Modu:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo WP_DEBUG ? __('Aktif', 'pratikwp') : __('İnaktif', 'pratikwp'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Script Debug:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? __('Aktif', 'pratikwp') : __('İnaktif', 'pratikwp'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('WP Cache:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo (defined('WP_CACHE') && WP_CACHE) ? __('Aktif', 'pratikwp') : __('İnaktif', 'pratikwp'); ?></td>
                                </tr>
                                <tr>
                                    <td class="label"><?php esc_html_e('Revizyon Sayısı:', 'pratikwp'); ?></td>
                                    <td class="value"><?php echo (defined('WP_POST_REVISIONS') ? WP_POST_REVISIONS : __('Sınırsız', 'pratikwp')); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- WordPress Constants -->
                    <div class="wp-constants-section">
                        <h3><?php esc_html_e('WordPress Sabitleri', 'pratikwp'); ?></h3>
                        <div class="constants-grid">
                            <?php $this->render_wp_constants(); ?>
                        </div>
                    </div>
                </div>

                <!-- Theme Info Tab -->
                <div id="theme-info" class="tab-content">
                    <div class="theme-details-section">
                        <?php $this->render_theme_details(); ?>
                    </div>
                </div>

                <!-- Plugin Info Tab -->
                <div id="plugin-info" class="tab-content">
                    <div class="plugins-section">
                        <div class="active-plugins">
                            <h3><?php esc_html_e('Aktif Eklentiler', 'pratikwp'); ?> (<?php echo count(get_option('active_plugins')); ?>)</h3>
                            <div class="plugins-list">
                                <?php $this->render_active_plugins(); ?>
                            </div>
                        </div>

                        <div class="inactive-plugins">
                            <h3><?php esc_html_e('İnaktif Eklentiler', 'pratikwp'); ?></h3>
                            <div class="plugins-list">
                                <?php $this->render_inactive_plugins(); ?>
                            </div>
                        </div>

                        <div class="must-use-plugins">
                            <h3><?php esc_html_e('Zorunlu Eklentiler', 'pratikwp'); ?></h3>
                            <div class="plugins-list">
                                <?php $this->render_must_use_plugins(); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Tab -->
                <div id="performance-info" class="tab-content">
                    <div class="performance-metrics">
                        <div class="metrics-grid">
                            <div class="metric-card">
                                <h4><?php esc_html_e('Bellek Kullanımı', 'pratikwp'); ?></h4>
                                <div class="metric-value"><?php echo $this->get_memory_usage(); ?></div>
                                <div class="metric-description"><?php echo $this->get_memory_limit(); ?> <?php esc_html_e('limitinden', 'pratikwp'); ?></div>
                            </div>
                            <div class="metric-card">
                                <h4><?php esc_html_e('Veritabanı Sorguları', 'pratikwp'); ?></h4>
                                <div class="metric-value"><?php echo get_num_queries(); ?></div>
                                <div class="metric-description"><?php echo timer_stop(0, 3); ?> <?php esc_html_e('saniye', 'pratikwp'); ?></div>
                            </div>
                            <div class="metric-card">
                                <h4><?php esc_html_e('Veritabanı Boyutu', 'pratikwp'); ?></h4>
                                <div class="metric-value"><?php echo $this->get_database_size(); ?></div>
                                <div class="metric-description"><?php esc_html_e('Toplam boyut', 'pratikwp'); ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="performance-test-section">
                        <h3><?php esc_html_e('Performans Testi', 'pratikwp'); ?></h3>
                        <p><?php esc_html_e('Sitenizin performansını test etmek için aşağıdaki butonu kullanın.', 'pratikwp'); ?></p>
                        <button type="button" class="button button-primary" id="start-performance-test">
                            <?php esc_html_e('Performans Testini Başlat', 'pratikwp'); ?>
                        </button>
                        <div class="performance-results" id="performance-results" style="display: none;"></div>
                    </div>
                </div>

                <!-- Security Tab -->
                <div id="security-info" class="tab-content">
                    <div class="security-checks">
                        <h3><?php esc_html_e('Güvenlik Kontrolleri', 'pratikwp'); ?></h3>
                        <div class="security-grid">
                            <?php $this->render_security_checks(); ?>
                        </div>
                    </div>

                    <div class="file-permissions-section">
                        <h3><?php esc_html_e('Dosya İzinleri', 'pratikwp'); ?></h3>
                        <button type="button" class="button button-secondary" id="check-file-permissions">
                            <?php esc_html_e('Dosya İzinlerini Kontrol Et', 'pratikwp'); ?>
                        </button>
                        <div class="permissions-results" id="permissions-results"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Render health checks
     */
    private function render_health_checks() {
        $checks = [
            'php_version' => [
                'title' => 'PHP Sürümü',
                'status' => version_compare(PHP_VERSION, '7.4', '>=') ? 'good' : 'warning',
                'message' => version_compare(PHP_VERSION, '7.4', '>=') ? 
                    'PHP sürümünüz güncel (' . PHP_VERSION . ')' : 
                    'PHP 7.4 veya üzeri öneriliyor (' . PHP_VERSION . ')',
            ],
            'memory_limit' => [
                'title' => 'Bellek Limiti',
                'status' => $this->check_memory_limit(),
                'message' => $this->get_memory_limit_message(),
            ],
            'max_upload_size' => [
                'title' => 'Upload Boyutu',
                'status' => wp_max_upload_size() >= 8388608 ? 'good' : 'warning',
                'message' => 'Maksimum upload: ' . size_format(wp_max_upload_size()),
            ],
            'wp_version' => [
                'title' => 'WordPress Sürümü',
                'status' => version_compare(get_bloginfo('version'), '5.0', '>=') ? 'good' : 'error',
                'message' => 'WordPress ' . get_bloginfo('version'),
            ],
        ];

        foreach ($checks as $check_id => $check) {
            ?>
            <div class="health-check-item <?php echo esc_attr($check['status']); ?>">
                <div class="check-icon">
                    <i class="dashicons dashicons-<?php echo $check['status'] === 'good' ? 'yes-alt' : ($check['status'] === 'warning' ? 'warning' : 'dismiss'); ?>"></i>
                </div>
                <div class="check-content">
                    <h4><?php echo esc_html($check['title']); ?></h4>
                    <p><?php echo esc_html($check['message']); ?></p>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render PHP extensions
     */
    private function render_php_extensions() {
        $required_extensions = [
            'curl' => 'cURL',
            'gd' => 'GD',
            'mbstring' => 'Multibyte String',
            'xml' => 'XML',
            'zip' => 'Zip',
            'json' => 'JSON',
            'mysqli' => 'MySQLi',
            'openssl' => 'OpenSSL',
        ];

        foreach ($required_extensions as $extension => $name) {
            $loaded = extension_loaded($extension);
            ?>
            <div class="extension-item <?php echo $loaded ? 'loaded' : 'missing'; ?>">
                <div class="extension-icon">
                    <i class="dashicons dashicons-<?php echo $loaded ? 'yes-alt' : 'dismiss'; ?>"></i>
                </div>
                <div class="extension-name"><?php echo esc_html($name); ?></div>
            </div>
            <?php
        }
    }

    /**
     * Render WordPress constants
     */
    private function render_wp_constants() {
        $constants = [
            'WP_DEBUG' => WP_DEBUG,
            'WP_DEBUG_LOG' => defined('WP_DEBUG_LOG') ? WP_DEBUG_LOG : false,
            'WP_DEBUG_DISPLAY' => defined('WP_DEBUG_DISPLAY') ? WP_DEBUG_DISPLAY : true,
            'SCRIPT_DEBUG' => defined('SCRIPT_DEBUG') ? SCRIPT_DEBUG : false,
            'WP_CACHE' => defined('WP_CACHE') ? WP_CACHE : false,
            'CONCATENATE_SCRIPTS' => defined('CONCATENATE_SCRIPTS') ? CONCATENATE_SCRIPTS : true,
            'COMPRESS_SCRIPTS' => defined('COMPRESS_SCRIPTS') ? COMPRESS_SCRIPTS : true,
            'COMPRESS_CSS' => defined('COMPRESS_CSS') ? COMPRESS_CSS : true,
        ];

        foreach ($constants as $constant => $value) {
            ?>
            <div class="constant-item">
                <div class="constant-name"><?php echo esc_html($constant); ?></div>
                <div class="constant-value <?php echo $value ? 'enabled' : 'disabled'; ?>">
                    <?php echo $value ? __('Aktif', 'pratikwp') : __('İnaktif', 'pratikwp'); ?>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render theme details
     */
    private function render_theme_details() {
        $theme = wp_get_theme();
        ?>
        <div class="theme-info-card">
            <div class="theme-screenshot">
                <?php if ($theme->get_screenshot()): ?>
                    <img src="<?php echo esc_url($theme->get_screenshot()); ?>" alt="<?php echo esc_attr($theme->get('Name')); ?>" />
                <?php endif; ?>
            </div>
            <div class="theme-details">
                <h3><?php echo esc_html($theme->get('Name')); ?></h3>
                <div class="theme-meta">
                    <div class="meta-item">
                        <strong><?php esc_html_e('Sürüm:', 'pratikwp'); ?></strong>
                        <span><?php echo esc_html($theme->get('Version')); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong><?php esc_html_e('Yazar:', 'pratikwp'); ?></strong>
                        <span><?php echo wp_kses_post($theme->get('Author')); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong><?php esc_html_e('Açıklama:', 'pratikwp'); ?></strong>
                        <span><?php echo wp_kses_post($theme->get('Description')); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong><?php esc_html_e('Tema Dizini:', 'pratikwp'); ?></strong>
                        <span><?php echo esc_html(get_template_directory()); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Child Theme Info -->
        <?php if (is_child_theme()): ?>
            <div class="child-theme-info">
                <h4><?php esc_html_e('Child Theme Bilgileri', 'pratikwp'); ?></h4>
                <div class="child-theme-details">
                    <div class="meta-item">
                        <strong><?php esc_html_e('Child Theme:', 'pratikwp'); ?></strong>
                        <span><?php echo esc_html(get_stylesheet()); ?></span>
                    </div>
                    <div class="meta-item">
                        <strong><?php esc_html_e('Parent Theme:', 'pratikwp'); ?></strong>
                        <span><?php echo esc_html(get_template()); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
    }

    /**
     * Render active plugins
     */
    private function render_active_plugins() {
        $active_plugins = get_option('active_plugins');
        
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            ?>
            <div class="plugin-item active">
                <div class="plugin-info">
                    <h4><?php echo esc_html($plugin_data['Name']); ?></h4>
                    <div class="plugin-meta">
                        <span class="version">v<?php echo esc_html($plugin_data['Version']); ?></span>
                        <span class="author"><?php esc_html_e('by', 'pratikwp'); ?> <?php echo wp_kses_post($plugin_data['Author']); ?></span>
                    </div>
                </div>
                <div class="plugin-status">
                    <span class="status-badge active"><?php esc_html_e('Aktif', 'pratikwp'); ?></span>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render inactive plugins
     */
    private function render_inactive_plugins() {
        $all_plugins = get_plugins();
        $active_plugins = get_option('active_plugins');
        
        foreach ($all_plugins as $plugin_file => $plugin_data) {
            if (!in_array($plugin_file, $active_plugins)) {
                ?>
                <div class="plugin-item inactive">
                    <div class="plugin-info">
                        <h4><?php echo esc_html($plugin_data['Name']); ?></h4>
                        <div class="plugin-meta">
                            <span class="version">v<?php echo esc_html($plugin_data['Version']); ?></span>
                            <span class="author"><?php esc_html_e('by', 'pratikwp'); ?> <?php echo wp_kses_post($plugin_data['Author']); ?></span>
                        </div>
                    </div>
                    <div class="plugin-status">
                        <span class="status-badge inactive"><?php esc_html_e('İnaktif', 'pratikwp'); ?></span>
                    </div>
                </div>
                <?php
            }
        }
    }

    /**
     * Render must-use plugins
     */
    private function render_must_use_plugins() {
        $mu_plugins = get_mu_plugins();
        
        if (empty($mu_plugins)) {
            echo '<p>' . esc_html__('Zorunlu eklenti bulunmuyor.', 'pratikwp') . '</p>';
            return;
        }
        
        foreach ($mu_plugins as $plugin_file => $plugin_data) {
            ?>
            <div class="plugin-item must-use">
                <div class="plugin-info">
                    <h4><?php echo esc_html($plugin_data['Name']); ?></h4>
                    <div class="plugin-meta">
                        <span class="version">v<?php echo esc_html($plugin_data['Version']); ?></span>
                        <span class="author"><?php esc_html_e('by', 'pratikwp'); ?> <?php echo wp_kses_post($plugin_data['Author']); ?></span>
                    </div>
                </div>
                <div class="plugin-status">
                    <span class="status-badge must-use"><?php esc_html_e('Zorunlu', 'pratikwp'); ?></span>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Render security checks
     */
    private function render_security_checks() {
        $checks = [
            'wp_version' => [
                'title' => 'WordPress Sürüm Gizliliği',
                'status' => !has_action('wp_head', 'wp_generator') ? 'good' : 'warning',
                'message' => !has_action('wp_head', 'wp_generator') ? 
                    'WordPress sürüm bilgisi gizli' : 
                    'WordPress sürüm bilgisi görünür',
            ],
            'debug_mode' => [
                'title' => 'Debug Modu',
                'status' => !WP_DEBUG ? 'good' : 'warning',
                'message' => !WP_DEBUG ? 
                    'Debug modu kapalı' : 
                    'Debug modu açık (üretimde kapatılmalı)',
            ],
            'file_editing' => [
                'title' => 'Dosya Düzenleme',
                'status' => defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT ? 'good' : 'warning',
                'message' => defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT ? 
                    'Dosya düzenleme devre dışı' : 
                    'Dosya düzenleme aktif',
            ],
            'ssl_status' => [
                'title' => 'SSL Durumu',
                'status' => is_ssl() ? 'good' : 'warning',
                'message' => is_ssl() ? 
                    'SSL aktif' : 
                    'SSL kullanılmıyor',
            ],
            'admin_user' => [
                'title' => 'Admin Kullanıcısı',
                'status' => !username_exists('admin') ? 'good' : 'warning',
                'message' => !username_exists('admin') ? 
                    'Varsayılan admin kullanıcısı yok' : 
                    'Varsayılan admin kullanıcısı mevcut',
            ],
        ];

        foreach ($checks as $check_id => $check) {
            ?>
            <div class="security-check-item <?php echo esc_attr($check['status']); ?>">
                <div class="check-icon">
                    <i class="dashicons dashicons-<?php echo $check['status'] === 'good' ? 'yes-alt' : 'warning'; ?>"></i>
                </div>
                <div class="check-content">
                    <h4><?php echo esc_html($check['title']); ?></h4>
                    <p><?php echo esc_html($check['message']); ?></p>
                </div>
            </div>
            <?php
        }
    }

    /**
     * Get server status class
     */
    private function get_server_status_class() {
        $php_version = version_compare(PHP_VERSION, '7.4', '>=');
        $memory_ok = $this->check_memory_limit() !== 'error';
        
        if ($php_version && $memory_ok) {
            return 'good';
        } elseif ($php_version || $memory_ok) {
            return 'warning';
        } else {
            return 'error';
        }
    }

    /**
     * Get server status text
     */
    private function get_server_status_text() {
        $class = $this->get_server_status_class();
        
        switch ($class) {
            case 'good':
                return __('Mükemmel', 'pratikwp');
            case 'warning':
                return __('İyileştirilebilir', 'pratikwp');
            default:
                return __('Sorunlu', 'pratikwp');
        }
    }

    /**
     * Get WordPress status class
     */
    private function get_wp_status_class() {
        $wp_version = version_compare(get_bloginfo('version'), '5.0', '>=');
        $debug_off = !WP_DEBUG;
        
        if ($wp_version && $debug_off) {
            return 'good';
        } elseif ($wp_version) {
            return 'warning';
        } else {
            return 'error';
        }
    }

    /**
     * Get WordPress status text
     */
    private function get_wp_status_text() {
        $class = $this->get_wp_status_class();
        
        switch ($class) {
            case 'good':
                return __('Optimize', 'pratikwp');
            case 'warning':
                return __('İyileştirilebilir', 'pratikwp');
            default:
                return __('Güncellemeli', 'pratikwp');
        }
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

        // PHP version check
        if (version_compare(PHP_VERSION, '8.0', '<')) {
            $score -= 10;
        }

        return max($score, 0);
    }

    /**
     * Get memory usage
     */
    private function get_memory_usage() {
        $memory_usage = memory_get_peak_usage(true);
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        $percentage = round(($memory_usage / $memory_limit) * 100, 1);
        
        return size_format($memory_usage) . ' (' . $percentage . '%)';
    }

    /**
     * Get memory limit
     */
    private function get_memory_limit() {
        return ini_get('memory_limit');
    }

    /**
     * Check memory limit
     */
    private function check_memory_limit() {
        $memory_limit = wp_convert_hr_to_bytes(ini_get('memory_limit'));
        
        if ($memory_limit >= 268435456) { // 256MB
            return 'good';
        } elseif ($memory_limit >= 134217728) { // 128MB
            return 'warning';
        } else {
            return 'error';
        }
    }

    /**
     * Get memory limit message
     */
    private function get_memory_limit_message() {
        $status = $this->check_memory_limit();
        $limit = ini_get('memory_limit');
        
        switch ($status) {
            case 'good':
                return sprintf(__('Yeterli bellek (%s)', 'pratikwp'), $limit);
            case 'warning':
                return sprintf(__('Kabul edilebilir (%s)', 'pratikwp'), $limit);
            default:
                return sprintf(__('Yetersiz bellek (%s)', 'pratikwp'), $limit);
        }
    }

    /**
     * Get MySQL version
     */
    private function get_mysql_version() {
        global $wpdb;
        return $wpdb->db_version();
    }

    /**
     * Get web server
     */
    private function get_web_server() {
        return isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : __('Bilinmiyor', 'pratikwp');
    }

    /**
     * Get database size
     */
    private function get_database_size() {
        global $wpdb;
        
        $size = $wpdb->get_var("
            SELECT SUM(data_length + index_length) 
            FROM information_schema.TABLES 
            WHERE table_schema = '{$wpdb->dbname}'
        ");
        
        return $size ? size_format($size) : __('Hesaplanamadı', 'pratikwp');
    }

    /**
     * AJAX: Export system info
     */
    public function export_system_info() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        $system_info = $this->collect_system_info();
        
        $filename = 'pratikwp-system-info-' . date('Y-m-d-H-i-s') . '.txt';
        
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($system_info));
        
        echo $system_info;
        exit;
    }

    /**
     * Collect all system information
     */
    private function collect_system_info() {
        $info = [];
        
        // Site info
        $info[] = "=== SITE BİLGİLERİ ===";
        $info[] = "Site URL: " . home_url();
        $info[] = "WordPress URL: " . site_url();
        $info[] = "Tema: " . wp_get_theme()->get('Name') . ' v' . wp_get_theme()->get('Version');
        $info[] = "";
        
        // WordPress info
        $info[] = "=== WORDPRESS BİLGİLERİ ===";
        $info[] = "WordPress Sürümü: " . get_bloginfo('version');
        $info[] = "Multisite: " . (is_multisite() ? 'Evet' : 'Hayır');
        $info[] = "Debug Modu: " . (WP_DEBUG ? 'Aktif' : 'İnaktif');
        $info[] = "Dil: " . get_locale();
        $info[] = "";
        
        // Server info
        $info[] = "=== SUNUCU BİLGİLERİ ===";
        $info[] = "PHP Sürümü: " . PHP_VERSION;
        $info[] = "MySQL Sürümü: " . $this->get_mysql_version();
        $info[] = "Web Sunucu: " . $this->get_web_server();
        $info[] = "İşletim Sistemi: " . PHP_OS;
        $info[] = "Bellek Limiti: " . ini_get('memory_limit');
        $info[] = "Maksimum Yürütme Süresi: " . ini_get('max_execution_time') . ' saniye';
        $info[] = "Maksimum Upload: " . size_format(wp_max_upload_size());
        $info[] = "";
        
        // Active plugins
        $info[] = "=== AKTİF EKLENTİLER ===";
        $active_plugins = get_option('active_plugins');
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $info[] = $plugin_data['Name'] . ' v' . $plugin_data['Version'];
        }
        $info[] = "";
        
        return implode("\n", $info);
    }

    /**
     * AJAX: Run performance test
     */
    public function run_performance_test() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        $start_time = microtime(true);
        $start_memory = memory_get_usage();
        
        // Simulate some work
        for ($i = 0; $i < 10000; $i++) {
            $dummy = md5($i);
        }
        
        $end_time = microtime(true);
        $end_memory = memory_get_usage();
        
        $execution_time = round(($end_time - $start_time) * 1000, 2);
        $memory_used = $end_memory - $start_memory;
        
        $results = [
            'execution_time' => $execution_time . ' ms',
            'memory_used' => size_format($memory_used),
            'total_memory' => size_format(memory_get_peak_usage(true)),
            'queries' => get_num_queries(),
            'score' => $this->calculate_performance_score(),
        ];
        
        wp_send_json_success($results);
    }

    /**
     * AJAX: Check file permissions
     */
    public function check_file_permissions() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        $directories = [
            'WordPress Root' => ABSPATH,
            'wp-content' => WP_CONTENT_DIR,
            'Uploads' => wp_upload_dir()['basedir'],
            'Themes' => get_theme_root(),
            'Plugins' => WP_PLUGIN_DIR,
        ];
        
        $results = [];
        
        foreach ($directories as $name => $path) {
            $perms = substr(sprintf('%o', fileperms($path)), -4);
            $writable = is_writable($path);
            
            $results[] = [
                'name' => $name,
                'path' => $path,
                'permissions' => $perms,
                'writable' => $writable,
                'status' => $writable ? 'good' : 'warning',
            ];
        }
        
        wp_send_json_success($results);
    }
}