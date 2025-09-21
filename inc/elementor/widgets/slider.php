<?php
/**
 * Slider Elementor Widget
 *
 * @package PratikWp
 * @version 1.0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class PratikWp_Slider_Widget extends Widget_Base {

    public function get_name() {
        return 'pratikwp-slider';
    }

    public function get_title() {
        return __('PratikWp Slider', 'pratikwp');
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return ['pratikwp-theme'];
    }

    public function get_keywords() {
        return ['slider', 'carousel', 'slayt', 'pratikwp'];
    }

    protected function register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Slider İçeriği', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'slider_info',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    __('Bu widget, slayt içeriklerini merkezi Slider yönetim panelinden alır. Slaytları düzenlemek için lütfen <a href="%s" target="_blank">buraya tıklayın</a>.', 'pratikwp'),
                    admin_url('admin.php?page=pratikwp-slider')
                ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'settings_section',
            [
                'label' => __('Slider Ayarları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'override_settings',
            [
                'label' => __('Genel Ayarları Geçersiz Kıl', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Evet', 'pratikwp'),
                'label_off' => __('Hayır', 'pratikwp'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Tema ayarlarındaki varsayılan slider ayarları yerine bu widget\'a özel ayarları kullanmak için aktif edin.', 'pratikwp')
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label' => __('Otomatik Oynat', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'override_settings' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'delay',
            [
                'label' => __('Gecikme Süresi (ms)', 'pratikwp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'override_settings' => 'yes',
                    'autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'effect',
            [
                'label' => __('Geçiş Efekti', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => __('Kaydırma', 'pratikwp'),
                    'fade'  => __('Soldurma', 'pratikwp'),
                ],
                'condition' => [
                    'override_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows',
            [
                'label' => __('Yön Okları', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'override_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'dots',
            [
                'label' => __('Navigasyon Noktaları', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'override_settings' => 'yes',
                ],
            ]
        );
        
        // YENİ KONTROLLER
        $this->add_control(
            'height_desktop',
            [
                'label' => __('Masaüstü Yüksekliği (px)', 'pratikwp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 500,
                'condition' => [
                    'override_settings' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'height_mobile',
            [
                'label' => __('Mobil Yüksekliği (px)', 'pratikwp'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300,
                'condition' => [
                    'override_settings' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        global $slider_settings;
        
        if (!($slider_settings instanceof PratikWp_SliderSettings)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-danger">' . __('Slider ayarları yüklenemedi.', 'pratikwp') . '</div>';
            }
            return;
        }

        $slider_settings->enqueue_slider_assets();
        $slides = $slider_settings->get_active_slides();
        
        if (empty($slides)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . __('Görüntülenecek slayt bulunamadı. Lütfen yönetim panelinden slayt ekleyin.', 'pratikwp') . '</div>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $args = [];

        if (isset($settings['override_settings']) && $settings['override_settings'] === 'yes') {
            
            $args['autoplay']       = !empty($settings['autoplay']);
            $args['delay']          = (int) $settings['delay'];
            $args['effect']         = $settings['effect'];
            $args['arrows']         = !empty($settings['arrows']);
            $args['dots']           = !empty($settings['dots']);
            $args['height_desktop'] = (int) $settings['height_desktop']; // Eklendi
            $args['height_mobile']  = (int) $settings['height_mobile'];  // Eklendi
        }

        $slider_settings->render_slider($slides, $args);
    }
    
    protected function content_template() {}
}