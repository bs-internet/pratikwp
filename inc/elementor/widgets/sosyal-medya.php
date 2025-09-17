<?php
/**
 * Social Media Elementor Widget
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;

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
        return ['pratikwp-site'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['social', 'media', 'facebook', 'instagram', 'twitter', 'youtube', 'linkedin'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'social_content_section',
            [
                'label' => __('Sosyal Medya Hesapları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Social Media Platforms to Show
        $this->add_control(
            'show_platforms',
            [
                'label' => __('Gösterilecek Platformlar', 'pratikwp'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'facebook' => __('Facebook', 'pratikwp'),
                    'x' => __('X (Twitter)', 'pratikwp'),
                    'instagram' => __('Instagram', 'pratikwp'),
                    'youtube' => __('YouTube', 'pratikwp'),
                    'linkedin' => __('LinkedIn', 'pratikwp'),
                    'tiktok' => __('TikTok', 'pratikwp'),
                    'pinterest' => __('Pinterest', 'pratikwp'),
                    'whatsapp' => __('WhatsApp', 'pratikwp'),
                ],
                'default' => ['facebook', 'instagram', 'x', 'youtube'],
            ]
        );

        // Display Style
        $this->add_control(
            'display_style',
            [
                'label' => __('Görünüm Stili', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'icon',
                'options' => [
                    'icon' => __('Sadece İkon', 'pratikwp'),
                    'text' => __('Sadece Metin', 'pratikwp'),
                    'icon_text' => __('İkon + Metin', 'pratikwp'),
                    'button' => __('Buton Stili', 'pratikwp'),
                ],
            ]
        );

        // Layout
        $this->add_control(
            'layout',
            [
                'label' => __('Düzen', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Yatay', 'pratikwp'),
                    'vertical' => __('Dikey', 'pratikwp'),
                    'grid' => __('Grid', 'pratikwp'),
                ],
            ]
        );

        // Grid Columns
        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => __('Grid Sütunları', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => '4',
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '8' => '8',
                ],
                'condition' => [
                    'layout' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-social-media.grid-layout' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'social_alignment',
            [
                'label' => __('Hizalama', 'pratikwp'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Sol', 'pratikwp'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Orta', 'pratikwp'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Sağ', 'pratikwp'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-social-media' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Custom Links Section
        $this->start_controls_section(
            'custom_links_section',
            [
                'label' => __('Özel Linkler', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'use_custom_links',
            [
                'label' => __('Özel Linkler Kullan', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => __('Tema ayarlarındaki linkleri değil, aşağıdaki özel linkleri kullan', 'pratikwp'),
            ]
        );

        // Custom links for each platform
        $platforms = [
            'facebook' => __('Facebook', 'pratikwp'),
            'x' => __('X (Twitter)', 'pratikwp'),
            'instagram' => __('Instagram', 'pratikwp'),
            'youtube' => __('YouTube', 'pratikwp'),
            'linkedin' => __('LinkedIn', 'pratikwp'),
            'tiktok' => __('TikTok', 'pratikwp'),
            'pinterest' => __('Pinterest', 'pratikwp'),
            'whatsapp' => __('WhatsApp', 'pratikwp'),
        ];

        foreach ($platforms as $platform => $label) {
            $this->add_control(
                'custom_' . $platform,
                [
                    'label' => $label . __(' Linki', 'pratikwp'),
                    'type' => Controls_Manager::URL,
                    'placeholder' => 'https://',
                    'condition' => [
                        'use_custom_links' => 'yes',
                    ],
                ]
            );
        }

        $this->end_controls_section();

        // Link Behavior Section
        $this->start_controls_section(
            'link_behavior_section',
            [
                'label' => __('Link Davranışı', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Target
        $this->add_control(
            'link_target',
            [
                'label' => __('Link Hedefi', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => '_blank',
                'options' => [
                    '_self' => __('Aynı Pencere', 'pratikwp'),
                    '_blank' => __('Yeni Pencere', 'pratikwp'),
                ],
            ]
        );

        // NoFollow
        $this->add_control(
            'link_nofollow',
            [
                'label' => __('NoFollow Ekle', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'social_style_section',
            [
                'label' => __('Genel Stil', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Item Size
        $this->add_responsive_control(
            'item_size',
            [
                'label' => __('Öğe Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40,
                ],
                'selectors' => [
                    '{{WRAPPER}} .social-link' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'display_style!' => 'text',
                ],
            ]
        );

        // Item Spacing
        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __('Öğeler Arası Boşluk', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-social-media.horizontal-layout' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pratikwp-social-media.vertical-layout' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pratikwp-social-media.grid-layout' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Icon Style Section
        $this->start_controls_section(
            'icon_style_section',
            [
                'label' => __('İkon Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style!' => 'text',
                ],
            ]
        );

        // Icon Size
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('İkon Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 18,
                ],
                'selectors' => [
                    '{{WRAPPER}} .social-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .social-link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon Color Type
        $this->add_control(
            'icon_color_type',
            [
                'label' => __('İkon Renk Tipi', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'brand',
                'options' => [
                    'brand' => __('Marka Renkleri', 'pratikwp'),
                    'custom' => __('Özel Renk', 'pratikwp'),
                ],
            ]
        );

        // Icon Color
        $this->add_control(
            'icon_color',
            [
                'label' => __('İkon Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .social-link' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_color_type' => 'custom',
                ],
            ]
        );

        // Icon Hover Color
        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('İkon Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social-link:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'icon_color_type' => 'custom',
                ],
            ]
        );

        $this->end_controls_section();

        // Background Style Section
        $this->start_controls_section(
            'background_style_section',
            [
                'label' => __('Arka Plan Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Background Type
        $this->add_control(
            'background_type',
            [
                'label' => __('Arka Plan Tipi', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => __('Yok', 'pratikwp'),
                    'color' => __('Renk', 'pratikwp'),
                    'brand' => __('Marka Renkleri', 'pratikwp'),
                ],
            ]
        );

        // Background Color
        $this->add_control(
            'background_color',
            [
                'label' => __('Arka Plan Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#f8f9fa',
                'selectors' => [
                    '{{WRAPPER}} .social-link' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'background_type' => 'color',
                ],
            ]
        );

        // Background Hover Color
        $this->add_control(
            'background_hover_color',
            [
                'label' => __('Arka Plan Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social-link:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'background_type' => 'color',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'item_border',
                'selector' => '{{WRAPPER}} .social-link',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'item_border_radius',
            [
                'label' => __('Border Radius', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default' => [
                    'top' => 50,
                    'right' => 50,
                    'bottom' => 50,
                    'left' => 50,
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .social-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}} .social-link',
            ]
        );

        $this->end_controls_section();

        // Text Style Section
        $this->start_controls_section(
            'text_style_section',
            [
                'label' => __('Metin Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'display_style!' => 'icon',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .social-text',
            ]
        );

        // Text Color
        $this->add_control(
            'text_color',
            [
                'label' => __('Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .social-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Hover Color
        $this->add_control(
            'text_hover_color',
            [
                'label' => __('Metin Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .social-link:hover .social-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Spacing
        $this->add_responsive_control(
            'text_spacing',
            [
                'label' => __('İkon ve Metin Arası Boşluk', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .social-link i + .social-text' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .social-link svg + .social-text' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'display_style' => 'icon_text',
                ],
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'social_advanced_section',
            [
                'label' => __('Gelişmiş', 'pratikwp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS Classes
        $this->add_control(
            'social_css_classes',
            [
                'label' => __('CSS Class\'ları', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'title' => __('Özel CSS class\'larını boşlukla ayırarak girin', 'pratikwp'),
            ]
        );

        // Hover Animation
        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animasyonu', 'pratikwp'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (empty($settings['show_platforms'])) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . __('Lütfen gösterilecek sosyal medya platformlarını seçin.', 'pratikwp') . '</div>';
            }
            return;
        }

        $css_classes = ['pratikwp-social-media'];
        $css_classes[] = $settings['layout'] . '-layout';
        $css_classes[] = 'style-' . $settings['display_style'];
        
        if (!empty($settings['social_css_classes'])) {
            $css_classes[] = $settings['social_css_classes'];
        }
        
        if (!empty($settings['hover_animation'])) {
            $css_classes[] = 'elementor-animation-' . $settings['hover_animation'];
        }

        $this->add_render_attribute('wrapper', 'class', $css_classes);

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <?php foreach ($settings['show_platforms'] as $platform) : ?>
                <?php
                $url = $this->get_platform_url($platform, $settings);
                if (empty($url)) continue;
                
                $platform_data = $this->get_platform_data($platform);
                $link_attrs = $this->get_link_attributes($url, $platform_data['name'], $settings);
                $item_classes = ['social-link', 'social-' . $platform];
                
                if ($settings['background_type'] === 'brand' || $settings['icon_color_type'] === 'brand') {
                    $item_classes[] = 'brand-colors';
                }
                ?>
                <a <?php echo $link_attrs; ?> class="<?php echo esc_attr(implode(' ', $item_classes)); ?>">
                    <?php if ($settings['display_style'] !== 'text') : ?>
                        <i class="<?php echo esc_attr($platform_data['icon']); ?>" aria-hidden="true"></i>
                    <?php endif; ?>
                    
                    <?php if ($settings['display_style'] !== 'icon') : ?>
                        <span class="social-text"><?php echo esc_html($platform_data['name']); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <?php if ($settings['background_type'] === 'brand' || $settings['icon_color_type'] === 'brand') : ?>
        <style>
        <?php echo $this->get_brand_colors_css(); ?>
        </style>
        <?php endif; ?>
        <?php
    }

    /**
     * Get platform URL
     */
    protected function get_platform_url($platform, $settings) {
        if ($settings['use_custom_links'] === 'yes' && !empty($settings['custom_' . $platform]['url'])) {
            return $settings['custom_' . $platform]['url'];
        }
        
        return get_option('sosyal_' . $platform, '');
    }

    /**
     * Get platform data
     */
    protected function get_platform_data($platform) {
        $platforms = [
            'facebook' => ['name' => 'Facebook', 'icon' => 'fab fa-facebook-f'],
            'x' => ['name' => 'X', 'icon' => 'fab fa-x-twitter'],
            'instagram' => ['name' => 'Instagram', 'icon' => 'fab fa-instagram'],
            'youtube' => ['name' => 'YouTube', 'icon' => 'fab fa-youtube'],
            'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in'],
            'tiktok' => ['name' => 'TikTok', 'icon' => 'fab fa-tiktok'],
            'pinterest' => ['name' => 'Pinterest', 'icon' => 'fab fa-pinterest-p'],
            'whatsapp' => ['name' => 'WhatsApp', 'icon' => 'fab fa-whatsapp'],
        ];
        
        return $platforms[$platform] ?? ['name' => ucfirst($platform), 'icon' => 'fas fa-link'];
    }

    /**
     * Get link attributes
     */
    protected function get_link_attributes($url, $title, $settings) {
        $attrs = [
            'href="' . esc_url($url) . '"',
            'title="' . esc_attr($title) . '"',
            'target="' . esc_attr($settings['link_target']) . '"',
        ];
        
        $rel_attrs = [];
        if ($settings['link_target'] === '_blank') {
            $rel_attrs[] = 'noopener';
        }
        if ($settings['link_nofollow'] === 'yes') {
            $rel_attrs[] = 'nofollow';
        }
        
        if (!empty($rel_attrs)) {
            $attrs[] = 'rel="' . implode(' ', $rel_attrs) . '"';
        }
        
        return implode(' ', $attrs);
    }

    /**
     * Get brand colors CSS
     */
    protected function get_brand_colors_css() {
        $css = '
        {{WRAPPER}} .social-facebook.brand-colors { color: #1877F2; }
        {{WRAPPER}} .social-facebook.brand-colors:hover { background-color: #1877F2; color: white; }
        
        {{WRAPPER}} .social-x.brand-colors { color: #000000; }
        {{WRAPPER}} .social-x.brand-colors:hover { background-color: #000000; color: white; }
        
        {{WRAPPER}} .social-instagram.brand-colors { color: #E4405F; }
        {{WRAPPER}} .social-instagram.brand-colors:hover { background: linear-gradient(45deg, #E4405F, #FFDC80); color: white; }
        
        {{WRAPPER}} .social-youtube.brand-colors { color: #FF0000; }
        {{WRAPPER}} .social-youtube.brand-colors:hover { background-color: #FF0000; color: white; }
        
        {{WRAPPER}} .social-linkedin.brand-colors { color: #0A66C2; }
        {{WRAPPER}} .social-linkedin.brand-colors:hover { background-color: #0A66C2; color: white; }
        
        {{WRAPPER}} .social-tiktok.brand-colors { color: #000000; }
        {{WRAPPER}} .social-tiktok.brand-colors:hover { background-color: #000000; color: white; }
        
        {{WRAPPER}} .social-pinterest.brand-colors { color: #BD081C; }
        {{WRAPPER}} .social-pinterest.brand-colors:hover { background-color: #BD081C; color: white; }
        
        {{WRAPPER}} .social-whatsapp.brand-colors { color: #25D366; }
        {{WRAPPER}} .social-whatsapp.brand-colors:hover { background-color: #25D366; color: white; }
        ';
        
        return $css;
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-social-media'];
        css_classes.push(settings.layout + '-layout');
        css_classes.push('style-' + settings.display_style);
        
        if (settings.social_css_classes) {
            css_classes.push(settings.social_css_classes);
        }
        
        if (settings.hover_animation) {
            css_classes.push('elementor-animation-' + settings.hover_animation);
        }

        var platforms = {
            facebook: { name: 'Facebook', icon: 'fab fa-facebook-f' },
            x: { name: 'X', icon: 'fab fa-x-twitter' },
            instagram: { name: 'Instagram', icon: 'fab fa-instagram' },
            youtube: { name: 'YouTube', icon: 'fab fa-youtube' },
            linkedin: { name: 'LinkedIn', icon: 'fab fa-linkedin-in' },
            tiktok: { name: 'TikTok', icon: 'fab fa-tiktok' },
            pinterest: { name: 'Pinterest', icon: 'fab fa-pinterest-p' },
            whatsapp: { name: 'WhatsApp', icon: 'fab fa-whatsapp' }
        };

        var target = settings.link_target;
        var rel_attrs = [];
        if (target === '_blank') {
            rel_attrs.push('noopener');
        }
        if (settings.link_nofollow === 'yes') {
            rel_attrs.push('nofollow');
        }
        var rel = rel_attrs.length > 0 ? 'rel="' + rel_attrs.join(' ') + '"' : '';
        #>
        <div class="{{ css_classes.join(' ') }}">
            <# if (settings.show_platforms && settings.show_platforms.length > 0) { #>
                <# _.each(settings.show_platforms, function(platform) { #>
                    <# if (platforms[platform]) { #>
                        <# 
                        var item_classes = ['social-link', 'social-' + platform];
                        if (settings.background_type === 'brand' || settings.icon_color_type === 'brand') {
                            item_classes.push('brand-colors');
                        }
                        #>
                        <a href="#" title="{{ platforms[platform].name }}" target="{{ target }}" {{{ rel }}} class="{{ item_classes.join(' ') }}">
                            <# if (settings.display_style !== 'text') { #>
                                <i class="{{ platforms[platform].icon }}" aria-hidden="true"></i>
                            <# } #>
                            
                            <# if (settings.display_style !== 'icon') { #>
                                <span class="social-text">{{ platforms[platform].name }}</span>
                            <# } #>
                        </a>
                    <# } #>
                <# }); #>
            <# } else { #>
                <div class="elementor-alert elementor-alert-warning">
                    <?php _e('Lütfen gösterilecek sosyal medya platformlarını seçin.', 'pratikwp'); ?>
                </div>
            <# } #>
        </div>
        
        <# if (settings.background_type === 'brand' || settings.icon_color_type === 'brand') { #>
        <style>
        {{WRAPPER}} .social-facebook.brand-colors { color: #1877F2; }
        {{WRAPPER}} .social-facebook.brand-colors:hover { background-color: #1877F2; color: white; }
        
        {{WRAPPER}} .social-x.brand-colors { color: #000000; }
        {{WRAPPER}} .social-x.brand-colors:hover { background-color: #000000; color: white; }
        
        {{WRAPPER}} .social-instagram.brand-colors { color: #E4405F; }
        {{WRAPPER}} .social-instagram.brand-colors:hover { background: linear-gradient(45deg, #E4405F, #FFDC80); color: white; }
        
        {{WRAPPER}} .social-youtube.brand-colors { color: #FF0000; }
        {{WRAPPER}} .social-youtube.brand-colors:hover { background-color: #FF0000; color: white; }
        
        {{WRAPPER}} .social-linkedin.brand-colors { color: #0A66C2; }
        {{WRAPPER}} .social-linkedin.brand-colors:hover { background-color: #0A66C2; color: white; }
        
        {{WRAPPER}} .social-tiktok.brand-colors { color: #000000; }
        {{WRAPPER}} .social-tiktok.brand-colors:hover { background-color: #000000; color: white; }
        
        {{WRAPPER}} .social-pinterest.brand-colors { color: #BD081C; }
        {{WRAPPER}} .social-pinterest.brand-colors:hover { background-color: #BD081C; color: white; }
        
        {{WRAPPER}} .social-whatsapp.brand-colors { color: #25D366; }
        {{WRAPPER}} .social-whatsapp.brand-colors:hover { background-color: #25D366; color: white; }
        </style>
        <# } #>
        <?php
    }
}