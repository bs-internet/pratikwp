<?php
/**
 * Admin Demo Import Page
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Admin Demo Import handler
 */
class PratikWp_Demo_Import {

    /**
     * Demo configurations
     */
    private $demos = [
        'default' => [
            'title' => 'Varsayılan Demo',
            'description' => 'İş ve kurumsal siteler için hazırlanmış profesyonel demo içerik.',
            'preview' => 'default-preview.jpg',
            'files' => [
                'content' => 'default/content.xml',
                'customizer' => 'default/customizer.dat',
                'widgets' => 'default/widgets.wie',
                'elementor' => 'default/elementor-templates.json',
                'sliders' => 'default/sliders.json',
            ],
            'plugins' => [
                'elementor/elementor.php' => 'Elementor',
                'contact-form-7/wp-contact-form-7.php' => 'Contact Form 7',
            ],
            'menus' => [
                'primary' => 'Ana Menü',
                'footer' => 'Alt Menü',
            ],
        ],
        'blog' => [
            'title' => 'Blog Teması',
            'description' => 'Kişisel blog ve içerik odaklı siteler için modern tasarım.',
            'preview' => 'blog-preview.jpg',
            'files' => [
                'content' => 'blog/content.xml',
                'customizer' => 'blog/customizer.dat',
                'widgets' => 'blog/widgets.wie',
                'elementor' => 'blog/elementor-templates.json',
                'sliders' => 'blog/sliders.json',
            ],
            'plugins' => [
                'elementor/elementor.php' => 'Elementor',
            ],
            'menus' => [
                'primary' => 'Ana Menü',
            ],
        ],
        'portfolio' => [
            'title' => 'Portfolyo Teması',
            'description' => 'Yaratıcı profesyoneller ve ajanslar için portfolyo odaklı tasarım.',
            'preview' => 'portfolio-preview.jpg',
            'files' => [
                'content' => 'portfolio/content.xml',
                'customizer' => 'portfolio/customizer.dat',
                'widgets' => 'portfolio/widgets.wie',
                'elementor' => 'portfolio/elementor-templates.json',
                'sliders' => 'portfolio/sliders.json',
            ],
            'plugins' => [
                'elementor/elementor.php' => 'Elementor',
                'elementor-pro/elementor-pro.php' => 'Elementor Pro',
            ],
            'menus' => [
                'primary' => 'Ana Menü',
                'footer' => 'Alt Menü',
            ],
        ],
    ];

    /**
     * Constructor
     */
    public function __construct() {
        add_action('wp_ajax_pratikwp_import_demo', [$this, 'import_demo']);
        add_action('wp_ajax_pratikwp_check_plugins', [$this, 'check_required_plugins']);
        add_action('wp_ajax_pratikwp_install_plugin', [$this, 'install_plugin']);
        add_action('wp_ajax_pratikwp_reset_site', [$this, 'reset_site']);
    }

    /**
     * Render demo import page
     */
    public function render_page() {
        ?>
        <div class="wrap pratikwp-admin-page pratikwp-demo-import">
            <div class="pratikwp-demo-import-header">
                <h1><?php esc_html_e('Demo İçerik İçe Aktarma', 'pratikwp'); ?></h1>
                <p class="demo-description"><?php esc_html_e('Hızlı başlamak için hazır demo içeriklerinden birini seçin. Tüm içerikler, ayarlar ve görseller otomatik olarak yüklenecektir.', 'pratikwp'); ?></p>
            </div>

            <!-- Demo Selection -->
            <div class="pratikwp-demo-grid">
                <?php foreach ($this->demos as $demo_id => $demo): ?>
                    <div class="demo-item" data-demo="<?php echo esc_attr($demo_id); ?>">
                        <div class="demo-preview">
                            <img src="<?php echo get_template_directory_uri(); ?>/demo-content/previews/<?php echo esc_attr($demo['preview']); ?>" 
                                 alt="<?php echo esc_attr($demo['title']); ?>" />
                            <div class="demo-overlay">
                                <button type="button" class="button button-primary demo-import-btn">
                                    <?php esc_html_e('İçe Aktar', 'pratikwp'); ?>
                                </button>
                                <a href="#" class="demo-preview-btn" target="_blank">
                                    <?php esc_html_e('Önizleme', 'pratikwp'); ?>
                                </a>
                            </div>
                        </div>
                        <div class="demo-info">
                            <h3 class="demo-title"><?php echo esc_html($demo['title']); ?></h3>
                            <p class="demo-description"><?php echo esc_html($demo['description']); ?></p>
                            
                            <!-- Demo Features -->
                            <div class="demo-features">
                                <div class="feature-item">
                                    <i class="dashicons dashicons-admin-appearance"></i>
                                    <span><?php esc_html_e('Özel Tasarım', 'pratikwp'); ?></span>
                                </div>
                                <div class="feature-item">
                                    <i class="dashicons dashicons-admin-post"></i>
                                    <span><?php esc_html_e('Örnek İçerikler', 'pratikwp'); ?></span>
                                </div>
                                <div class="feature-item">
                                    <i class="dashicons dashicons-admin-customizer"></i>
                                    <span><?php esc_html_e('Tema Ayarları', 'pratikwp'); ?></span>
                                </div>
                                <div class="feature-item">
                                    <i class="dashicons dashicons-menu"></i>
                                    <span><?php esc_html_e('Menü Yapısı', 'pratikwp'); ?></span>
                                </div>
                            </div>

                            <!-- Required Plugins -->
                            <?php if (!empty($demo['plugins'])): ?>
                                <div class="demo-plugins">
                                    <h4><?php esc_html_e('Gerekli Eklentiler', 'pratikwp'); ?></h4>
                                    <div class="plugins-list">
                                        <?php foreach ($demo['plugins'] as $plugin_file => $plugin_name): ?>
                                            <span class="plugin-badge <?php echo is_plugin_active($plugin_file) ? 'active' : 'inactive'; ?>">
                                                <?php echo esc_html($plugin_name); ?>
                                                <?php if (is_plugin_active($plugin_file)): ?>
                                                    <i class="dashicons dashicons-yes-alt"></i>
                                                <?php else: ?>
                                                    <i class="dashicons dashicons-dismiss"></i>
                                                <?php endif; ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Import Options -->
            <div class="pratikwp-import-options" id="import-options" style="display: none;">
                <div class="options-header">
                    <h3><?php esc_html_e('İçe Aktarma Seçenekleri', 'pratikwp'); ?></h3>
                    <p><?php esc_html_e('Hangi içeriklerin içe aktarılacağını seçin', 'pratikwp'); ?></p>
                </div>

                <div class="import-checkboxes">
                    <label class="import-option">
                        <input type="checkbox" name="import_content" value="1" checked />
                        <span class="checkmark"></span>
                        <div class="option-info">
                            <strong><?php esc_html_e('İçerikler', 'pratikwp'); ?></strong>
                            <p><?php esc_html_e('Sayfalar, yazılar ve görseller', 'pratikwp'); ?></p>
                        </div>
                    </label>

                    <label class="import-option">
                        <input type="checkbox" name="import_customizer" value="1" checked />
                        <span class="checkmark"></span>
                        <div class="option-info">
                            <strong><?php esc_html_e('Tema Ayarları', 'pratikwp'); ?></strong>
                            <p><?php esc_html_e('Renkler, yazı tipleri ve düzen ayarları', 'pratikwp'); ?></p>
                        </div>
                    </label>

                    <label class="import-option">
                        <input type="checkbox" name="import_widgets" value="1" checked />
                        <span class="checkmark"></span>
                        <div class="option-info">
                            <strong><?php esc_html_e('Widget\'lar', 'pratikwp'); ?></strong>
                            <p><?php esc_html_e('Sidebar ve footer widget\'ları', 'pratikwp'); ?></p>
                        </div>
                    </label>

                    <label class="import-option">
                        <input type="checkbox" name="import_elementor" value="1" checked />
                        <span class="checkmark"></span>
                        <div class="option-info">
                            <strong><?php esc_html_e('Elementor Şablonları', 'pratikwp'); ?></strong>
                            <p><?php esc_html_e('Header, footer ve sayfa şablonları', 'pratikwp'); ?></p>
                        </div>
                    </label>

                    <label class="import-option">
                        <input type="checkbox" name="import_sliders" value="1" checked />
                        <span class="checkmark"></span>
                        <div class="option-info">
                            <strong><?php esc_html_e('Slider\'lar', 'pratikwp'); ?></strong>
                            <p><?php esc_html_e('Ana sayfa slider içerikleri', 'pratikwp'); ?></p>
                        </div>
                    </label>
                </div>

                <div class="import-warnings">
                    <div class="warning-box">
                        <i class="dashicons dashicons-warning"></i>
                        <div class="warning-content">
                            <strong><?php esc_html_e('Önemli Uyarı', 'pratikwp'); ?></strong>
                            <p><?php esc_html_e('Demo içerik içe aktarma işlemi mevcut içeriklerinizi etkileyebilir. İşlemden önce site yedeği almanızı öneririz.', 'pratikwp'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="import-actions">
                    <button type="button" class="button button-large button-primary" id="start-import">
                        <?php esc_html_e('İçe Aktarmaya Başla', 'pratikwp'); ?>
                    </button>
                    <button type="button" class="button button-secondary" id="cancel-import">
                        <?php esc_html_e('İptal', 'pratikwp'); ?>
                    </button>
                </div>
            </div>

            <!-- Import Progress -->
            <div class="pratikwp-import-progress" id="import-progress" style="display: none;">
                <div class="progress-header">
                    <h3><?php esc_html_e('İçe Aktarma İşlemi Devam Ediyor...', 'pratikwp'); ?></h3>
                    <p><?php esc_html_e('Lütfen sayfayı kapatmayın, işlem birkaç dakika sürebilir.', 'pratikwp'); ?></p>
                </div>

                <div class="progress-steps">
                    <div class="progress-step" id="step-preparation">
                        <div class="step-icon"><i class="dashicons dashicons-admin-tools"></i></div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Hazırlık', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Gerekli kontroller yapılıyor', 'pratikwp'); ?></p>
                        </div>
                        <div class="step-status"></div>
                    </div>

                    <div class="progress-step" id="step-plugins">
                        <div class="step-icon"><i class="dashicons dashicons-admin-plugins"></i></div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Eklenti Kontrolü', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Gerekli eklentiler kontrol ediliyor', 'pratikwp'); ?></p>
                        </div>
                        <div class="step-status"></div>
                    </div>

                    <div class="progress-step" id="step-content">
                        <div class="step-icon"><i class="dashicons dashicons-admin-post"></i></div>
                        <div class="step-content">
                            <h4><?php esc_html_e('İçerik İçe Aktarma', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Sayfalar ve yazılar içe aktarılıyor', 'pratikwp'); ?></p>
                        </div>
                        <div class="step-status"></div>
                    </div>

                    <div class="progress-step" id="step-customizer">
                        <div class="step-icon"><i class="dashicons dashicons-admin-customizer"></i></div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Tema Ayarları', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Özelleştirici ayarları uygulanıyor', 'pratikwp'); ?></p>
                        </div>
                        <div class="step-status"></div>
                    </div>

                    <div class="progress-step" id="step-widgets">
                        <div class="step-icon"><i class="dashicons dashicons-admin-generic"></i></div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Widget\'lar', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Widget ayarları içe aktarılıyor', 'pratikwp'); ?></p>
                        </div>
                        <div class="step-status"></div>
                    </div>

                    <div class="progress-step" id="step-finalization">
                        <div class="step-icon"><i class="dashicons dashicons-yes-alt"></i></div>
                        <div class="step-content">
                            <h4><?php esc_html_e('Tamamlanıyor', 'pratikwp'); ?></h4>
                            <p><?php esc_html_e('Son ayarlar yapılıyor', 'pratikwp'); ?></p>
                        </div>
                        <div class="step-status"></div>
                    </div>
                </div>

                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                    <div class="progress-text" id="progress-text">0%</div>
                </div>
            </div>

            <!-- Import Complete -->
            <div class="pratikwp-import-complete" id="import-complete" style="display: none;">
                <div class="complete-icon">
                    <i class="dashicons dashicons-yes-alt"></i>
                </div>
                <h3><?php esc_html_e('İçe Aktarma Tamamlandı!', 'pratikwp'); ?></h3>
                <p><?php esc_html_e('Demo içerik başarıyla sitenize yüklendi. Artık içerikleri düzenleyebilir ve kendi sitenizi oluşturabilirsiniz.', 'pratikwp'); ?></p>
                
                <div class="complete-actions">
                    <a href="<?php echo home_url(); ?>" class="button button-primary" target="_blank">
                        <?php esc_html_e('Siteyi Görüntüle', 'pratikwp'); ?>
                    </a>
                    <a href="<?php echo admin_url('customize.php'); ?>" class="button button-secondary">
                        <?php esc_html_e('Özelleştirmeye Başla', 'pratikwp'); ?>
                    </a>
                </div>

                <div class="next-steps">
                    <h4><?php esc_html_e('Sonraki Adımlar', 'pratikwp'); ?></h4>
                    <ul>
                        <li><?php esc_html_e('Logo ve site başlığını özelleştirin', 'pratikwp'); ?></li>
                        <li><?php esc_html_e('İletişim bilgilerini güncelleyin', 'pratikwp'); ?></li>
                        <li><?php esc_html_e('Renk şemasını ve yazı tiplerini ayarlayın', 'pratikwp'); ?></li>
                        <li><?php esc_html_e('Örnek içerikleri kendi içeriklerinizle değiştirin', 'pratikwp'); ?></li>
                    </ul>
                </div>
            </div>

            <!-- Reset Site Option -->
            <div class="pratikwp-reset-section">
                <div class="reset-header">
                    <h3><?php esc_html_e('Site Sıfırlama', 'pratikwp'); ?></h3>
                    <p><?php esc_html_e('Sitenizi varsayılan haline döndürmek için bu seçeneği kullanabilirsiniz.', 'pratikwp'); ?></p>
                </div>
                <button type="button" class="button button-link-delete" id="reset-site">
                    <?php esc_html_e('Siteyi Sıfırla', 'pratikwp'); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX: Import demo content
     */
    public function import_demo() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        $demo_id = sanitize_text_field($_POST['demo_id']);
        $import_options = isset($_POST['import_options']) ? $_POST['import_options'] : [];

        if (!isset($this->demos[$demo_id])) {
            wp_send_json_error(['message' => __('Geçersiz demo ID', 'pratikwp')]);
        }

        $demo = $this->demos[$demo_id];
        $demo_path = get_template_directory() . '/demo-content/';

        try {
            // Step 1: Preparation
            $this->send_progress_update('preparation', 10, __('Hazırlık yapılıyor...', 'pratikwp'));

            // Step 2: Check and install plugins
            $this->send_progress_update('plugins', 20, __('Eklentiler kontrol ediliyor...', 'pratikwp'));
            $this->check_and_install_plugins($demo['plugins']);

            // Step 3: Import content
            if (in_array('import_content', $import_options) && isset($demo['files']['content'])) {
                $this->send_progress_update('content', 40, __('İçerikler içe aktarılıyor...', 'pratikwp'));
                $this->import_content($demo_path . $demo['files']['content']);
            }

            // Step 4: Import customizer
            if (in_array('import_customizer', $import_options) && isset($demo['files']['customizer'])) {
                $this->send_progress_update('customizer', 60, __('Tema ayarları uygulanıyor...', 'pratikwp'));
                $this->import_customizer($demo_path . $demo['files']['customizer']);
            }

            // Step 5: Import widgets
            if (in_array('import_widgets', $import_options) && isset($demo['files']['widgets'])) {
                $this->send_progress_update('widgets', 75, __('Widget\'lar içe aktarılıyor...', 'pratikwp'));
                $this->import_widgets($demo_path . $demo['files']['widgets']);
            }

            // Step 6: Import Elementor templates
            if (in_array('import_elementor', $import_options) && isset($demo['files']['elementor'])) {
                $this->send_progress_update('elementor', 85, __('Elementor şablonları içe aktarılıyor...', 'pratikwp'));
                $this->import_elementor_templates($demo_path . $demo['files']['elementor']);
            }

            // Step 7: Import sliders
            if (in_array('import_sliders', $import_options) && isset($demo['files']['sliders'])) {
                $this->send_progress_update('sliders', 90, __('Slider\'lar içe aktarılıyor...', 'pratikwp'));
                $this->import_sliders($demo_path . $demo['files']['sliders']);
            }

            // Step 8: Setup menus
            $this->send_progress_update('menus', 95, __('Menüler ayarlanıyor...', 'pratikwp'));
            $this->setup_menus($demo['menus']);

            // Step 9: Finalization
            $this->send_progress_update('finalization', 100, __('Tamamlanıyor...', 'pratikwp'));
            $this->finalize_import();

            wp_send_json_success([
                'message' => __('Demo içerik başarıyla içe aktarıldı!', 'pratikwp')
            ]);

        } catch (Exception $e) {
            wp_send_json_error([
                'message' => sprintf(__('İçe aktarma hatası: %s', 'pratikwp'), $e->getMessage())
            ]);
        }
    }

    /**
     * Import WordPress content from XML
     */
    private function import_content($file_path) {
        if (!file_exists($file_path)) {
            throw new Exception(__('İçerik dosyası bulunamadı', 'pratikwp'));
        }

        if (!class_exists('WP_Import')) {
            $importer = get_template_directory() . '/inc/admin/wordpress-importer.php';
            if (file_exists($importer)) {
                require_once $importer;
            } else {
                throw new Exception(__('WordPress importer bulunamadı', 'pratikwp'));
            }
        }

        $wp_import = new WP_Import();
        $wp_import->fetch_attachments = true;
        
        ob_start();
        $wp_import->import($file_path);
        ob_end_clean();
    }

    /**
     * Import customizer settings
     */
    private function import_customizer($file_path) {
        if (!file_exists($file_path)) {
            return;
        }

        $data = file_get_contents($file_path);
        $customizer_data = unserialize($data);

        if (is_array($customizer_data)) {
            foreach ($customizer_data as $key => $value) {
                set_theme_mod($key, $value);
            }
        }
    }

    /**
     * Import widgets
     */
    private function import_widgets($file_path) {
        if (!file_exists($file_path)) {
            return;
        }

        $data = file_get_contents($file_path);
        $widget_data = json_decode($data, true);

        if (is_array($widget_data)) {
            foreach ($widget_data as $sidebar_id => $widgets) {
                update_option('sidebars_widgets', $widget_data);
            }
        }
    }

    /**
     * Import Elementor templates
     */
    private function import_elementor_templates($file_path) {
        if (!file_exists($file_path) || !class_exists('\Elementor\Plugin')) {
            return;
        }

        $templates_data = file_get_contents($file_path);
        $templates = json_decode($templates_data, true);

        if (is_array($templates)) {
            foreach ($templates as $template) {
                $this->create_elementor_template($template);
            }
        }
    }

    /**
     * Import slider data
     */
    private function import_sliders($file_path) {
        if (!file_exists($file_path)) {
            return;
        }

        $sliders_data = file_get_contents($file_path);
        $sliders = json_decode($sliders_data, true);

        if (is_array($sliders)) {
            foreach ($sliders as $slider_id => $slider_data) {
                update_option('pratikwp_slider_' . $slider_id, $slider_data);
            }
        }
    }

    /**
     * Setup navigation menus
     */
    private function setup_menus($menus) {
        $locations = get_theme_mod('nav_menu_locations');
        
        foreach ($menus as $location => $menu_name) {
            $menu = wp_get_nav_menu_object($menu_name);
            if ($menu) {
                $locations[$location] = $menu->term_id;
            }
        }
        
        set_theme_mod('nav_menu_locations', $locations);
    }

    /**
     * Finalize import process
     */
    private function finalize_import() {
        // Set front page
        $front_page = get_page_by_title('Ana Sayfa');
        if ($front_page) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_page->ID);
        }

        // Set blog page
        $blog_page = get_page_by_title('Blog');
        if ($blog_page) {
            update_option('page_for_posts', $blog_page->ID);
        }

        // Flush rewrite rules
        flush_rewrite_rules();

        // Clear caches
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }
    }

    /**
     * Create Elementor template
     */
    private function create_elementor_template($template_data) {
        $post_id = wp_insert_post([
            'post_title' => $template_data['title'],
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'elementor_library',
        ]);

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_elementor_data', $template_data['content']);
            update_post_meta($post_id, '_elementor_template_type', $template_data['type']);
            update_post_meta($post_id, '_elementor_edit_mode', 'builder');
        }
    }

    /**
     * Check and install required plugins
     */
    private function check_and_install_plugins($plugins) {
        foreach ($plugins as $plugin_file => $plugin_name) {
            if (!is_plugin_active($plugin_file)) {
                // Plugin installation logic would go here
                // This is simplified for the example
            }
        }
    }

    /**
     * Send progress update
     */
    private function send_progress_update($step, $percentage, $message) {
        // In a real implementation, this would send AJAX updates
        // For now, it's a placeholder
    }

    /**
     * AJAX: Reset site
     */
    public function reset_site() {
        check_ajax_referer('pratikwp_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Yetkisiz işlem', 'pratikwp')]);
        }

        try {
            // Reset theme mods
            remove_theme_mods();

            // Reset widgets
            update_option('sidebars_widgets', []);

            // Reset menus
            $menus = wp_get_nav_menus();
            foreach ($menus as $menu) {
                wp_delete_nav_menu($menu->slug);
            }

            // Delete demo posts and pages
            $demo_posts = get_posts([
                'post_type' => ['post', 'page'],
                'posts_per_page' => -1,
                'meta_query' => [
                    [
                        'key' => '_pratikwp_demo_content',
                        'value' => '1',
                        'compare' => '='
                    ]
                ]
            ]);

            foreach ($demo_posts as $post) {
                wp_delete_post($post->ID, true);
            }

            wp_send_json_success(['message' => __('Site başarıyla sıfırlandı', 'pratikwp')]);

        } catch (Exception $e) {
            wp_send_json_error(['message' => $e->getMessage()]);
        }
    }
}