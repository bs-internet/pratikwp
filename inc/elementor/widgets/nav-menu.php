<?php
/**
 * Navigation Menu Elementor Widget
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
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

class PratikWp_Nav_Menu_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'pratikwp-nav-menu';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Navigation Menü', 'pratikwp');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-nav-menu';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['pratikwp-theme'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['menu', 'nav', 'navigation', 'link', 'navbar'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'menu_content_section',
            [
                'label' => __('Menü Ayarları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Menu Selection
        $menus = wp_get_nav_menus();
        $menu_options = ['' => __('-- Menü Seçin --', 'pratikwp')];
        
        foreach ($menus as $menu) {
            $menu_options[$menu->term_id] = $menu->name;
        }

        $this->add_control(
            'menu',
            [
                'label' => __('Menü', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'options' => $menu_options,
                'default' => '',
                'description' => __('Görünüm > Menüler sayfasından menü oluşturun', 'pratikwp'),
            ]
        );

        // Layout
        $this->add_control(
            'layout',
            [
                'label' => __('Menü Düzeni', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => __('Yatay', 'pratikwp'),
                    'vertical' => __('Dikey', 'pratikwp'),
                    'toggle' => __('Hamburger (Mobil)', 'pratikwp'),
                ],
            ]
        );

        // Menu Alignment
        $this->add_responsive_control(
            'menu_alignment',
            [
                'label' => __('Menü Hizalaması', 'pratikwp'),
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
                    'justify' => [
                        'title' => __('Yayıl', 'pratikwp'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'left',
                'condition' => [
                    'layout!' => 'toggle',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .pratikwp-nav-menu .navbar-nav' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        // Submenu Indicator
        $this->add_control(
            'submenu_indicator',
            [
                'label' => __('Alt Menü Göstergesi', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'arrow',
                'options' => [
                    'none' => __('Yok', 'pratikwp'),
                    'arrow' => __('Ok İşareti', 'pratikwp'),
                    'plus' => __('Artı İşareti', 'pratikwp'),
                    'caret' => __('Caret', 'pratikwp'),
                    'custom' => __('Özel İkon', 'pratikwp'),
                ],
            ]
        );

        // Custom Indicator Icon
        $this->add_control(
            'custom_indicator',
            [
                'label' => __('Özel Gösterge İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'condition' => [
                    'submenu_indicator' => 'custom',
                ],
            ]
        );

        // Mobile Breakpoint
        $this->add_control(
            'mobile_breakpoint',
            [
                'label' => __('Mobil Kırılma Noktası', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'md',
                'options' => [
                    'sm' => __('Small (576px)', 'pratikwp'),
                    'md' => __('Medium (768px)', 'pratikwp'),
                    'lg' => __('Large (992px)', 'pratikwp'),
                    'xl' => __('Extra Large (1200px)', 'pratikwp'),
                    'never' => __('Hiçbir Zaman', 'pratikwp'),
                ],
                'condition' => [
                    'layout!' => 'toggle',
                ],
            ]
        );

        $this->end_controls_section();

        // Mobile Menu Section
        $this->start_controls_section(
            'mobile_menu_section',
            [
                'label' => __('Mobil Menü', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'layout!' => 'vertical',
                ],
            ]
        );

        // Hamburger Icon
        $this->add_control(
            'hamburger_icon',
            [
                'label' => __('Hamburger İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-bars',
                    'library' => 'fa-solid',
                ],
            ]
        );

        // Close Icon
        $this->add_control(
            'close_icon',
            [
                'label' => __('Kapatma İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-times',
                    'library' => 'fa-solid',
                ],
            ]
        );

        // Mobile Menu Position
        $this->add_control(
            'mobile_menu_position',
            [
                'label' => __('Mobil Menü Pozisyonu', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide-right',
                'options' => [
                    'slide-right' => __('Sağdan Kaydır', 'pratikwp'),
                    'slide-left' => __('Soldan Kaydır', 'pratikwp'),
                    'slide-top' => __('Yukarıdan Kaydır', 'pratikwp'),
                    'slide-bottom' => __('Aşağıdan Kaydır', 'pratikwp'),
                    'fade' => __('Belir', 'pratikwp'),
                    'dropdown' => __('Aşağı Açıl', 'pratikwp'),
                ],
            ]
        );

        $this->end_controls_section();

        // Menu Items Style
        $this->start_controls_section(
            'menu_items_style',
            [
                'label' => __('Menü Öğeleri', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .nav-link',
            ]
        );

        // Text Color
        $this->add_control(
            'menu_text_color',
            [
                'label' => __('Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Text Hover Color
        $this->add_control(
            'menu_text_hover_color',
            [
                'label' => __('Hover Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-link:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-item:hover .nav-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Active Color
        $this->add_control(
            'menu_active_color',
            [
                'label' => __('Aktif Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-item.active .nav-link' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-link.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Menu Item Padding
        $this->add_responsive_control(
            'menu_item_padding',
            [
                'label' => __('Menü Öğesi Padding', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 10,
                    'right' => 15,
                    'bottom' => 10,
                    'left' => 15,
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Menu Item Margin
        $this->add_responsive_control(
            'menu_item_margin',
            [
                'label' => __('Menü Öğesi Margin', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Menu Item Background
        $this->start_controls_section(
            'menu_item_background_section',
            [
                'label' => __('Menü Öğesi Arka Plan', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_background',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .nav-link',
            ]
        );

        // Hover Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_hover_background',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .nav-link:hover',
            ]
        );

        // Active Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'menu_item_active_background',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .nav-link.active, {{WRAPPER}} .pratikwp-nav-menu .nav-item.active .nav-link',
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'menu_item_border',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .nav-link',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'menu_item_border_radius',
            [
                'label' => __('Kenarlık Yarıçapı', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .nav-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Dropdown Style
        $this->start_controls_section(
            'dropdown_style_section',
            [
                'label' => __('Dropdown Menü', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Dropdown Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'dropdown_background',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .dropdown-menu',
            ]
        );

        // Dropdown Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_border',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .dropdown-menu',
            ]
        );

        // Dropdown Border Radius
        $this->add_responsive_control(
            'dropdown_border_radius',
            [
                'label' => __('Kenarlık Yarıçapı', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .dropdown-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Dropdown Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_shadow',
                'selector' => '{{WRAPPER}} .pratikwp-nav-menu .dropdown-menu',
            ]
        );

        // Dropdown Item Padding
        $this->add_responsive_control(
            'dropdown_item_padding',
            [
                'label' => __('Dropdown Öğesi Padding', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-nav-menu .dropdown-menu .nav-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Mobile Menu Style
        $this->start_controls_section(
            'mobile_menu_style_section',
            [
                'label' => __('Mobil Menü Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'layout!' => 'vertical',
                ],
            ]
        );

        // Toggle Button Style
        $this->add_control(
            'toggle_button_heading',
            [
                'label' => __('Toggle Butonu', 'pratikwp'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        // Toggle Button Size
        $this->add_responsive_control(
            'toggle_button_size',
            [
                'label' => __('Buton Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mobile-menu-toggle' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Toggle Button Color
        $this->add_control(
            'toggle_button_color',
            [
                'label' => __('Buton Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .mobile-menu-toggle' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Toggle Button Background
        $this->add_control(
            'toggle_button_background',
            [
                'label' => __('Buton Arka Plan', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mobile-menu-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        // Mobile Menu Panel Style
        $this->add_control(
            'mobile_panel_heading',
            [
                'label' => __('Mobil Menü Paneli', 'pratikwp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        // Mobile Menu Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'mobile_menu_background',
                'selector' => '{{WRAPPER}} .mobile-menu-panel',
            ]
        );

        // Mobile Menu Width
        $this->add_responsive_control(
            'mobile_menu_width',
            [
                'label' => __('Panel Genişliği', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 200,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .mobile-menu-panel' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'menu_advanced_section',
            [
                'label' => __('Gelişmiş', 'pratikwp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS Classes
        $this->add_control(
            'menu_css_classes',
            [
                'label' => __('CSS Class\'ları', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'title' => __('Özel CSS class\'larını boşlukla ayırarak girin', 'pratikwp'),
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
                'label' => __('Schema.org SiteNavigationElement Markup\'ını Etkinleştir', 'pratikwp'),
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
        
        if (empty($settings['menu'])) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . __('Lütfen bir menü seçin.', 'pratikwp') . '</div>';
            }
            return;
        }

        $css_classes = ['pratikwp-nav-menu'];
        if (!empty($settings['menu_css_classes'])) {
            $css_classes[] = $settings['menu_css_classes'];
        }
        $css_classes[] = 'layout-' . $settings['layout'];
        
        $this->add_render_attribute('wrapper', 'class', $css_classes);

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <?php if ($settings['layout'] !== 'vertical') : ?>
                <button class="mobile-menu-toggle" type="button" aria-label="<?php esc_attr_e('Menüyü Aç/Kapat', 'pratikwp'); ?>">
                    <span class="toggle-open">
                        <?php \Elementor\Icons_Manager::render_icon($settings['hamburger_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                    <span class="toggle-close" style="display: none;">
                        <?php \Elementor\Icons_Manager::render_icon($settings['close_icon'], ['aria-hidden' => 'true']); ?>
                    </span>
                </button>
            <?php endif; ?>
            
            <div class="menu-container">
                <nav class="navbar">
                    <?php
                    // Menu arguments
                    $menu_args = [
                        'menu' => $settings['menu'],
                        'menu_class' => 'navbar-nav ' . ($settings['layout'] === 'vertical' ? 'flex-column' : ''),
                        'container' => false,
                        'walker' => new PratikWp_Walker_Nav_Menu(),
                        'fallback_cb' => false,
                    ];
                    
                    wp_nav_menu($menu_args);
                    ?>
                </nav>
            </div>
            
            <?php if ($settings['layout'] !== 'vertical') : ?>
                <div class="mobile-menu-overlay"></div>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-nav-menu'];
        
        if (settings.menu_css_classes) {
            css_classes.push(settings.menu_css_classes);
        }
        
        css_classes.push('layout-' + settings.layout);
        css_classes.push('mobile-breakpoint-' + settings.mobile_breakpoint);

        var schema_attrs = '';
        if (settings.enable_schema === 'yes') {
            schema_attrs = 'itemscope itemtype="https://schema.org/SiteNavigationElement"';
        }
        #>
        <div class="{{ css_classes.join(' ') }}" {{{ schema_attrs }}}>
            <# if (settings.layout === 'toggle' || settings.mobile_breakpoint !== 'never') { #>
                <button class="mobile-menu-toggle d-{{ settings.mobile_breakpoint }}-none" type="button" aria-label="<?php esc_attr_e('Menüyü Aç/Kapat', 'pratikwp'); ?>">
                    <span class="toggle-open">
                        <# if (settings.hamburger_icon && settings.hamburger_icon.value) { #>
                            <i class="{{ settings.hamburger_icon.value }}"></i>
                        <# } #>
                    </span>
                    <span class="toggle-close" style="display: none;">
                        <# if (settings.close_icon && settings.close_icon.value) { #>
                            <i class="{{ settings.close_icon.value }}"></i>
                        <# } #>
                    </span>
                </button>
            <# } #>
            
            <div class="menu-container {{ (settings.layout !== 'toggle') ? 'd-none d-' + settings.mobile_breakpoint + '-block' : 'mobile-menu-panel' }}">
                <nav class="navbar" {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                    <# if (settings.menu) { #>
                        <div class="navbar-nav {{ (settings.layout === 'vertical') ? 'flex-column' : '' }}">
                            <div class="nav-item">
                                <a class="nav-link" href="#"><?php _e('Menü Öğesi 1', 'pratikwp'); ?></a>
                            </div>
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#"><?php _e('Dropdown Menü', 'pratikwp'); ?></a>
                                <div class="dropdown-menu">
                                    <a class="nav-link" href="#"><?php _e('Alt Menü 1', 'pratikwp'); ?></a>
                                    <a class="nav-link" href="#"><?php _e('Alt Menü 2', 'pratikwp'); ?></a>
                                </div>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" href="#"><?php _e('Menü Öğesi 3', 'pratikwp'); ?></a>
                            </div>
                        </div>
                    <# } else { #>
                        <div class="elementor-alert elementor-alert-warning">
                            <?php _e('Lütfen bir menü seçin.', 'pratikwp'); ?>
                        </div>
                    <# } #>
                </nav>
            </div>
            
            <# if (settings.layout !== 'vertical') { #>
                <div class="mobile-menu-overlay"></div>
            <# } #>
        </div>
        <?php
    }
}