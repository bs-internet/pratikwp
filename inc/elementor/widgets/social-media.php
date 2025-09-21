<?php
/**
 * Social Media Elementor Widget
 * 
 * @package PratikWp
 * @version 1.2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class PratikWp_Social_Media_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'pratikwp-social-media';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Sosyal Medya', 'pratikwp');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-social-icons';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['pratikwp-theme'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Ayarlar', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Hangi sosyal medya hesapları gösterilsin
        $this->add_control(
            'show_platforms',
            [
                'label' => __('Gösterilecek Platformlar', 'pratikwp'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['facebook', 'twitter', 'instagram'],
                'options' => [
                    'facebook'  => __('Facebook', 'pratikwp'),
                    'twitter'   => __('Twitter', 'pratikwp'),
                    'instagram' => __('Instagram', 'pratikwp'),
                    'linkedin'  => __('LinkedIn', 'pratikwp'),
                    'youtube'   => __('YouTube', 'pratikwp'),
                    'tiktok'    => __('TikTok', 'pratikwp'),
                ],
            ]
        );
        
        // Görünüm stili
        $this->add_control(
            'style',
            [
                'label' => __('Stil', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icons',
                'options' => [
                    'icons' => __('Sadece İkonlar', 'pratikwp'),
                    'text' => __('Sadece Metin', 'pratikwp'),
                    'both' => __('İkon + Metin', 'pratikwp'),
                ],
            ]
        );
        
        // Hedef pencere
        $this->add_control(
            'target',
            [
                'label' => __('Yeni Pencerede Aç', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
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
            'icon_size',
            [
                'label' => __('İkon Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .social-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'icon_color',
            [
                'label' => __('İkon/Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social-link' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    /**
     * Render widget output
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['show_platforms'])) {
            return;
        }

        // Merkezi shortcode'u çağırıyoruz. Tüm mantık orada.
        $platforms = implode(',', $settings['show_platforms']);
        $style = $settings['style'];
        $target = ($settings['target'] === 'yes') ? 'yes' : 'no';

        echo do_shortcode('[pratikwp_sosyal_medya goster="' . esc_attr($platforms) . '" stil="' . esc_attr($style) . '" yeni_pencere="' . esc_attr($target) . '"]');
    }
}