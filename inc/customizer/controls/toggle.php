<?php
/**
 * Toggle Control for Customizer
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('WP_Customize_Control')) {
    
    class PratikWp_Toggle_Control extends WP_Customize_Control {
        
        public $type = 'pratikwp_toggle';
        
        public $on_text = '';
        public $off_text = '';
        public $size = 'normal';
        public $style = 'default';
        
        public function __construct($manager, $id, $args = array()) {
            parent::__construct($manager, $id, $args);
            
            // Set default values
            $this->on_text = isset($args['on_text']) ? $args['on_text'] : __('Açık', 'pratikwp');
            $this->off_text = isset($args['off_text']) ? $args['off_text'] : __('Kapalı', 'pratikwp');
            $this->size = isset($args['size']) ? $args['size'] : $this->size;
            $this->style = isset($args['style']) ? $args['style'] : $this->style;
        }
        
        public function enqueue() {
            wp_enqueue_style(
                'pratikwp-toggle-control',
                PRATIKWP_ASSETS . '/css/customizer-controls.css',
                array(),
                PRATIKWP_VERSION
            );
            
            wp_enqueue_script(
                'pratikwp-toggle-control',
                PRATIKWP_ASSETS . '/js/customizer-controls.js',
                array('jquery', 'customize-controls'),
                PRATIKWP_VERSION,
                true
            );
        }
        
        public function to_json() {
            parent::to_json();
            
            $this->json['on_text'] = $this->on_text;
            $this->json['off_text'] = $this->off_text;
            $this->json['size'] = $this->size;
            $this->json['style'] = $this->style;
            $this->json['checked'] = (bool) $this->value();
        }
        
        protected function content_template() {
            ?>
            <# var toggleId = 'toggle_' + Math.random().toString(36).substr(2, 9); #>
            <# var isChecked = data.checked ? 'checked' : ''; #>
            <# var sizeClass = data.size ? 'size-' + data.size : 'size-normal'; #>
            <# var styleClass = data.style ? 'style-' + data.style : 'style-default'; #>
            
            <div class="pratikwp-toggle-control {{ sizeClass }} {{ styleClass }}">
                <# if (data.label) { #>
                    <div class="customize-control-title">{{{ data.label }}}</div>
                <# } #>
                
                <# if (data.description) { #>
                    <span class="description customize-control-description">{{{ data.description }}}</span>
                <# } #>
                
                <div class="pratikwp-toggle-wrapper">
                    <div class="pratikwp-toggle-switch">
                        <input 
                            type="checkbox" 
                            id="{{ toggleId }}" 
                            class="pratikwp-toggle-input" 
                            {{ isChecked }}
                            {{{ data.link }}}
                        />
                        <label for="{{ toggleId }}" class="pratikwp-toggle-label">
                            <span class="pratikwp-toggle-slider">
                                <span class="pratikwp-toggle-handle"></span>
                            </span>
                            <span class="pratikwp-toggle-text">
                                <span class="on-text">{{ data.on_text }}</span>
                                <span class="off-text">{{ data.off_text }}</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <style>
            .pratikwp-toggle-control {
                margin-bottom: 12px;
            }
            
            .pratikwp-toggle-wrapper {
                margin-top: 8px;
            }
            
            .pratikwp-toggle-switch {
                display: inline-block;
                position: relative;
            }
            
            .pratikwp-toggle-input {
                position: absolute;
                opacity: 0;
                width: 0;
                height: 0;
            }
            
            .pratikwp-toggle-label {
                display: flex;
                align-items: center;
                gap: 10px;
                cursor: pointer;
                user-select: none;
            }
            
            .pratikwp-toggle-slider {
                position: relative;
                display: inline-block;
                width: 50px;
                height: 24px;
                background-color: #ccc;
                border-radius: 24px;
                transition: background-color 0.3s ease;
            }
            
            .pratikwp-toggle-handle {
                position: absolute;
                top: 2px;
                left: 2px;
                width: 20px;
                height: 20px;
                background-color: white;
                border-radius: 50%;
                transition: transform 0.3s ease;
                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
            }
            
            .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-slider {
                background-color: #0073aa;
            }
            
            .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-handle {
                transform: translateX(26px);
            }
            
            .pratikwp-toggle-text {
                font-size: 13px;
                font-weight: 500;
                color: #666;
            }
            
            .pratikwp-toggle-input:checked + .pratikwp-toggle-label .on-text {
                color: #0073aa;
            }
            
            .pratikwp-toggle-input:not(:checked) + .pratikwp-toggle-label .off-text {
                color: #333;
            }
            
            .pratikwp-toggle-input:checked + .pratikwp-toggle-label .off-text,
            .pratikwp-toggle-input:not(:checked) + .pratikwp-toggle-label .on-text {
                display: none;
            }
            
            /* Size variations */
            .pratikwp-toggle-control.size-small .pratikwp-toggle-slider {
                width: 40px;
                height: 20px;
            }
            
            .pratikwp-toggle-control.size-small .pratikwp-toggle-handle {
                width: 16px;
                height: 16px;
            }
            
            .pratikwp-toggle-control.size-small .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-handle {
                transform: translateX(20px);
            }
            
            .pratikwp-toggle-control.size-large .pratikwp-toggle-slider {
                width: 60px;
                height: 30px;
            }
            
            .pratikwp-toggle-control.size-large .pratikwp-toggle-handle {
                top: 3px;
                left: 3px;
                width: 24px;
                height: 24px;
            }
            
            .pratikwp-toggle-control.size-large .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-handle {
                transform: translateX(30px);
            }
            
            /* Style variations */
            .pratikwp-toggle-control.style-ios .pratikwp-toggle-slider {
                background-color: #e5e5ea;
            }
            
            .pratikwp-toggle-control.style-ios .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-slider {
                background-color: #34c759;
            }
            
            .pratikwp-toggle-control.style-material .pratikwp-toggle-slider {
                background-color: rgba(0,0,0,0.26);
                border-radius: 12px;
            }
            
            .pratikwp-toggle-control.style-material .pratikwp-toggle-handle {
                background-color: #fafafa;
                box-shadow: 0 2px 5px rgba(0,0,0,0.26);
            }
            
            .pratikwp-toggle-control.style-material .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-slider {
                background-color: rgba(33, 150, 243, 0.5);
            }
            
            .pratikwp-toggle-control.style-material .pratikwp-toggle-input:checked + .pratikwp-toggle-label .pratikwp-toggle-handle {
                background-color: #2196f3;
            }
            
            /* Focus states */
            .pratikwp-toggle-input:focus + .pratikwp-toggle-label .pratikwp-toggle-slider {
                box-shadow: 0 0 0 3px rgba(0, 115, 170, 0.2);
            }
            
            /* Disabled state */
            .pratikwp-toggle-input:disabled + .pratikwp-toggle-label {
                opacity: 0.6;
                cursor: not-allowed;
            }
            </style>
            <?php
        }
        
        protected function render_content() {
            $toggle_id = 'toggle_' . uniqid();
            $is_checked = (bool) $this->value();
            $size_class = 'size-' . $this->size;
            $style_class = 'style-' . $this->style;
            ?>
            <div class="pratikwp-toggle-control <?php echo esc_attr($size_class . ' ' . $style_class); ?>">
                <?php if (!empty($this->label)): ?>
                    <div class="customize-control-title"><?php echo esc_html($this->label); ?></div>
                <?php endif; ?>
                
                <?php if (!empty($this->description)): ?>
                    <span class="description customize-control-description"><?php echo $this->description; ?></span>
                <?php endif; ?>
                
                <div class="pratikwp-toggle-wrapper">
                    <div class="pratikwp-toggle-switch">
                        <input 
                            type="checkbox" 
                            id="<?php echo esc_attr($toggle_id); ?>" 
                            class="pratikwp-toggle-input" 
                            <?php checked($is_checked); ?>
                            <?php $this->link(); ?>
                        />
                        <label for="<?php echo esc_attr($toggle_id); ?>" class="pratikwp-toggle-label">
                            <span class="pratikwp-toggle-slider">
                                <span class="pratikwp-toggle-handle"></span>
                            </span>
                            <span class="pratikwp-toggle-text">
                                <span class="on-text"><?php echo esc_html($this->on_text); ?></span>
                                <span class="off-text"><?php echo esc_html($this->off_text); ?></span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}