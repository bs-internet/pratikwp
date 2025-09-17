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
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Typography;

class PratikWp_Company_Info_Widget extends Widget_Base {

    /**
     * Get widget name
     */
    public function get_name() {
        return 'pratikwp-company-info';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Firma Bilgileri', 'pratikwp');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-info-box';
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
        return ['company', 'info', 'contact', 'business', 'address', 'phone', 'email'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'company_content_section',
            [
                'label' => __('Firma Bilgileri', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Company Fields to Show
        $this->add_control(
            'show_fields',
            [
                'label' => __('Gösterilecek Bilgiler', 'pratikwp'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'firma_adi' => __('Firma Adı', 'pratikwp'),
                    'firma_slogan' => __('Slogan', 'pratikwp'),
                    'firma_adres' => __('Adres', 'pratikwp'),
                    'firma_il' => __('İl', 'pratikwp'),
                    'firma_ilce' => __('İlçe', 'pratikwp'),
                    'firma_postakodu' => __('Posta Kodu', 'pratikwp'),
                    'firma_tel1' => __('Telefon 1', 'pratikwp'),
                    'firma_tel2' => __('Telefon 2', 'pratikwp'),
                    'firma_gsm1' => __('GSM 1', 'pratikwp'),
                    'firma_gsm2' => __('GSM 2', 'pratikwp'),
                    'firma_email1' => __('E-mail 1', 'pratikwp'),
                    'firma_email2' => __('E-mail 2', 'pratikwp'),
                    'firma_website' => __('Website', 'pratikwp'),
                    'firma_fax' => __('Fax', 'pratikwp'),
                    'firma_vergi_no' => __('Vergi No', 'pratikwp'),
                    'firma_ticaret_no' => __('Ticaret Sicil No', 'pratikwp'),
                ],
                'default' => ['firma_adi', 'firma_adres', 'firma_tel1', 'firma_email1'],
            ]
        );

        // Layout Style
        $this->add_control(
            'layout_style',
            [
                'label' => __('Düzen Stili', 'pratikwp'),
                'type' => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'vertical' => __('Dikey Liste', 'pratikwp'),
                    'horizontal' => __('Yatay', 'pratikwp'),
                    'grid' => __('Grid', 'pratikwp'),
                    'card' => __('Kart Görünümü', 'pratikwp'),
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
                    '{{WRAPPER}} .pratikwp-company-info.grid-layout' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );

        // Show Labels
        $this->add_control(
            'show_labels',
            [
                'label' => __('Etiketleri Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Show Icons
        $this->add_control(
            'show_icons',
            [
                'label' => __('İkonları Göster', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Custom Icons
        $this->add_control(
            'custom_icons_heading',
            [
                'label' => __('Özel İkonlar', 'pratikwp'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'show_icons' => 'yes',
                ],
            ]
        );

        // Icon for each field
        $field_icons = [
            'firma_adi' => ['label' => __('Firma Adı İkonu', 'pratikwp'), 'default' => 'fas fa-building'],
            'firma_adres' => ['label' => __('Adres İkonu', 'pratikwp'), 'default' => 'fas fa-map-marker-alt'],
            'firma_tel1' => ['label' => __('Telefon İkonu', 'pratikwp'), 'default' => 'fas fa-phone'],
            'firma_gsm1' => ['label' => __('GSM İkonu', 'pratikwp'), 'default' => 'fas fa-mobile-alt'],
            'firma_email1' => ['label' => __('E-mail İkonu', 'pratikwp'), 'default' => 'fas fa-envelope'],
            'firma_website' => ['label' => __('Website İkonu', 'pratikwp'), 'default' => 'fas fa-globe'],
            'firma_fax' => ['label' => __('Fax İkonu', 'pratikwp'), 'default' => 'fas fa-fax'],
        ];

        foreach ($field_icons as $field => $config) {
            $this->add_control(
                $field . '_icon',
                [
                    'label' => $config['label'],
                    'type' => Controls_Manager::ICONS,
                    'default' => [
                        'value' => $config['default'],
                        'library' => 'fa-solid',
                    ],
                    'condition' => [
                        'show_icons' => 'yes',
                    ],
                ]
            );
        }

        $this->end_controls_section();

        // Links Section
        $this->start_controls_section(
            'company_links_section',
            [
                'label' => __('Link Ayarları', 'pratikwp'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Make Phone Numbers Clickable
        $this->add_control(
            'clickable_phones',
            [
                'label' => __('Telefon Numaraları Tıklanabilir', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Make Email Addresses Clickable
        $this->add_control(
            'clickable_emails',
            [
                'label' => __('E-mail Adresleri Tıklanabilir', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Make Website Clickable
        $this->add_control(
            'clickable_website',
            [
                'label' => __'Website Tıklanabilir', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );

        // Address Link to Maps
        $this->add_control(
            'address_maps_link',
            [
                'label' => __('Adresi Google Maps\'e Bağla', 'pratikwp'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'company_style_section',
            [
                'label' => __('Genel Stil', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Container Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'selector' => '{{WRAPPER}} .pratikwp-company-info',
            ]
        );

        // Container Padding
        $this->add_responsive_control(
            'container_padding',
            [
                'label' => __('Container Padding', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-company-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Container Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'selector' => '{{WRAPPER}} .pratikwp-company-info',
            ]
        );

        // Container Border Radius
        $this->add_responsive_control(
            'container_border_radius',
            [
                'label' => __('Border Radius', 'pratikwp'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-company-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .pratikwp-company-info.vertical-layout .info-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pratikwp-company-info.horizontal-layout .info-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pratikwp-company-info.grid-layout' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Labels Style Section
        $this->start_controls_section(
            'labels_style_section',
            [
                'label' => __('Etiket Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_labels' => 'yes',
                ],
            ]
        );

        // Label Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .info-label',
            ]
        );

        // Label Color
        $this->add_control(
            'label_color',
            [
                'label' => __('Etiket Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => [
                    '{{WRAPPER}} .info-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Label Spacing
        $this->add_responsive_control(
            'label_spacing',
            [
                'label' => __('Etiket Boşluğu', 'pratikwp'),
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
                    '{{WRAPPER}} .info-label' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Values Style Section
        $this->start_controls_section(
            'values_style_section',
            [
                'label' => __('Değer Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Value Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'value_typography',
                'selector' => '{{WRAPPER}} .info-value, {{WRAPPER}} .info-value a',
            ]
        );

        // Value Color
        $this->add_control(
            'value_color',
            [
                'label' => __('Değer Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#666666',
                'selectors' => [
                    '{{WRAPPER}} .info-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Color
        $this->add_control(
            'link_color',
            [
                'label' => __('Link Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007cba',
                'selectors' => [
                    '{{WRAPPER}} .info-value a' => 'color: {{VALUE}};',
                ],
            ]
        );

        // Link Hover Color
        $this->add_control(
            'link_hover_color',
            [
                'label' => __('Link Hover Rengi', 'pratikwp'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .info-value a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Icons Style Section
        $this->start_controls_section(
            'icons_style_section',
            [
                'label' => __('İkon Stili', 'pratikwp'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_icons' => 'yes',
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
                        'max' => 40,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 16,
                ],
                'selectors' => [
                    '{{WRAPPER}} .info-icon' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .info-icon' => 'color: {{VALUE}};',
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
                        'max' => 30,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .info-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Advanced Section
        $this->start_controls_section(
            'company_advanced_section',
            [
                'label' => __('Gelişmiş', 'pratikwp'),
                'tab' => Controls_Manager::TAB_ADVANCED,
            ]
        );

        // Custom CSS Classes
        $this->add_control(
            'company_css_classes',
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
                'label' => __('Schema.org Organization Markup\'ını Etkinleştir', 'pratikwp'),
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
        
        if (empty($settings['show_fields'])) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="elementor-alert elementor-alert-warning">' . __('Lütfen gösterilecek alanları seçin.', 'pratikwp') . '</div>';
            }
            return;
        }

        $css_classes = ['pratikwp-company-info'];
        $css_classes[] = $settings['layout_style'] . '-layout';
        
        if (!empty($settings['company_css_classes'])) {
            $css_classes[] = $settings['company_css_classes'];
        }

        $this->add_render_attribute('wrapper', 'class', $css_classes);
        
        // Schema markup
        $schema_attrs = '';
        if ($settings['enable_schema'] === 'yes') {
            $schema_attrs = 'itemscope itemtype="https://schema.org/Organization"';
        }

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?> <?php echo $schema_attrs; ?>>
            <?php foreach ($settings['show_fields'] as $field) : ?>
                <?php
                $value = get_option($field, '');
                if (empty($value)) continue;
                
                $field_data = $this->get_field_data($field);
                ?>
                <div class="info-item info-item-<?php echo esc_attr($field); ?>">
                    <?php if ($settings['show_icons'] === 'yes' && !empty($settings[$field . '_icon']['value'])) : ?>
                        <span class="info-icon">
                            <?php \Elementor\Icons_Manager::render_icon($settings[$field . '_icon'], ['aria-hidden' => 'true']); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($settings['show_labels'] === 'yes') : ?>
                        <span class="info-label"><?php echo esc_html($field_data['label']); ?>:</span>
                    <?php endif; ?>
                    
                    <span class="info-value" <?php echo ($settings['enable_schema'] === 'yes' && !empty($field_data['schema'])) ? 'itemprop="' . $field_data['schema'] . '"' : ''; ?>>
                        <?php echo $this->format_field_value($field, $value, $settings); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
     * Get field data (label, schema, etc.)
     */
    protected function get_field_data($field) {
        $field_labels = [
            'firma_adi' => ['label' => __('Firma Adı', 'pratikwp'), 'schema' => 'name'],
            'firma_slogan' => ['label' => __('Slogan', 'pratikwp'), 'schema' => 'slogan'],
            'firma_adres' => ['label' => __('Adres', 'pratikwp'), 'schema' => 'address'],
            'firma_il' => ['label' => __('İl', 'pratikwp'), 'schema' => ''],
            'firma_ilce' => ['label' => __('İlçe', 'pratikwp'), 'schema' => ''],
            'firma_postakodu' => ['label' => __('Posta Kodu', 'pratikwp'), 'schema' => ''],
            'firma_tel1' => ['label' => __('Telefon', 'pratikwp'), 'schema' => 'telephone'],
            'firma_tel2' => ['label' => __('Telefon 2', 'pratikwp'), 'schema' => 'telephone'],
            'firma_gsm1' => ['label' => __('GSM', 'pratikwp'), 'schema' => 'telephone'],
            'firma_gsm2' => ['label' => __('GSM 2', 'pratikwp'), 'schema' => 'telephone'],
            'firma_email1' => ['label' => __('E-mail', 'pratikwp'), 'schema' => 'email'],
            'firma_email2' => ['label' => __('E-mail 2', 'pratikwp'), 'schema' => 'email'],
            'firma_website' => ['label' => __('Website', 'pratikwp'), 'schema' => 'url'],
            'firma_fax' => ['label' => __('Fax', 'pratikwp'), 'schema' => 'faxNumber'],
            'firma_vergi_no' => ['label' => __('Vergi No', 'pratikwp'), 'schema' => 'vatID'],
            'firma_ticaret_no' => ['label' => __('Ticaret Sicil No', 'pratikwp'), 'schema' => ''],
        ];
        
        return $field_labels[$field] ?? ['label' => $field, 'schema' => ''];
    }

    /**
     * Format field value based on type and settings
     */
    protected function format_field_value($field, $value, $settings) {
        $phone_fields = ['firma_tel1', 'firma_tel2', 'firma_gsm1', 'firma_gsm2', 'firma_fax'];
        $email_fields = ['firma_email1', 'firma_email2'];
        
        if (in_array($field, $phone_fields) && $settings['clickable_phones'] === 'yes') {
            $clean_phone = preg_replace('/[^0-9+]/', '', $value);
            return '<a href="tel:' . esc_attr($clean_phone) . '">' . esc_html($value) . '</a>';
        }
        
        if (in_array($field, $email_fields) && $settings['clickable_emails'] === 'yes') {
            return '<a href="mailto:' . esc_attr($value) . '">' . esc_html($value) . '</a>';
        }
        
        if ($field === 'firma_website' && $settings['clickable_website'] === 'yes') {
            $url = esc_url($value);
            if (!empty($url)) {
                return '<a href="' . $url . '" target="_blank" rel="noopener">' . esc_html($value) . '</a>';
            }
        }
        
        if ($field === 'firma_adres' && $settings['address_maps_link'] === 'yes') {
            $maps_url = 'https://www.google.com/maps/search/' . urlencode($value);
            return '<a href="' . esc_url($maps_url) . '" target="_blank" rel="noopener">' . esc_html($value) . '</a>';
        }
        
        return esc_html($value);
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template() {
        ?>
        <#
        var css_classes = ['pratikwp-company-info'];
        css_classes.push(settings.layout_style + '-layout');
        
        if (settings.company_css_classes) {
            css_classes.push(settings.company_css_classes);
        }

        var schema_attrs = '';
        if (settings.enable_schema === 'yes') {
            schema_attrs = 'itemscope itemtype="https://schema.org/Organization"';
        }

        var field_labels = {
            'firma_adi': '<?php _e('Firma Adı', 'pratikwp'); ?>',
            'firma_slogan': '<?php _e('Slogan', 'pratikwp'); ?>',
            'firma_adres': '<?php _e('Adres', 'pratikwp'); ?>',
            'firma_il': '<?php _e('İl', 'pratikwp'); ?>',
            'firma_ilce': '<?php _e('İlçe', 'pratikwp'); ?>',
            'firma_tel1': '<?php _e('Telefon', 'pratikwp'); ?>',
            'firma_gsm1': '<?php _e('GSM', 'pratikwp'); ?>',
            'firma_email1': '<?php _e('E-mail', 'pratikwp'); ?>',
            'firma_website': '<?php _e('Website', 'pratikwp'); ?>',
            'firma_fax': '<?php _e('Fax', 'pratikwp'); ?>',
        };

        var sample_values = {
            'firma_adi': 'BS Internet',
            'firma_slogan': 'Web Çözümleri',
            'firma_adres': 'Örnek Mahalle, Örnek Sokak No:1',
            'firma_il': 'İstanbul',
            'firma_ilce': 'Kadıköy',
            'firma_tel1': '+90 216 123 45 67',
            'firma_gsm1': '+90 555 123 45 67',
            'firma_email1': 'info@pratikwp.com',
            'firma_website': 'www.pratikwp.com',
            'firma_fax': '+90 216 123 45 68',
        };
        #>
        <div class="{{ css_classes.join(' ') }}" {{{ schema_attrs }}}>
            <# if (settings.show_fields && settings.show_fields.length > 0) { #>
                <# _.each(settings.show_fields, function(field) { #>
                    <# if (field_labels[field] && sample_values[field]) { #>
                        <div class="info-item info-item-{{ field }}">
                            <# if (settings.show_icons === 'yes' && settings[field + '_icon'] && settings[field + '_icon'].value) { #>
                                <span class="info-icon">
                                    <i class="{{ settings[field + '_icon'].value }}" aria-hidden="true"></i>
                                </span>
                            <# } #>
                            
                            <# if (settings.show_labels === 'yes') { #>
                                <span class="info-label">{{ field_labels[field] }}:</span>
                            <# } #>
                            
                            <span class="info-value">
                                {{{ sample_values[field] }}}
                            </span>
                        </div>
                    <# } #>
                <# }); #>
            <# } else { #>
                <div class="elementor-alert elementor-alert-warning">
                    <?php _e('Lütfen gösterilecek alanları seçin.', 'pratikwp'); ?>
                </div>
            <# } #>
        </div>
        <?php
    }
}