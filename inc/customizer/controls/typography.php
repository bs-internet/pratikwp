<?php
/**
 * Typography Control for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WP_Customize_Control')) {
    
    class PratikWp_Typography_Control extends WP_Customize_Control {
        
        public $type = 'pratikwp_typography';
        
        public $show_font_family = true;
        public $show_font_size = true;
        public $show_font_weight = true;
        public $show_line_height = true;
        public $show_letter_spacing = true;
        public $show_text_transform = true;
        public $font_backup = true;
        public $preview_text = '';
        
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            
            // Set options
            $this->show_font_family = isset($args['show_font_family']) ? $args['show_font_family'] : $this->show_font_family;
            $this->show_font_size = isset($args['show_font_size']) ? $args['show_font_size'] : $this->show_font_size;
            $this->show_font_weight = isset($args['show_font_weight']) ? $args['show_font_weight'] : $this->show_font_weight;
            $this->show_line_height = isset($args['show_line_height']) ? $args['show_line_height'] : $this->show_line_height;
            $this->show_letter_spacing = isset($args['show_letter_spacing']) ? $args['show_letter_spacing'] : $this->show_letter_spacing;
            $this->show_text_transform = isset($args['show_text_transform']) ? $args['show_text_transform'] : $this->show_text_transform;
            $this->font_backup = isset($args['font_backup']) ? $args['font_backup'] : $this->font_backup;
            $this->preview_text = isset($args['preview_text']) ? $args['preview_text'] : __('Örnek Metin Önizleme', 'pratikwp');
        }
        
        public function enqueue() {
            // Google Fonts API
            wp_enqueue_script(
                'google-fonts-api',
                'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js',
                array(),
                '1.6.26',
                true
            );
            
            wp_enqueue_style(
                'pratikwp-typography-control',
                PRATIKWP_ASSETS . '/css/customizer-controls.css',
                array(),
                PRATIKWP_VERSION
            );
            
            wp_enqueue_script(
                'pratikwp-typography-control',
                PRATIKWP_ASSETS . '/js/customizer-controls.js',
                array('jquery', 'customize-controls'),
                PRATIKWP_VERSION,
                true
            );
        }
        
        public function to_json() {
            parent::to_json();
            
            $this->json['show_font_family'] = $this->show_font_family;
            $this->json['show_font_size'] = $this->show_font_size;
            $this->json['show_font_weight'] = $this->show_font_weight;
            $this->json['show_line_height'] = $this->show_line_height;
            $this->json['show_letter_spacing'] = $this->show_letter_spacing;
            $this->json['show_text_transform'] = $this->show_text_transform;
            $this->json['font_backup'] = $this->font_backup;
            $this->json['preview_text'] = $this->preview_text;
            $this->json['google_fonts'] = $this->get_google_fonts();
            $this->json['system_fonts'] = $this->get_system_fonts();
            $this->json['font_weights'] = $this->get_font_weights();
            $this->json['text_transforms'] = $this->get_text_transforms();
            
            // Parse current value
            $value = $this->value();
            if (is_string($value)) {
                $value = json_decode($value, true);
            }
            $this->json['typography_value'] = wp_parse_args($value, $this->get_default_values());
        }
        
        private function get_google_fonts() {
            return array(
                'Open Sans' => 'Open Sans',
                'Roboto' => 'Roboto',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Oswald' => 'Oswald',
                'Source Sans Pro' => 'Source Sans Pro',
                'Raleway' => 'Raleway',
                'PT Sans' => 'PT Sans',
                'Ubuntu' => 'Ubuntu',
                'Merriweather' => 'Merriweather',
                'Playfair Display' => 'Playfair Display',
                'Nunito' => 'Nunito',
                'Poppins' => 'Poppins',
                'Work Sans' => 'Work Sans',
                'Fira Sans' => 'Fira Sans',
                'Inter' => 'Inter',
                'Roboto Slab' => 'Roboto Slab',
                'Crimson Text' => 'Crimson Text'
            );
        }
        
        private function get_system_fonts() {
            return array(
                'Arial' => 'Arial, sans-serif',
                'Helvetica' => 'Helvetica, sans-serif',
                'Times New Roman' => '"Times New Roman", serif',
                'Georgia' => 'Georgia, serif',
                'Verdana' => 'Verdana, sans-serif',
                'Tahoma' => 'Tahoma, sans-serif',
                'Trebuchet MS' => '"Trebuchet MS", sans-serif',
                'Courier New' => '"Courier New", monospace',
                'Impact' => 'Impact, sans-serif',
                'Comic Sans MS' => '"Comic Sans MS", cursive'
            );
        }
        
        private function get_font_weights() {
            return array(
                '100' => __('İnce (100)', 'pratikwp'),
                '200' => __('Çok İnce (200)', 'pratikwp'),
                '300' => __('Açık (300)', 'pratikwp'),
                '400' => __('Normal (400)', 'pratikwp'),
                '500' => __('Orta (500)', 'pratikwp'),
                '600' => __('Yarı Kalın (600)', 'pratikwp'),
                '700' => __('Kalın (700)', 'pratikwp'),
                '800' => __('Çok Kalın (800)', 'pratikwp'),
                '900' => __('En Kalın (900)', 'pratikwp')
            );
        }
        
        private function get_text_transforms() {
            return array(
                'none' => __('Normal', 'pratikwp'),
                'uppercase' => __('BÜYÜK HARF', 'pratikwp'),
                'lowercase' => __('küçük harf', 'pratikwp'),
                'capitalize' => __('İlk Harfler Büyük', 'pratikwp')
            );
        }
        
        private function get_default_values() {
            return array(
                'font_family' => '',
                'font_size' => 16,
                'font_weight' => '400',
                'line_height' => 1.5,
                'letter_spacing' => 0,
                'text_transform' => 'none'
            );
        }
        
        protected function content_template() {
            ?>
            <# var controlId = 'typography_' + Math.random().toString(36).substr(2, 9); #>
            <# var typography = data.typography_value || {}; #>
            
            <div class="pratikwp-typography-control">
                <# if (data.label) { #>
                    <div class="customize-control-title">{{{ data.label }}}</div>
                <# } #>
                
                <# if (data.description) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
                
                <div class="pratikwp-typography-fields">
                    
                    <# if (data.show_font_family) { #>
                        <div class="typography-field">
                            <label class="field-label"><?php esc_html_e('Font Ailesi', 'pratikwp'); ?></label>
                            <select class="typography-font-family" data-field="font_family">
                                <option value=""><?php esc_html_e('Varsayılan', 'pratikwp'); ?></option>
                                <optgroup label="<?php esc_attr_e('Google Fonts', 'pratikwp'); ?>">
                                    <# _.each(data.google_fonts, function(label, value) { #>
                                        <option value="{{ value }}" <# if (typography.font_family === value) { #>selected<# } #>>{{ label }}</option>
                                    <# }); #>
                                </optgroup>
                                <optgroup label="<?php esc_attr_e('Sistem Fontları', 'pratikwp'); ?>">
                                    <# _.each(data.system_fonts, function(value, label) { #>
                                        <option value="{{ value }}" <# if (typography.font_family === value) { #>selected<# } #>>{{ label }}</option>
                                    <# }); #>
                                </optgroup>
                            </select>
                        </div>
                    <# } #>
                    
                    <# if (data.show_font_size) { #>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Boyut', 'pratikwp'); ?></label>
                            <div class="field-group">
                                <input type="number" class="typography-font-size" data-field="font_size" value="{{ typography.font_size || 16 }}" min="8" max="100" />
                                <span class="field-unit">px</span>
                            </div>
                        </div>
                    <# } #>
                    
                    <# if (data.show_font_weight) { #>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Kalınlık', 'pratikwp'); ?></label>
                            <select class="typography-font-weight" data-field="font_weight">
                                <# _.each(data.font_weights, function(label, value) { #>
                                    <option value="{{ value }}" <# if (typography.font_weight === value) { #>selected<# } #>>{{ label }}</option>
                                <# }); #>
                            </select>
                        </div>
                    <# } #>
                    
                    <# if (data.show_line_height) { #>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Satır Yüksekliği', 'pratikwp'); ?></label>
                            <div class="field-group">
                                <input type="number" class="typography-line-height" data-field="line_height" value="{{ typography.line_height || 1.5 }}" min="0.5" max="3" step="0.1" />
                                <span class="field-unit">em</span>
                            </div>
                        </div>
                    <# } #>
                    
                    <# if (data.show_letter_spacing) { #>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Harf Aralığı', 'pratikwp'); ?></label>
                            <div class="field-group">
                                <input type="number" class="typography-letter-spacing" data-field="letter_spacing" value="{{ typography.letter_spacing || 0 }}" min="-5" max="10" step="0.1" />
                                <span class="field-unit">px</span>
                            </div>
                        </div>
                    <# } #>
                    
                    <# if (data.show_text_transform) { #>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Metin Dönüşümü', 'pratikwp'); ?></label>
                            <select class="typography-text-transform" data-field="text_transform">
                                <# _.each(data.text_transforms, function(label, value) { #>
                                    <option value="{{ value }}" <# if (typography.text_transform === value) { #>selected<# } #>>{{ label }}</option>
                                <# }); #>
                            </select>
                        </div>
                    <# } #>
                    
                </div>
                
                <div class="typography-preview">
                    <div class="preview-label"><?php esc_html_e('Önizleme:', 'pratikwp'); ?></div>
                    <div class="preview-text" id="{{ controlId }}_preview">{{ data.preview_text }}</div>
                </div>
                
                <input type="hidden" class="typography-hidden-value" {{{ data.link }}} value="{{ JSON.stringify(typography) }}" />
            </div>
            
            <style>
            .pratikwp-typography-control {
                margin-bottom: 12px;
            }
            
            .pratikwp-typography-fields {
                border: 1px solid #ddd;
                border-radius: 4px;
                padding: 12px;
                margin: 8px 0;
                background: #f9f9f9;
            }
            
            .typography-field {
                margin-bottom: 12px;
            }
            
            .typography-field:last-child {
                margin-bottom: 0;
            }
            
            .typography-field-inline {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .typography-field-inline .field-label {
                flex: 0 0 40%;
                margin: 0;
                font-size: 12px;
                font-weight: 500;
                color: #555;
            }
            
            .typography-field-inline select,
            .typography-field-inline .field-group {
                flex: 1;
                max-width: 120px;
            }
            
            .field-label {
                display: block;
                margin-bottom: 4px;
                font-size: 12px;
                font-weight: 500;
                color: #555;
            }
            
            .typography-field select,
            .typography-field input {
                width: 100%;
                padding: 4px 8px;
                border: 1px solid #ccc;
                border-radius: 3px;
                font-size: 12px;
            }
            
            .field-group {
                display: flex;
                align-items: center;
                gap: 4px;
            }
            
            .field-group input {
                flex: 1;
                width: auto;
            }
            
            .field-unit {
                font-size: 11px;
                color: #666;
                font-weight: 500;
            }
            
            .typography-preview {
                margin-top: 12px;
                padding: 12px;
                border: 1px solid #ddd;
                border-radius: 4px;
                background: white;
            }
            
            .preview-label {
                font-size: 11px;
                color: #666;
                margin-bottom: 8px;
                font-weight: 500;
            }
            
            .preview-text {
                font-size: 16px;
                line-height: 1.5;
                color: #333;
                word-break: break-word;
            }
            
            .typography-hidden-value {
                display: none;
            }
            </style>
            
            <script>
            (function($) {
                $(document).ready(function() {
                    // Typography control change handler
                    $(document).on('change input', '.pratikwp-typography-control select, .pratikwp-typography-control input[type="number"]', function() {
                        var $control = $(this).closest('.pratikwp-typography-control');
                        var $hiddenInput = $control.find('.typography-hidden-value');
                        var $preview = $control.find('.preview-text');
                        
                        var typography = {};
                        
                        // Collect all values
                        $control.find('[data-field]').each(function() {
                            var field = $(this).data('field');
                            var value = $(this).val();
                            
                            if (field === 'font_size' || field === 'line_height' || field === 'letter_spacing') {
                                value = parseFloat(value) || 0;
                            }
                            
                            typography[field] = value;
                        });
                        
                        // Update hidden input
                        $hiddenInput.val(JSON.stringify(typography)).trigger('change');
                        
                        // Update preview
                        updateTypographyPreview($preview, typography);
                        
                        // Load Google Font if needed
                        if (typography.font_family && typography.font_family.indexOf(',') === -1) {
                            loadGoogleFont(typography.font_family);
                        }
                    });
                    
                    function updateTypographyPreview($preview, typography) {
                        var styles = {};
                        
                        if (typography.font_family) {
                            styles['font-family'] = typography.font_family;
                        }
                        if (typography.font_size) {
                            styles['font-size'] = typography.font_size + 'px';
                        }
                        if (typography.font_weight) {
                            styles['font-weight'] = typography.font_weight;
                        }
                        if (typography.line_height) {
                            styles['line-height'] = typography.line_height;
                        }
                        if (typography.letter_spacing) {
                            styles['letter-spacing'] = typography.letter_spacing + 'px';
                        }
                        if (typography.text_transform) {
                            styles['text-transform'] = typography.text_transform;
                        }
                        
                        $preview.css(styles);
                    }
                    
                    function loadGoogleFont(fontFamily) {
                        if (typeof WebFont !== 'undefined') {
                            WebFont.load({
                                google: {
                                    families: [fontFamily + ':100,200,300,400,500,600,700,800,900']
                                }
                            });
                        }
                    }
                });
            })(jQuery);
            </script>
            <?php
        }
        
        protected function render_content() {
            $control_id = 'typography_' . uniqid();
            $value = $this->value();
            
            if (is_string($value)) {
                $value = json_decode($value, true);
            }
            
            $typography = wp_parse_args($value, $this->get_default_values());
            ?>
            <div class="pratikwp-typography-control">
                <?php if (!empty($this->label)): ?>
                    <div class="customize-control-title"><?php echo esc_html($this->label); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                
                <div class="pratikwp-typography-fields">
                    
                    <?php if ($this->show_font_family): ?>
                        <div class="typography-field">
                            <label class="field-label"><?php esc_html_e('Font Ailesi', 'pratikwp'); ?></label>
                            <select class="typography-font-family" data-field="font_family">
                                <option value=""><?php esc_html_e('Varsayılan', 'pratikwp'); ?></option>
                                <optgroup label="<?php esc_attr_e('Google Fonts', 'pratikwp'); ?>">
                                    <?php foreach ($this->get_google_fonts() as $font => $label): ?>
                                        <option value="<?php echo esc_attr($font); ?>" <?php selected($typography['font_family'], $font); ?>><?php echo esc_html($label); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                                <optgroup label="<?php esc_attr_e('Sistem Fontları', 'pratikwp'); ?>">
                                    <?php foreach ($this->get_system_fonts() as $label => $font): ?>
                                        <option value="<?php echo esc_attr($font); ?>" <?php selected($typography['font_family'], $font); ?>><?php echo esc_html($label); ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->show_font_size): ?>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Boyut', 'pratikwp'); ?></label>
                            <div class="field-group">
                                <input type="number" class="typography-font-size" data-field="font_size" value="<?php echo esc_attr($typography['font_size']); ?>" min="8" max="100" />
                                <span class="field-unit">px</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->show_font_weight): ?>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Kalınlık', 'pratikwp'); ?></label>
                            <select class="typography-font-weight" data-field="font_weight">
                                <?php foreach ($this->get_font_weights() as $weight => $label): ?>
                                    <option value="<?php echo esc_attr($weight); ?>" <?php selected($typography['font_weight'], $weight); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->show_line_height): ?>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Satır Yüksekliği', 'pratikwp'); ?></label>
                            <div class="field-group">
                                <input type="number" class="typography-line-height" data-field="line_height" value="<?php echo esc_attr($typography['line_height']); ?>" min="0.5" max="3" step="0.1" />
                                <span class="field-unit">em</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->show_letter_spacing): ?>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Harf Aralığı', 'pratikwp'); ?></label>
                            <div class="field-group">
                                <input type="number" class="typography-letter-spacing" data-field="letter_spacing" value="<?php echo esc_attr($typography['letter_spacing']); ?>" min="-5" max="10" step="0.1" />
                                <span class="field-unit">px</span>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->show_text_transform): ?>
                        <div class="typography-field typography-field-inline">
                            <label class="field-label"><?php esc_html_e('Metin Dönüşümü', 'pratikwp'); ?></label>
                            <select class="typography-text-transform" data-field="text_transform">
                                <?php foreach ($this->get_text_transforms() as $transform => $label): ?>
                                    <option value="<?php echo esc_attr($transform); ?>" <?php selected($typography['text_transform'], $transform); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <div class="typography-preview">
                    <div class="preview-label"><?php esc_html_e('Önizleme:', 'pratikwp'); ?></div>
                    <div class="preview-text" id="<?php echo esc_attr($control_id); ?>_preview"><?php echo esc_html($this->preview_text); ?></div>
                </div>
                
                <input type="hidden" class="typography-hidden-value" <?php $this->link(); ?> value="<?php echo esc_attr(json_encode($typography)); ?>" />
            </div>
            <?php
        }
    }
}