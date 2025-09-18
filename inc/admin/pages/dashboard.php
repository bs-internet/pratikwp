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
        // Dashboard temizlendi - AJAX handler'ları kaldırıldı
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
                    <p class="dashboard-subtitle"><?php esc_html_e('Tema yönetimi paneli', 'pratikwp'); ?></p>
                </div>
            </div>

            <div class="pratikwp-dashboard-grid">
                <!-- Theme Information -->
                <div class="dashboard-section theme-info">
                    <div class="section-header">
                        <h3><?php esc_html_e('Tema Bilgileri', 'pratikwp'); ?></h3>
                    </div>
                    <div class="theme-details">
                        <div class="theme-meta">
                            <h4>PratikWp</h4>
                            <p class="version"><?php esc_html_e('Sürüm:', 'pratikwp'); ?> <?php echo PRATIKWP_VERSION; ?></p>
                            <p class="description"><?php esc_html_e('Modern WordPress teması', 'pratikwp'); ?></p>
                            <div class="theme-links">
                                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary">
                                    <?php esc_html_e('Özelleştir', 'pratikwp'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}