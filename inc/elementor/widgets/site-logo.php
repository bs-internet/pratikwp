<?php
/**
 * Site Logo Elementor Widget
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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

class PratikWp_Site_Logo_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'pratikwp-site-logo';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Site Logo', 'pratikwp');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-site-logo';
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
        return ['logo', 'site', 'brand', 'image', 'identity'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Logo Content Section
        $this->start_controls_section(
            'logo_content_section',
            [
                'label' => __('Logo Ayarları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Logo Source
        $this->add_control(
            'logo_source',
            [
                'label' => __('Logo Kaynağı', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'customizer',
                'options' => [
                    'customizer' => __('Customizer Logo (Önerilen)', 'pratikwp'),
                    'custom' => __('Özel Logo', 'pratikwp'),
                    'text' => __('Site Adı (Metin)', 'pratikwp'),
                ],
            ]
        );

        // Custom Logo
        $this->add_control(
            'custom_logo',
            [
                'label' => __('Özel Logo Seç', 'pratikwp'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'logo_source' => 'custom',
                ],
            ]
        );

        // Logo Alt Text
        $this->add_control(
            'logo_alt',
            [
                'label' => __('Logo Alt Text', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'default' => get_bloginfo('name'),
                'condition' => [
                    'logo_source!' => 'text',
                ],
            ]
        );

        // Site Title Text
        $this->add_control(
            'site_title_text',
            [
                'label' => __('Site Başlığı', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'default' => get_bloginfo('name'),
                'condition' => [
                    'logo_source' => 'text',
                ],
            ]
        );

        // Logo Link
        $this->add_control(
            'logo_link',
            [
                'label' => __('Logo Linki', 'pratikwp'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => home_url('/'),
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'show_label' => false,
            ]
        );

        // Logo Alignment
        $this->add_responsive_control(
            'logo_alignment',
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Logo Style Section
        $this->start_controls_section(
            'logo_style_section',
            [
                'label' => __('Logo Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'logo_source!' => 'text',
                ],
            ]
        );

        // Logo Size
        $this->add_responsive_control(
            'logo_width',
            [
                'label' => __('Genişlik', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 200,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Logo Max Width
        $this->add_responsive_control(
            'logo_max_width',
            [
                'label' => __('Maksimum Genişlik', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em', 'rem', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Logo Height
        $this->add_responsive_control(
            'logo_height',
            [
                'label' => __('Yükseklik', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem', 'vh'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Object Fit
        $this->add_control(
            'logo_object_fit',
            [
                'label' => __('Resim Uyumu', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Varsayılan', 'pratikwp'),
                    'fill' => __('Doldur', 'pratikwp'),
                    'cover' => __('Kapla', 'pratikwp'),
                    'contain' => __('Sığdır', 'pratikwp'),
                    'scale-down' => __('Küçült', 'pratikwp'),
                    'none' => __('Hiçbiri', 'pratikwp'),
                ],
                'condition' => [
                    'logo_height[size]!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        // Logo Opacity
        $this->add_control(
            'logo_opacity',
            [
                'label' => __('Şeffaflık', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        // Logo Hover Opacity
        $this->add_control(
            'logo_hover_opacity',
            [
                'label' => __('Hover Şeffaflığı', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo:hover img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        // CSS Filters
        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'logo_css_filters',
                'selector' => '{{WRAPPER}} .pratikwp-site-logo img',
            ]
        );

        // Hover CSS Filters
        $this->add_group_control(
            \Elementor\Group_Control_Css_Filter::get_type(),
            [
                'name' => 'logo_hover_css_filters',
                'selector' => '{{WRAPPER}} .pratikwp-site-logo:hover img',
            ]
        );

        // Logo Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'logo_border',
                'selector' => '{{WRAPPER}} .pratikwp-site-logo img',
            ]
        );

        // Logo Border Radius
        $this->add_control(
            'logo_border_radius',
            [
                'label' => __('Kenarlık Yarıçapı', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Logo Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'logo_box_shadow',
                'selector' => '{{WRAPPER}} .pratikwp-site-logo img',
            ]
        );

        $this->end_controls_section();

        // Text Logo Style Section
        $this->start_controls_section(
            'text_logo_style_section',
            [
                'label' => __('Metin Logo Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'logo_source' => 'text',
                ],
            ]
        );

        // Text Color
        $this->add_control(
            'text_logo_color',
            [
                'label' => __('Renk', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo .site-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Hover Color
        $this->add_control(
            'text_logo_hover_color',
            [
                'label' => __('Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-site-logo:hover .site-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_logo_typography',
                'selector' => '{{WRAPPER}} .pratikwp-site-logo .site-title',
            ]
        );

        // Text Shadow
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_logo_text_shadow',
                'selector' => '{{WRAPPER}} .pratikwp-site-logo .site-title',
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'logo_advanced_section',
            [
                'label' => __('Gelişmiş', 'pratikwp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS Classes
        $this->add_control(
            'logo_css_classes',
            [
                'label' => __('CSS Class\'ları', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'title' => __('Özel CSS class\'larını boşlukla ayırarak girin', 'pratikwp'),
            ]
        );

        // Logo Hover Animation
        $this->add_control(
            'logo_hover_animation',
            [
                'label' => __('Hover Animasyonu', 'pratikwp'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        // Schema.org Settings
        $this->add_control(
            'schema_heading',
            [
                'label' => __('Schema.org Ayarları', 'pratikwp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'enable_schema',
            [
                'label' => __('Schema.org Logo Markup\'ını Etkinleştir', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $css_classes = ['pratikwp-site-logo'];
        
        if (!empty($settings['logo_css_classes'])) {
            $css_classes[] = $settings['logo_css_classes'];
        }
        
        if (!empty($settings['logo_hover_animation'])) {
            $css_classes[] = 'elementor-animation-' . $settings['logo_hover_animation'];
        }

        $this->add_render_attribute('wrapper', 'class', $css_classes);
        
        // Schema markup
        $schema_attrs = '';
        if ($settings['enable_schema'] === 'yes') {
            $schema_attrs = 'itemscope itemtype="https://schema.org/Organization"';
        }

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?> <?php echo $schema_attrs; ?>>
            <?php
            // Get logo URL
            $logo_url = '';
            if (!empty($settings['logo_link']['url'])) {
                $logo_url = $settings['logo_link']['url'];
                $this->add_link_attributes('logo_link', $settings['logo_link']);
            }

            // Logo link opening tag
            if ($logo_url) {
                echo '<a ' . $this->get_render_attribute_string('logo_link') . '>';
            }

            // Render logo based on source
            switch ($settings['logo_source']) {
                case 'custom':
                    if (!empty($settings['custom_logo']['url'])) {
                        $alt_text = $settings['logo_alt'] ?: get_bloginfo('name');
                        $schema_logo_attr = ($settings['enable_schema'] === 'yes') ? 'itemprop="logo"' : '';
                        echo '<img src="' . esc_url($settings['custom_logo']['url']) . '" alt="' . esc_attr($alt_text) . '" ' . $schema_logo_attr . '>';
                    }
                    break;
                    
                case 'text':
                    $site_title = $settings['site_title_text'] ?: get_bloginfo('name');
                    $schema_name_attr = ($settings['enable_schema'] === 'yes') ? 'itemprop="name"' : '';
                    echo '<span class="site-title" ' . $schema_name_attr . '>' . esc_html($site_title) . '</span>';
                    break;
                    
                default: // customizer
                    $custom_logo_id = get_theme_mod('custom_logo');
                    if ($custom_logo_id) {
                        $logo_image = wp_get_attachment_image_src($custom_logo_id, 'full');
                        if ($logo_image) {
                            $alt_text = $settings['logo_alt'] ?: get_bloginfo('name');
                            $schema_logo_attr = ($settings['enable_schema'] === 'yes') ? 'itemprop="logo"' : '';
                            echo '<img src="' . esc_url($logo_image[0]) . '" alt="' . esc_attr($alt_text) . '" ' . $schema_logo_attr . '>';
                        }
                    } else {
                        // Fallback to site title if no logo is set
                        $site_title = get_bloginfo('name');
                        $schema_name_attr = ($settings['enable_schema'] === 'yes') ? 'itemprop="name"' : '';
                        echo '<span class="site-title" ' . $schema_name_attr . '>' . esc_html($site_title) . '</span>';
                    }
                    break;
            }

            // Logo link closing tag
            if ($logo_url) {
                echo '</a>';
            }
            ?>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-site-logo'];
        
        if (settings.logo_css_classes) {
            css_classes.push(settings.logo_css_classes);
        }
        
        if (settings.logo_hover_animation) {
            css_classes.push('elementor-animation-' + settings.logo_hover_animation);
        }

        var schema_attrs = '';
        if (settings.enable_schema === 'yes') {
            schema_attrs = 'itemscope itemtype="https://schema.org/Organization"';
        }
        #>
        <div class="{{ css_classes.join(' ') }}" {{{ schema_attrs }}}>
            <# if (settings.logo_link.url) { #>
                <a href="{{ settings.logo_link.url }}">
            <# } #>
            
            <# if (settings.logo_source === 'custom' && settings.custom_logo.url) { #>
                <# 
                var alt_text = settings.logo_alt || '<?php echo get_bloginfo('name'); ?>';
                var schema_logo_attr = (settings.enable_schema === 'yes') ? 'itemprop="logo"' : '';
                #>
                <img src="{{ settings.custom_logo.url }}" alt="{{ alt_text }}" {{{ schema_logo_attr }}}>
            <# } else if (settings.logo_source === 'text') { #>
                <# 
                var site_title = settings.site_title_text || '<?php echo get_bloginfo('name'); ?>';
                var schema_name_attr = (settings.enable_schema === 'yes') ? 'itemprop="name"' : '';
                #>
                <span class="site-title" {{{ schema_name_attr }}}>{{{ site_title }}}</span>
            <# } else { #>
                <# 
                var schema_name_attr = (settings.enable_schema === 'yes') ? 'itemprop="name"' : '';
                #>
                <span class="site-title" {{{ schema_name_attr }}}>{{ '<?php echo get_bloginfo('name'); ?>' }}</span>
            <# } #>
            
            <# if (settings.logo_link.url) { #>
                </a>
            <# } #>
        </div>
        <?php
    }
}