<?php
/**
 * Post Meta Elementor Widget
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
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;

class PratikWp_Post_Meta_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'pratikwp-post-meta';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Post Meta', 'pratikwp');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-post-info';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['pratikwp-post'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['post', 'meta', 'author', 'date', 'category', 'comment', 'reading time'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'meta_content_section',
            [
                'label' => __('Meta Öğeleri', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Show Date
        $this->add_control(
            'show_date',
            [
                'label' => __('Tarihi Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Date Format
        $this->add_control(
            'date_format',
            [
                'label' => __('Tarih Formatı', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => __('WordPress Varsayılanı', 'pratikwp'),
                    'F j, Y' => __('Ocak 15, 2024', 'pratikwp'),
                    'Y-m-d' => __('2024-01-15', 'pratikwp'),
                    'd/m/Y' => __('15/01/2024', 'pratikwp'),
                    'd.m.Y' => __('15.01.2024', 'pratikwp'),
                    'j F Y' => __('15 Ocak 2024', 'pratikwp'),
                    'relative' => __('Göreceli (2 gün önce)', 'pratikwp'),
                    'custom' => __('Özel Format', 'pratikwp'),
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        // Custom Date Format
        $this->add_control(
            'custom_date_format',
            [
                'label' => __('Özel Tarih Formatı', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'default' => 'F j, Y',
                'description' => __('PHP tarih formatı kullanın (örn: F j, Y)', 'pratikwp'),
                'condition' => [
                    'show_date' => 'yes',
                    'date_format' => 'custom',
                ],
            ]
        );

        // Date Icon
        $this->add_control(
            'date_icon',
            [
                'label' => __('Tarih İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-calendar-alt',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'show_date' => 'yes',
                ],
            ]
        );

        // Show Author
        $this->add_control(
            'show_author',
            [
                'label' => __('Yazarı Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Author Link
        $this->add_control(
            'author_link',
            [
                'label' => __('Yazar Linkini Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'condition' => [
                    'show_author' => 'yes',
                ],
            ]
        );

        // Author Icon
        $this->add_control(
            'author_icon',
            [
                'label' => __('Yazar İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-user',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'show_author' => 'yes',
                ],
            ]
        );

        // Show Categories
        $this->add_control(
            'show_categories',
            [
                'label' => __('Kategorileri Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Categories Icon
        $this->add_control(
            'categories_icon',
            [
                'label' => __('Kategori İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-folder',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_categories' => 'yes',
                ],
            ]
        );

        // Show Tags
        $this->add_control(
            'show_tags',
            [
                'label' => __('Etiketleri Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        // Tags Icon
        $this->add_control(
            'tags_icon',
            [
                'label' => __('Etiket İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-tags',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'show_tags' => 'yes',
                ],
            ]
        );

        // Show Comments
        $this->add_control(
            'show_comments',
            [
                'label' => __('Yorum Sayısını Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Comments Icon
        $this->add_control(
            'comments_icon',
            [
                'label' => __('Yorum İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-comment',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'show_comments' => 'yes',
                ],
            ]
        );

        // Show Reading Time
        $this->add_control(
            'show_reading_time',
            [
                'label' => __('Okuma Süresini Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        // Reading Time Icon
        $this->add_control(
            'reading_time_icon',
            [
                'label' => __('Okuma Süresi İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-clock',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'show_reading_time' => 'yes',
                ],
            ]
        );

        // Show Views Count
        $this->add_control(
            'show_views',
            [
                'label' => __('Görüntülenme Sayısını Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        // Views Icon
        $this->add_control(
            'views_icon',
            [
                'label' => __('Görüntülenme İkonu', 'pratikwp'),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'far fa-eye',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'show_views' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Layout Section
        $this->start_controls_section(
            'meta_layout_section',
            [
                'label' => __('Düzen Ayarları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Layout Style
        $this->add_control(
            'layout_style',
            [
                'label' => __('Düzen Stili', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inline',
                'options' => [
                    'inline' => __('Satır İçi', 'pratikwp'),
                    'list' => __('Liste Halinde', 'pratikwp'),
                    'grid' => __('Grid', 'pratikwp'),
                ],
            ]
        );

        // Separator
        $this->add_control(
            'separator',
            [
                'label' => __('Ayırıcı', 'pratikwp'),
                'type' => Controls_Manager::TEXT,
                'default' => '•',
                'condition' => [
                    'layout_style' => 'inline',
                ],
            ]
        );

        // Alignment
        $this->add_responsive_control(
            'meta_alignment',
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
                    '{{WRAPPER}} .pratikwp-post-meta' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        // Grid Columns
        $this->add_responsive_control(
            'grid_columns',
            [
                'label' => __('Grid Sütunları', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => '2',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'condition' => [
                    'layout_style' => 'grid',
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta.grid-layout' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'meta_style_section',
            [
                'label' => __('Stil', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .pratikwp-post-meta',
            ]
        );

        // Text Color
        $this->add_control(
            'meta_color',
            [
                'label' => __('Metin Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Color
        $this->add_control(
            'meta_link_color',
            [
                'label' => __('Link Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Hover Color
        $this->add_control(
            'meta_link_hover_color',
            [
                'label' => __('Link Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Spacing
        $this->add_responsive_control(
            'meta_spacing',
            [
                'label' => __('Meta Öğeleri Arası Boşluk', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta.inline-layout .meta-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pratikwp-post-meta.list-layout .meta-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pratikwp-post-meta.grid-layout' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Icon Style Section
        $this->start_controls_section(
            'meta_icon_style_section',
            [
                'label' => __('İkon Stilleri', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Icon Size
        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __('İkon Boyutu', 'pratikwp'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta .meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Icon Color
        $this->add_control(
            'icon_color',
            [
                'label' => __('İkon Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-post-meta .meta-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Icon Spacing
        $this->add_responsive_control(
            'icon_spacing',
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
                    '{{WRAPPER}} .pratikwp-post-meta .meta-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'meta_advanced_section',
            [
                'label' => __('Gelişmiş', 'pratikwp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS Classes
        $this->add_control(
            'meta_css_classes',
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
                'label' => __('Schema.org Markup\'ını Etkinleştir', 'pratikwp'),
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
        if (!is_singular('post')) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-info">' . __('Bu widget sadece tekil yazı sayfalarında çalışır.', 'pratikwp') . '</div>';
            }
            return;
        }

        $settings = $this->get_settings_for_display();
        $post_id = get_the_ID();
        
        $css_classes = ['pratikwp-post-meta'];
        $css_classes[] = $settings['layout_style'] . '-layout';
        
        if (!empty($settings['meta_css_classes'])) {
            $css_classes[] = $settings['meta_css_classes'];
        }

        $this->add_render_attribute('wrapper', 'class', $css_classes);
        
        // Schema markup
        $schema_attrs = '';
        if ($settings['enable_schema'] === 'yes') {
            $schema_attrs = 'itemscope itemtype="https://schema.org/BlogPosting"';
        }

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?> <?php echo $schema_attrs; ?>>
            <?php
            // Date
            if ($settings['show_date'] === 'yes') {
                $this->render_date($settings);
            }
            
            // Author
            if ($settings['show_author'] === 'yes') {
                $this->render_author($settings);
            }
            
            // Categories
            if ($settings['show_categories'] === 'yes' && has_category()) {
                $this->render_categories($settings);
            }
            
            // Tags
            if ($settings['show_tags'] === 'yes' && has_tag()) {
                $this->render_tags($settings);
            }
            
            // Comments
            if ($settings['show_comments'] === 'yes' && (comments_open() || get_comments_number())) {
                $this->render_comments($settings);
            }
            
            // Reading Time
            if ($settings['show_reading_time'] === 'yes') {
                $this->render_reading_time($settings);
            }
            
            // Views Count
            if ($settings['show_views'] === 'yes') {
                $this->render_views($settings);
            }
            ?>
        </div>
    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-post-meta'];
        css_classes.push(settings.layout_style + '-layout');
        
        if (settings.meta_css_classes) {
            css_classes.push(settings.meta_css_classes);
        }

        var schema_attrs = '';
        if (settings.enable_schema === 'yes') {
            schema_attrs = 'itemscope itemtype="https://schema.org/BlogPosting"';
        }

        var separator = (settings.layout_style === 'inline') ? '<span class="meta-separator">' + settings.separator + '</span>' : '';
        #>
        <div class="{{ css_classes.join(' ') }}" {{{ schema_attrs }}}>
            <# if (settings.show_date === 'yes') { #>
                <span class="meta-item meta-date">
                    <# if (settings.date_icon && settings.date_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.date_icon.value }}"></i>
                        </span>
                    <# } #>
                    <time datetime="2024-01-15T10:00:00+00:00" {{{ (settings.enable_schema === 'yes') ? 'itemprop="datePublished"' : '' }}}>
                        <# if (settings.date_format === 'relative') { #>
                            <?php _e('2 gün önce', 'pratikwp'); ?>
                        <# } else { #>
                            <?php _e('15 Ocak 2024', 'pratikwp'); ?>
                        <# } #>
                    </time>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_author === 'yes') { #>
                <span class="meta-item meta-author">
                    <# if (settings.author_icon && settings.author_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.author_icon.value }}"></i>
                        </span>
                    <# } #>
                    <# if (settings.author_link === 'yes') { #>
                        <a href="#" {{{ (settings.enable_schema === 'yes') ? 'itemprop="author" itemscope itemtype="https://schema.org/Person"' : '' }}}>
                            <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                                <?php _e('Yazar Adı', 'pratikwp'); ?>
                            </span>
                        </a>
                    <# } else { #>
                        <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="author" itemscope itemtype="https://schema.org/Person"' : '' }}}>
                            <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                                <?php _e('Yazar Adı', 'pratikwp'); ?>
                            </span>
                        </span>
                    <# } #>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_categories === 'yes') { #>
                <span class="meta-item meta-categories">
                    <# if (settings.categories_icon && settings.categories_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.categories_icon.value }}"></i>
                        </span>
                    <# } #>
                    <a href="#"><?php _e('Kategori 1', 'pratikwp'); ?></a>, 
                    <a href="#"><?php _e('Kategori 2', 'pratikwp'); ?></a>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_tags === 'yes') { #>
                <span class="meta-item meta-tags">
                    <# if (settings.tags_icon && settings.tags_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.tags_icon.value }}"></i>
                        </span>
                    <# } #>
                    <a href="#"><?php _e('Etiket 1', 'pratikwp'); ?></a>, 
                    <a href="#"><?php _e('Etiket 2', 'pratikwp'); ?></a>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_comments === 'yes') { #>
                <span class="meta-item meta-comments">
                    <# if (settings.comments_icon && settings.comments_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.comments_icon.value }}"></i>
                        </span>
                    <# } #>
                    <a href="#"><?php _e('5 Yorum', 'pratikwp'); ?></a>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_reading_time === 'yes') { #>
                <span class="meta-item meta-reading-time">
                    <# if (settings.reading_time_icon && settings.reading_time_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.reading_time_icon.value }}"></i>
                        </span>
                    <# } #>
                    <?php _e('3 dk okuma', 'pratikwp'); ?>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_views === 'yes') { #>
                <span class="meta-item meta-views">
                    <# if (settings.views_icon && settings.views_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.views_icon.value }}"></i>
                        </span>
                    <# } #>
                    <?php _e('142 görüntülenme', 'pratikwp'); ?>
                </span>{{{ separator }}}
            <# } #>
        </div>
        <?php
    }
}

    /**
     * Render date meta
     */
    private function render_date($settings) {
        $date_format = $settings['date_format'];
        
        if ($date_format === 'relative') {
            $date_text = sprintf(__('%s önce', 'pratikwp'), human_time_diff(get_the_time('U'), current_time('timestamp')));
        } elseif ($date_format === 'custom') {
            $date_text = get_the_date($settings['custom_date_format']);
        } elseif ($date_format === 'default') {
            $date_text = get_the_date();
        } else {
            $date_text = get_the_date($date_format);
        }

        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-date">
            <?php if (!empty($settings['date_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['date_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="datePublished"' : ''; ?>>
                <?php echo esc_html($date_text); ?>
            </time>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render author meta
     */
    private function render_author($settings) {
        $author_text = get_the_author();
        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-author">
            <?php if (!empty($settings['author_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['author_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php if ($settings['author_link'] === 'yes') : ?>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="author" itemscope itemtype="https://schema.org/Person"' : ''; ?>>
                    <span <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="name"' : ''; ?>><?php echo esc_html($author_text); ?></span>
                </a>
            <?php else : ?>
                <span <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="author" itemscope itemtype="https://schema.org/Person"' : ''; ?>>
                    <span <?php echo ($settings['enable_schema'] === 'yes') ? 'itemprop="name"' : ''; ?>><?php echo esc_html($author_text); ?></span>
                </span>
            <?php endif; ?>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render categories meta
     */
    private function render_categories($settings) {
        $categories = get_the_category();
        if (empty($categories)) return;
        
        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-categories">
            <?php if (!empty($settings['categories_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['categories_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php
            $category_links = [];
            foreach ($categories as $category) {
                $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            }
            echo implode(', ', $category_links);
            ?>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render tags meta
     */
    private function render_tags($settings) {
        $tags = get_the_tags();
        if (empty($tags)) return;
        
        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-tags">
            <?php if (!empty($settings['tags_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['tags_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php
            $tag_links = [];
            foreach ($tags as $tag) {
                $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
            }
            echo implode(', ', $tag_links);
            ?>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render comments meta
     */
    private function render_comments($settings) {
        $comments_count = get_comments_number();
        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-comments">
            <?php if (!empty($settings['comments_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['comments_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <a href="<?php echo esc_url(get_comments_link()); ?>">
                <?php
                printf(
                    _n('%s Yorum', '%s Yorum', $comments_count, 'pratikwp'),
                    number_format_i18n($comments_count)
                );
                ?>
            </a>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render reading time meta
     */
    private function render_reading_time($settings) {
        $content = get_post_field('post_content', get_the_ID());
        $word_count = str_word_count(strip_tags($content));
        $reading_time = ceil($word_count / 200); // 200 words per minute average
        
        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-reading-time">
            <?php if (!empty($settings['reading_time_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['reading_time_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php
            printf(
                _n('%d dk okuma', '%d dk okuma', $reading_time, 'pratikwp'),
                $reading_time
            );
            ?>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render views meta
     */
    private function render_views($settings) {
        $views = get_post_meta(get_the_ID(), '_post_views', true);
        $views = $views ? (int) $views : 0;
        
        $separator = ($settings['layout_style'] === 'inline') ? '<span class="meta-separator">' . $settings['separator'] . '</span>' : '';
        ?>
        <span class="meta-item meta-views">
            <?php if (!empty($settings['views_icon']['value'])) : ?>
                <span class="meta-icon">
                    <?php \Elementor\Icons_Manager::render_icon($settings['views_icon'], ['aria-hidden' => 'true']); ?>
                </span>
            <?php endif; ?>
            <?php
            printf(
                _n('%s görüntülenme', '%s görüntülenme', $views, 'pratikwp'),
                number_format_i18n($views)
            );
            ?>
        </span><?php echo $separator; ?>
        <?php
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-post-meta'];
        css_classes.push(settings.layout_style + '-layout');
        
        if (settings.meta_css_classes) {
            css_classes.push(settings.meta_css_classes);
        }

        var schema_attrs = '';
        if (settings.enable_schema === 'yes') {
            schema_attrs = 'itemscope itemtype="https://schema.org/BlogPosting"';
        }

        var separator = (settings.layout_style === 'inline') ? '<span class="meta-separator">' + settings.separator + '</span>' : '';
        #>
        <div class="{{ css_classes.join(' ') }}" {{{ schema_attrs }}}>
            <# if (settings.show_date === 'yes') { #>
                <span class="meta-item meta-date">
                    <# if (settings.date_icon && settings.date_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.date_icon.value }}"></i>
                        </span>
                    <# } #>
                    <time datetime="2024-01-15T10:00:00+00:00" {{{ (settings.enable_schema === 'yes') ? 'itemprop="datePublished"' : '' }}}>
                        <# if (settings.date_format === 'relative') { #>
                            <?php _e('2 gün önce', 'pratikwp'); ?>
                        <# } else { #>
                            <?php _e('15 Ocak 2024', 'pratikwp'); ?>
                        <# } #>
                    </time>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_author === 'yes') { #>
                <span class="meta-item meta-author">
                    <# if (settings.author_icon && settings.author_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.author_icon.value }}"></i>
                        </span>
                    <# } #>
                    <# if (settings.author_link === 'yes') { #>
                        <a href="#" {{{ (settings.enable_schema === 'yes') ? 'itemprop="author" itemscope itemtype="https://schema.org/Person"' : '' }}}>
                            <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                                <?php _e('Yazar Adı', 'pratikwp'); ?>
                            </span>
                        </a>
                    <# } else { #>
                        <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="author" itemscope itemtype="https://schema.org/Person"' : '' }}}>
                            <span {{{ (settings.enable_schema === 'yes') ? 'itemprop="name"' : '' }}}>
                                <?php _e('Yazar Adı', 'pratikwp'); ?>
                            </span>
                        </span>
                    <# } #>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_categories === 'yes') { #>
                <span class="meta-item meta-categories">
                    <# if (settings.categories_icon && settings.categories_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.categories_icon.value }}"></i>
                        </span>
                    <# } #>
                    <a href="#"><?php _e('Kategori 1', 'pratikwp'); ?></a>, 
                    <a href="#"><?php _e('Kategori 2', 'pratikwp'); ?></a>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_tags === 'yes') { #>
                <span class="meta-item meta-tags">
                    <# if (settings.tags_icon && settings.tags_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.tags_icon.value }}"></i>
                        </span>
                    <# } #>
                    <a href="#"><?php _e('Etiket 1', 'pratikwp'); ?></a>, 
                    <a href="#"><?php _e('Etiket 2', 'pratikwp'); ?></a>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_comments === 'yes') { #>
                <span class="meta-item meta-comments">
                    <# if (settings.comments_icon && settings.comments_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.comments_icon.value }}"></i>
                        </span>
                    <# } #>
                    <a href="#"><?php _e('5 Yorum', 'pratikwp'); ?></a>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_reading_time === 'yes') { #>
                <span class="meta-item meta-reading-time">
                    <# if (settings.reading_time_icon && settings.reading_time_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.reading_time_icon.value }}"></i>
                        </span>
                    <# } #>
                    <?php _e('3 dk okuma', 'pratikwp'); ?>
                </span>{{{ separator }}}
            <# } #>
            
            <# if (settings.show_views === 'yes') { #>
                <span class="meta-item meta-views">
                    <# if (settings.views_icon && settings.views_icon.value) { #>
                        <span class="meta-icon">
                            <i class="{{ settings.views_icon.value }}"></i>
                        </span>
                    <# } #>
                    <?php _e('142 görüntülenme', 'pratikwp'); ?>
                </span>{{{ separator }}}
            <# } #>
        </div>
        <?php
    }
}