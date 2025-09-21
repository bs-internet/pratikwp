<?php
/**
 * Company Info Elementor Widget
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class PratikWp_Company_Info_Widget extends Widget_Base
{

    public function get_name() {
        return 'pratikwp-company-info';
    }

    public function get_title() {
        return __('Firma Bilgileri', 'pratikwp');
    }

    public function get_icon() {
        return 'eicon-info-box';
    }

    public function get_categories() {
        return ['pratikwp-theme'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Ayarlar', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Hangi bilgileri gösterilsin (Yönetim paneliyle uyumlu hale getirildi)
        $this->add_control(
            'show_fields',
            [
                'label' => __('Gösterilecek Bilgiler', 'pratikwp'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['name', 'full_address', 'phone1', 'email'],
                'options' => [
                    'name'         => __('Firma Adı', 'pratikwp'),
                    'full_address' => __('Tam Adres', 'pratikwp'),
                    'address'      => __('Sadece Açık Adres', 'pratikwp'),
                    'district'     => __('Sadece İlçe', 'pratikwp'),
                    'city'         => __('Sadece İl', 'pratikwp'),                    
                    'phone1'       => __('Sabit Telefon 1', 'pratikwp'),
                    'phone2'       => __('Sabit Telefon 2', 'pratikwp'),
                    'gsm1'         => __('GSM 1', 'pratikwp'),
                    'gsm2'         => __('GSM 2', 'pratikwp'),
                    'email'        => __('E-posta', 'pratikwp'),
                ],
            ]
        );

        $this->end_controls_section();

        // Stil ayarları
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Stil', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-company-info' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['show_fields'])) { return; }
        $shortcode_atts = implode(',', $settings['show_fields']);
        echo do_shortcode('[pratikwp_firma_bilgisi goster="' . esc_attr($shortcode_atts) . '"]');
    }
}