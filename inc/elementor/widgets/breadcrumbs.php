<?php
/**
 * Breadcrumbs Elementor Widget
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
use Elementor\Core\Schemes\Typography;

class PratikWp_Breadcrumbs_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'pratikwp-breadcrumbs';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Breadcrumbs', 'pratikwp');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-product-breadcrumbs';
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
        return ['breadcrumb', 'navigation', 'path', 'trail', 'hierarchy'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'breadcrumbs_content_section',
            [
                'label' => __('Breadcrumb Ayarları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Show Home
        $this->add_control(
            'show_home',
            [
                'label' => __('Ana Sayfa Linkini Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Home Text
        $this->add_control(
            'home_text',
            [
                'label' => __('Ana Sayfa Metni', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Ana Sayfa', 'pratikwp'),
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );

        // Home Icon
        $this->add_control(
            'home_icon',
            [
                'label' => __('Ana Sayfa İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-home',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );

        // Separator
        $this->add_control(
            'separator_type',
            [
                'label' => __('Ayırıcı Tipi', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text' => __('Metin', 'pratikwp'),
                    'icon' => __('İkon', 'pratikwp'),
                ],
            ]
        );

        // Separator Text
        $this->add_control(
            'separator_text',
            [
                'label' => __('Ayırıcı Metin', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'default' => '/',
                'condition' => [
                    'separator_type' => 'text',
                ],
            ]
        );

        // Separator Icon
        $this->add_control(
            'separator_icon',
            [
                'label' => __('Ayırıcı İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-chevron-right',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'separator_type' => 'icon',
                ],
            ]
        );

        // Max Items
        $this->add_control(
            'max_items',
            [
                'label' => __('Maksimum Öğe Sayısı', 'pratikwp'),
                'type' => Controls_Manager::NUMBER,
                'min' => 2,
                'max' => 10,
                'default' => 5,
                'description' => __('Çok uzun breadcrumb\'ları kısaltmak için', 'pratikwp'),
            ]
        );

        // Truncate Style
        $this->add_control(
            'truncate_style',
            [
                'label' => __('Kısaltma Stili', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'middle',
                'options' => [
                    'middle' => __('Ortadan Kısalt (...)', 'pratikwp'),
                    'beginning' => __('Baştan Kısalt', 'pratikwp'),
                    'end' => __('Sondan Kısalt', 'pratikwp'),
                ],
                'condition' => [
                    'max_items!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'breadcrumbs_layout_section',
            [
                'label' => __('Düzen', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'breadcrumb_alignment',
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
                    '{{WRAPPER}} .pratikwp-breadcrumbs' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Display Style
        $this->add_control(
            'display_style',
            [
                'label' => __('Görünüm Stili', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __('Satır İçi', 'pratikwp'),
                    'block' => __('Blok (Alt Alta)', 'pratikwp'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-item' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'breadcrumbs_style_section',
            [
                'label' => __('Genel Stil', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'breadcrumb_background',
                'selector' => '{{WRAPPER}} .pratikwp-breadcrumbs',
            ]
        );

        // Padding
        $this->add_responsive_control(
            'breadcrumb_padding',
            [
                'label' => __('Padding', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-breadcrumbs' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'breadcrumb_margin',
            [
                'label' => __('Margin', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-breadcrumbs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'breadcrumb_border',
                'selector' => '{{WRAPPER}} .pratikwp-breadcrumbs',
            ]
        );

        // Border Radius
        $this->add_responsive_control(
            'breadcrumb_border_radius',
            [
                'label' => __('Border Radius', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-breadcrumbs' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Items Style Section
        $this->start_controls_section(
            'breadcrumb_items_style_section',
            [
                'label' => __('Breadcrumb Öğeleri', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'breadcrumb_typography',
                'selector' => '{{WRAPPER}} .breadcrumb-item',
            ]
        );

        // Text Color
        $this->add_control(
            'breadcrumb_text_color',
            [
                'label' => __('Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-item' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Color
        $this->add_control(
            'breadcrumb_link_color',
            [
                'label' => __('Link Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Hover Color
        $this->add_control(
            'breadcrumb_link_hover_color',
            [
                'label' => __('Link Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Active Color
        $this->add_control(
            'breadcrumb_active_color',
            [
                'label' => __('Aktif Öğe Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-item.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Item Spacing
        $this->add_responsive_control(
            'breadcrumb_item_spacing',
            [
                'label' => __('Öğeler Arası Boşluk', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .breadcrumb-separator' => 'margin: 0 {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Separator Style Section
        $this->start_controls_section(
            'breadcrumb_separator_style_section',
            [
                'label' => __('Ayırıcı Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Separator Color
        $this->add_control(
            'separator_color',
            [
                'label' => __('Ayırıcı Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#999999',
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-separator' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Separator Size
        $this->add_responsive_control(
            'separator_size',
            [
                'label' => __('Ayırıcı Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 8,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-separator' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Home Icon Style Section
        $this->start_controls_section(
            'home_icon_style_section',
            [
                'label' => __('Ana Sayfa İkonu', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_home' => 'yes',
                ],
            ]
        );

        // Home Icon Color
        $this->add_control(
            'home_icon_color',
            [
                'label' => __('İkon Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Home Icon Size
        $this->add_responsive_control(
            'home_icon_size',
            [
                'label' => __('İkon Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Home Icon Spacing
        $this->add_responsive_control(
            'home_icon_spacing',
            [
                'label' => __('İkon Boşluğu', 'pratikwp'),
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
                    'size' => 5,
                ],
                'selectors' => [
                    '{{WRAPPER}} .breadcrumb-home-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'breadcrumb_advanced_section',
            [
                'label' => __('Gelişmiş', 'pratikwp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS Classes
        $this->add_control(
            'breadcrumb_css_classes',
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
                'label' => __('Schema.org BreadcrumbList Markup\'ını Etkinleştir', 'pratikwp'),
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
        if (is_front_page()) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . __('Breadcrumbs ana sayfada gösterilmez.', 'pratikwp') . '</div>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $items = $this->get_breadcrumb_items();
        
        if (empty($items)) {
            return;
        }

        // Apply max items limit if set
        if (!empty($settings['max_items']) && count($items) > $settings['max_items']) {
            $items = $this->truncate_items($items, $settings['max_items'], $settings['truncate_style']);
        }

        $css_classes = ['pratikwp-breadcrumbs'];
        
        if (!empty($settings['breadcrumb_css_classes'])) {
            $css_classes[] = $settings['breadcrumb_css_classes'];
        }

        $this->add_render_attribute('wrapper', 'class', $css_classes);
        
        // Schema markup
        $schema_attrs = '';
        if ($settings['enable_schema'] === 'yes') {
            $schema_attrs = 'itemscope itemtype="https://schema.org/BreadcrumbList"';
        }

        ?>
        <nav <?php echo $this->get_render_attribute_string('wrapper'); ?> aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>" <?php echo $schema_attrs; ?>>
            <ol class="breadcrumb">
                <?php
                $position = 1;
                foreach ($items as $index => $item) {
                    $is_last = ($index === count($items) - 1);
                    $item_class = 'breadcrumb-item';
                    
                    if ($is_last || $item['current']) {
                        $item_class .= ' active';
                    }
                    
                    $schema_item_attrs = '';
                    if ($settings['enable_schema'] === 'yes') {
                        $schema_item_attrs = 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"';
                    }
                    ?>
                    <li class="<?php echo esc_attr($item_class); ?>" <?php echo $schema_item_attrs; ?>>
                        <?php if (!$is_last && !empty($item['url'])) : ?>
                            <a href="<?php echo esc_url($item['url']); ?>" <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="item"' : ''; ?>>
                                <?php if ($index === 0 && $settings['show_home'] === 'yes' && !empty($settings['home_icon']['value'])) : ?>
                                    <span class="breadcrumb-home-icon">
                                        <?php \Elementor\Icons_Manager::render_icon($settings['home_icon'], ['aria-hidden' => 'true']); ?>
                                    </span>
                                <?php endif; ?>
                                <span <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="name"' : ''; ?>>
                                    <?php echo esc_html($item['title']); ?>
                                </span>
                            </a>
                        <?php else : ?>
                            <?php if ($index === 0 && $settings['show_home'] === 'yes' && !empty($settings['home_icon']['value'])) : ?>
                                <span class="breadcrumb-home-icon">
                                    <?php \Elementor\Icons_Manager::render_icon($settings['home_icon'], ['aria-hidden' => 'true']); ?>
                                </span>
                            <?php endif; ?>
                            <span <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="name"' : ''; ?>>
                                <?php echo esc_html($item['title']); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($settings['enable_schema'] === 'yes') : ?>
                            <meta itemprop="position" content="<?php echo $position; ?>">
                        <?php endif; ?>
                        
                        <?php if (!$is_last) : ?>
                            <span class="breadcrumb-separator" aria-hidden="true">
                                <?php if ($settings['separator_type'] === 'icon' && !empty($settings['separator_icon']['value'])) : ?>
                                    <?php \Elementor\Icons_Manager::render_icon($settings['separator_icon']); ?>
                                <?php else : ?>
                                    <?php echo esc_html($settings['separator_text']); ?>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </li>
                    <?php
                    $position++;
                }
                ?>
            </ol>
        </nav>
        <?php
    }

    /**
     * Get breadcrumb items
     */
    protected function get_breadcrumb_items() {
        $items = [];
        
        // Home
        $items[] = [
            'title' => $this->get_settings('home_text') ?: __('Ana Sayfa', 'pratikwp'),
            'url' => home_url('/'),
            'current' => false
        ];
        
        if (is_category() || is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $items[] = [
                    'title' => $category->name,
                    'url' => get_category_link($category->term_id),
                    'current' => is_category()
                ];
            }
            
            if (is_single()) {
                $items[] = [
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'current' => true
                ];
            }
        } elseif (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $items[] = [
                    'title' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                    'current' => false
                ];
            }
            
            $items[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'current' => true
            ];
        } elseif (is_archive()) {
            $items[] = [
                'title' => get_the_archive_title(),
                'url' => '',
                'current' => true
            ];
        } elseif (is_search()) {
            $items[] = [
                'title' => sprintf(__('Arama: %s', 'pratikwp'), get_search_query()),
                'url' => '',
                'current' => true
            ];
        } elseif (is_404()) {
            $items[] = [
                'title' => __('404 - Sayfa Bulunamadı', 'pratikwp'),
                'url' => '',
                'current' => true
            ];
        }
        
        return apply_filters('pratikwp_breadcrumb_items', $items);
    }

    /**
     * Truncate items based on max limit
     */
    protected function truncate_items($items, $max_items, $style = 'middle') {
        if (count($items) <= $max_items) {
            return $items;
        }
        
        switch ($style) {
            case 'beginning':
                // Keep last items
                return array_slice($items, -$max_items);
                
            case 'end':
                // Keep first items
                return array_slice($items, 0, $max_items);
                
            case 'middle':
            default:
                // Keep first, last and add ellipsis in middle
                $keep_start = ceil(($max_items - 1) / 2);
                $keep_end = floor(($max_items - 1) / 2);
                
                $start_items = array_slice($items, 0, $keep_start);
                $end_items = array_slice($items, -$keep_end);
                
                // Add ellipsis item
                $ellipsis = [
                    'title' => '...',
                    'url' => '',
                    'current' => false
                ];
                
                return array_merge($start_items, [$ellipsis], $end_items);
        }
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-breadcrumbs'];
        
        if (settings.breadcrumb_css_classes) {
            css_classes.push(settings.breadcrumb_css_classes);
        }

        var schema_attrs = '';
        if (settings.enable_schema === 'yes') {
            schema_attrs = 'itemscope itemtype="https://schema.org/BreadcrumbList"';
        }

        var home_text = settings.home_text || '<?php _e('Ana Sayfa', 'pratikwp'); ?>';
        var separator_content = '';
        
        if (settings.separator_type === 'icon' && settings.separator_icon && settings.separator_icon.value) {
            separator_content = '<i class="' + settings.separator_icon.value + '"></i>';
        } else {
            separator_content = settings.separator_text || '/';
        }
        #>
        <nav class="{{ css_classes.join(' ') }}" aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>" {{{ schema_attrs }}}>
            <ol class="breadcrumb">
                <# if (settings.show_home === 'yes') { #>
                    <li class="breadcrumb-item" {{{ (settings.enable_schema === 'yes') ? 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"' : '' }}}>
                        <a href="#" {{{ (settings.enable_schema === 'yes') ? 'itemprop="item"' : '' }}}>
                            <# if (settings.home_icon && settings.home_icon.value) { #>
                                <span class="breadcrumb-home-icon">
                                    <i class="{{ settings.home_icon.value }}" aria-hidden="true"></i>
                                </span>
                            <# } #>
                            <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                                {{{ home_text }}}
                            </span>
                        </a>
                        <# if (settings.enable_schema === 'yes') { #>
                            <meta itemprop="position" content="1">
                        <# } #>
                        <span class="breadcrumb-separator" aria-hidden="true">{{{ separator_content }}}</span>
                    </li>
                <# } #>
                
                <li class="breadcrumb-item" {{{ (settings.enable_schema === 'yes') ? 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"' : '' }}}>
                    <a href="#" {{{ (settings.enable_schema === 'yes') ? 'itemprop="item"' : '' }}}>
                        <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                            <?php _e('Kategori', 'pratikwp'); ?>
                        </span>
                    </a>
                    <# if (settings.enable_schema === 'yes') { #>
                        <meta itemprop="position" content="2">
                    <# } #>
                    <span class="breadcrumb-separator" aria-hidden="true">{{{ separator_content }}}</span>
                </li>
                
                <li class="breadcrumb-item active" {{{ (settings.enable_schema === 'yes') ? 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"' : '' }}}>
                    <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                        <?php _e('Mevcut Sayfa', 'pratikwp'); ?>
                    </span>
                    <# if (settings.enable_schema === 'yes') { #>
                        <meta itemprop="position" content="3">
                    <# } #>
                </li>
            </ol>
        </nav>
        <?php
    }
}