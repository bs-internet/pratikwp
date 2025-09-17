<?php
/**
 * Range Control for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WP_Customize_Control')) {
    
    class PratikWp_Range_Control extends WP_Customize_Control {
        
        public $type = 'pratikwp_range';
        
        public $min = 0;
        public $max = 100;
        public $step = 1;
        public $suffix = '';
        public $reset = true;
        
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            
            // Set default values
            $this->min = isset($args['min']) ? $args['min'] : $this->min;
            $this->max = isset($args['max']) ? $args['max'] : $this->max;
            $this->step = isset($args['step']) ? $args['step'] : $this->step;
            $this->suffix = isset($args['suffix']) ? $args['suffix'] : $this->suffix;
            $this->reset = isset($args['reset']) ? $args['reset'] : $this->reset;
        }
        
        public function enqueue() {
            wp_enqueue_style(
                'pratikwp-range-control',
                PRATIKWP_ASSETS . '/css/customizer-controls.css',
                array(),
                PRATIKWP_VERSION
            );
            
            wp_enqueue_script(
                'pratikwp-range-control',
                PRATIKWP_ASSETS . '/js/customizer-controls.js',
                array('jquery', 'customize-controls'),
                PRATIKWP_VERSION,
                true
            );
        }
        
        public function to_json() {
            parent::to_json();
            
            $this->json['min'] = $this->min;
            $this->json['max'] = $this->max;
            $this->json['step'] = $this->step;
            $this->json['suffix'] = $this->suffix;
            $this->json['reset'] = $this->reset;
            $this->json['default'] = $this->setting->default;
        }
        
        protected function content_template() {
            ?>
            <# var defaultValue = data.default || data.min; #>
            <# var currentValue = data.value || defaultValue; #>
            <# var resetTitle = '<?php echo esc_attr__('Varsayılana Dön', 'pratikwp'); ?>'; #>
            
            <div class="pratikwp-range-control">
                <div class="customize-control-title-wrapper">
                    <# if (data.label) { #>
                        <span class="customize-control-title">{{{ data.label }}}</span>
                    <# } #>
                    
                    <# if (data.reset) { #>
                        <button type="button" class="pratikwp-range-reset" title="{{ resetTitle }}" data-default="{{ defaultValue }}">
                            <span class="dashicons dashicons-image-rotate"></span>
                        </button>
                    <# } #>
                </div>
                
                <# if (data.description) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
                
                <div class="pratikwp-range-wrapper">
                    <input 
                        type="range" 
                        class="pratikwp-range-slider" 
                        min="{{ data.min }}" 
                        max="{{ data.max }}" 
                        step="{{ data.step }}" 
                        value="{{ currentValue }}" 
                        {{{ data.link }}}
                    />
                    
                    <div class="pratikwp-range-value">
                        <span class="value">{{ currentValue }}</span>
                        <# if (data.suffix) { #>
                            <span class="suffix">{{ data.suffix }}</span>
                        <# } #>
                    </div>
                </div>
                
                <div class="pratikwp-range-limits">
                    <span class="min-value">{{ data.min }}<# if (data.suffix) { #>{{ data.suffix }}<# } #></span>
                    <span class="max-value">{{ data.max }}<# if (data.suffix) { #>{{ data.suffix }}<# } #></span>
                </div>
            </div>
            
            <style>
            .pratikwp-range-control {
                margin-bottom: 12px;
            }
            
            .customize-control-title-wrapper {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
            }
            
            .customize-control-title-wrapper .customize-control-title {
                margin: 0;
                font-weight: 600;
            }
            
            .pratikwp-range-reset {
                background: none;
                border: none;
                padding: 2px;
                cursor: pointer;
                color: #666;
                font-size: 14px;
                opacity: 0.7;
                transition: opacity 0.2s;
            }
            
            .pratikwp-range-reset:hover {
                opacity: 1;
                color: #0073aa;
            }
            
            .pratikwp-range-wrapper {
                display: flex;
                align-items: center;
                gap: 12px;
                margin: 8px 0;
            }
            
            .pratikwp-range-slider {
                flex: 1;
                height: 6px;
                border-radius: 3px;
                background: #ddd;
                outline: none;
                -webkit-appearance: none;
                appearance: none;
            }
            
            .pratikwp-range-slider::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: #0073aa;
                cursor: pointer;
                border: 2px solid #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .pratikwp-range-slider::-moz-range-thumb {
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: #0073aa;
                cursor: pointer;
                border: 2px solid #fff;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .pratikwp-range-value {
                min-width: 60px;
                padding: 4px 8px;
                background: #f5f5f5;
                border-radius: 3px;
                font-size: 12px;
                text-align: center;
                border: 1px solid #ddd;
            }
            
            .pratikwp-range-value .value {
                font-weight: 600;
                color: #333;
            }
            
            .pratikwp-range-value .suffix {
                color: #666;
                margin-left: 2px;
            }
            
            .pratikwp-range-limits {
                display: flex;
                justify-content: space-between;
                font-size: 11px;
                color: #666;
                margin-top: 4px;
            }
            </style>
            
            <script>
            (function($) {
                $(document).ready(function() {
                    // Range slider value update
                    $(document).on('input', '.pratikwp-range-slider', function() {
                        var $this = $(this);
                        var value = $this.val();
                        $this.closest('.pratikwp-range-control').find('.pratikwp-range-value .value').text(value);
                    });
                    
                    // Reset button
                    $(document).on('click', '.pratikwp-range-reset', function() {
                        var $this = $(this);
                        var defaultValue = $this.data('default');
                        var $control = $this.closest('.pratikwp-range-control');
                        var $slider = $control.find('.pratikwp-range-slider');
                        
                        $slider.val(defaultValue).trigger('change');
                        $control.find('.pratikwp-range-value .value').text(defaultValue);
                    });
                });
            })(jQuery);
            </script>
            <?php
        }
        
        protected function render_content() {
            $default_value = $this->setting->default ?: $this->min;
            $current_value = $this->value() ?: $default_value;
            ?>
            <div class="pratikwp-range-control">
                <div class="customize-control-title-wrapper">
                    <?php if (!empty($this->label)): ?>
                        <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($this->reset): ?>
                        <button type="button" class="pratikwp-range-reset" title="<?php esc_attr_e('Varsayılana Dön', 'pratikwp'); ?>" data-default="<?php echo esc_attr($default_value); ?>">
                            <span class="dashicons dashicons-image-rotate"></span>
                        </button>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                
                <div class="pratikwp-range-wrapper">
                    <input 
                        type="range" 
                        class="pratikwp-range-slider" 
                        min="<?php echo esc_attr($this->min); ?>" 
                        max="<?php echo esc_attr($this->max); ?>" 
                        step="<?php echo esc_attr($this->step); ?>" 
                        value="<?php echo esc_attr($current_value); ?>" 
                        <?php $this->link(); ?>
                    />
                    
                    <div class="pratikwp-range-value">
                        <span class="value"><?php echo esc_html($current_value); ?></span>
                        <?php if ($this->suffix): ?>
                            <span class="suffix"><?php echo esc_html($this->suffix); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="pratikwp-range-limits">
                    <span class="min-value"><?php echo esc_html($this->min . $this->suffix); ?></span>
                    <span class="max-value"><?php echo esc_html($this->max . $this->suffix); ?></span>
                </div>
            </div>
            <?php
        }
    }
}