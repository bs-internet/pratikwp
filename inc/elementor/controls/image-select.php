<?php
/**
 * Elementor Image Select Control
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor image select control.
 */
class PratikWp_Image_Select_Control extends \Elementor\Base_Data_Control {

    /**
     * Get image select control type.
     */
    public function get_type() {
        return 'pratikwp-image-select';
    }

    /**
     * Enqueue image select control scripts and styles.
     */
    public function enqueue() {
        // Control styles
        wp_enqueue_style(
            'pratikwp-image-select-control',
            get_template_directory_uri() . '/assets/css/elementor-controls.css',
            [],
            PRATIKWP_VERSION
        );

        // Control script
        wp_enqueue_script(
            'pratikwp-image-select-control',
            get_template_directory_uri() . '/assets/js/elementor-controls.js',
            ['jquery'],
            PRATIKWP_VERSION,
            true
        );
    }

    /**
     * Get default settings.
     */
    protected function get_default_settings() {
        return [
            'options' => [],
            'columns' => 3,
            'toggle' => false,
            'multiple' => false,
        ];
    }

    /**
     * Render image select control output in the editor.
     */
    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
                <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <div class="pratikwp-image-select-wrapper" data-columns="{{ data.columns }}">
                    <# _.each( data.options, function( option_data, option_value ) { #>
                        <# 
                        var selected = '';
                        var checked = '';
                        
                        if ( data.multiple ) {
                            if ( data.controlValue && data.controlValue.includes( option_value ) ) {
                                selected = 'selected';
                                checked = 'checked';
                            }
                        } else {
                            if ( option_value === data.controlValue ) {
                                selected = 'selected';
                                checked = 'checked';
                            }
                        }
                        #>
                        
                        <div class="pratikwp-image-select-item {{ selected }}" data-value="{{ option_value }}">
                            <# if ( data.multiple ) { #>
                                <input type="checkbox" 
                                       id="{{ data._cid }}-{{ option_value }}" 
                                       name="{{ data.name }}[]" 
                                       value="{{ option_value }}" 
                                       {{ checked }} 
                                       style="display: none;" />
                            <# } else { #>
                                <input type="radio" 
                                       id="{{ data._cid }}-{{ option_value }}" 
                                       name="{{ data.name }}" 
                                       value="{{ option_value }}" 
                                       {{ checked }} 
                                       style="display: none;" />
                            <# } #>
                            
                            <label for="{{ data._cid }}-{{ option_value }}" class="pratikwp-image-select-label">
                                <# if ( option_data.image ) { #>
                                    <img src="{{ option_data.image }}" alt="{{ option_data.title }}" />
                                <# } #>
                                
                                <# if ( option_data.icon ) { #>
                                    <i class="{{ option_data.icon }}"></i>
                                <# } #>
                                
                                <# if ( option_data.title ) { #>
                                    <span class="pratikwp-image-select-title">{{ option_data.title }}</span>
                                <# } #>
                                
                                <# if ( option_data.description ) { #>
                                    <span class="pratikwp-image-select-description">{{ option_data.description }}</span>
                                <# } #>
                            </label>
                        </div>
                    <# } ); #>
                </div>
            </div>
            
            <# if ( data.description ) { #>
                <div class="elementor-control-field-description">{{{ data.description }}}</div>
            <# } #>
        </div>
        
        <style>
        .pratikwp-image-select-wrapper {
            display: grid;
            gap: 10px;
            grid-template-columns: repeat({{ data.columns }}, 1fr);
        }
        
        .pratikwp-image-select-item {
            position: relative;
            cursor: pointer;
            border: 2px solid #e6e9ec;
            border-radius: 5px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .pratikwp-image-select-item:hover {
            border-color: #a4afb7;
            transform: translateY(-2px);
        }
        
        .pratikwp-image-select-item.selected {
            border-color: #007cba;
            box-shadow: 0 0 0 1px #007cba;
        }
        
        .pratikwp-image-select-label {
            display: block;
            padding: 15px 10px;
            text-align: center;
            cursor: pointer;
            position: relative;
        }
        
        .pratikwp-image-select-label img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 8px;
            border-radius: 3px;
        }
        
        .pratikwp-image-select-label i {
            font-size: 24px;
            color: #6c757d;
            margin-bottom: 8px;
            display: block;
        }
        
        .pratikwp-image-select-item.selected .pratikwp-image-select-label i {
            color: #007cba;
        }
        
        .pratikwp-image-select-title {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #495057;
            margin-bottom: 4px;
        }
        
        .pratikwp-image-select-description {
            display: block;
            font-size: 11px;
            color: #6c757d;
            line-height: 1.4;
        }
        
        .pratikwp-image-select-item.selected .pratikwp-image-select-title {
            color: #007cba;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .pratikwp-image-select-wrapper[data-columns="4"],
            .pratikwp-image-select-wrapper[data-columns="3"] {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .pratikwp-image-select-wrapper[data-columns="5"],
            .pratikwp-image-select-wrapper[data-columns="6"] {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .pratikwp-image-select-wrapper {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            $('.pratikwp-image-select-item').on('click', function() {
                var wrapper = $(this).closest('.pratikwp-image-select-wrapper');
                var isMultiple = $(this).find('input[type="checkbox"]').length > 0;
                var value = $(this).data('value');
                
                if (!isMultiple) {
                    // Radio behavior
                    wrapper.find('.pratikwp-image-select-item').removeClass('selected');
                    wrapper.find('input[type="radio"]').prop('checked', false);
                    
                    $(this).addClass('selected');
                    $(this).find('input[type="radio"]').prop('checked', true);
                } else {
                    // Checkbox behavior
                    $(this).toggleClass('selected');
                    var checkbox = $(this).find('input[type="checkbox"]');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
                
                // Trigger change event for Elementor
                $(this).find('input').trigger('change');
            });
        });
        </script>
        <?php
    }

    /**
     * Get image select control default value.
     */
    public function get_default_value() {
        return $this->get_settings('multiple') ? [] : '';
    }

    /**
     * Parse image select control value.
     */
    public function get_value( $control, $settings ) {
        $value = parent::get_value( $control, $settings );
        
        if ( $control['multiple'] && ! is_array( $value ) ) {
            $value = explode( ',', $value );
        }
        
        return $value;
    }

    /**
     * Sanitize image select control value.
     */
    protected function sanitize_value( $value, $control ) {
        if ( is_array( $value ) ) {
            return array_map( 'sanitize_text_field', $value );
        }
        
        return sanitize_text_field( $value );
    }
}