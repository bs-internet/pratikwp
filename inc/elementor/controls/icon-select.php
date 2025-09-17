<?php
/**
 * Elementor Icon Select Control
 * 
 * @package PratikWp
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor icon select control.
 */
class PratikWp_Icon_Select_Control extends \Elementor\Base_Data_Control {

    /**
     * Get icon select control type.
     */
    public function get_type() {
        return 'pratikwp-icon-select';
    }

    /**
     * Enqueue icon select control scripts and styles.
     */
    public function enqueue() {
        // Control styles
        wp_enqueue_style(
            'pratikwp-icon-select-control',
            get_template_directory_uri() . '/assets/css/elementor-controls.css',
            [],
            PRATIKWP_VERSION
        );

        // Control script
        wp_enqueue_script(
            'pratikwp-icon-select-control',
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
            'columns' => 6,
            'toggle' => false,
            'multiple' => false,
            'size' => 'normal', // small, normal, large
            'style' => 'default', // default, rounded, square
        ];
    }

    /**
     * Get predefined icon sets
     */
    public static function get_icon_sets() {
        return [
            'social' => [
                'fab fa-facebook-f' => 'Facebook',
                'fab fa-x-twitter' => 'X (Twitter)',
                'fab fa-instagram' => 'Instagram',
                'fab fa-youtube' => 'YouTube',
                'fab fa-linkedin-in' => 'LinkedIn',
                'fab fa-tiktok' => 'TikTok',
                'fab fa-pinterest-p' => 'Pinterest',
                'fab fa-whatsapp' => 'WhatsApp',
                'fab fa-telegram-plane' => 'Telegram',
                'fab fa-discord' => 'Discord',
                'fab fa-snapchat-ghost' => 'Snapchat',
                'fab fa-reddit-alien' => 'Reddit',
            ],
            'contact' => [
                'fas fa-phone' => 'Telefon',
                'fas fa-envelope' => 'Email',
                'fas fa-map-marker-alt' => 'Konum',
                'fas fa-clock' => 'Saat',
                'fas fa-fax' => 'Faks',
                'fas fa-mobile-alt' => 'Mobil',
                'fas fa-globe' => 'Website',
                'fas fa-comments' => 'Mesaj',
            ],
            'business' => [
                'fas fa-briefcase' => 'Çanta',
                'fas fa-building' => 'Bina',
                'fas fa-handshake' => 'Anlaşma',
                'fas fa-chart-line' => 'Grafik',
                'fas fa-users' => 'Kullanıcılar',
                'fas fa-lightbulb' => 'Fikir',
                'fas fa-cogs' => 'Ayarlar',
                'fas fa-trophy' => 'Ödül',
            ],
            'arrows' => [
                'fas fa-arrow-right' => 'Sağ Ok',
                'fas fa-arrow-left' => 'Sol Ok',
                'fas fa-arrow-up' => 'Yukarı Ok',
                'fas fa-arrow-down' => 'Aşağı Ok',
                'fas fa-chevron-right' => 'Chevron Sağ',
                'fas fa-chevron-left' => 'Chevron Sol',
                'fas fa-chevron-up' => 'Chevron Yukarı',
                'fas fa-chevron-down' => 'Chevron Aşağı',
                'fas fa-angle-right' => 'Angle Sağ',
                'fas fa-angle-left' => 'Angle Sol',
            ],
        ];
    }

    /**
     * Render icon select control output in the editor.
     */
    public function content_template() {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <# if ( data.label ) { #>
                <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <# } #>
            
            <div class="elementor-control-input-wrapper elementor-control-unit-5">
                <div class="pratikwp-icon-select-wrapper" 
                     data-columns="{{ data.columns }}" 
                     data-size="{{ data.size }}" 
                     data-style="{{ data.style }}">
                    
                    <# if ( Object.keys(data.options).length > 20 ) { #>
                        <div class="pratikwp-icon-search">
                            <input type="text" 
                                   class="pratikwp-icon-search-input" 
                                   placeholder="<?php esc_attr_e('İkon ara...', 'pratikwp'); ?>" />
                        </div>
                    <# } #>
                    
                    <div class="pratikwp-icon-grid">
                        <# _.each( data.options, function( option_title, option_value ) { #>
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
                            
                            <div class="pratikwp-icon-select-item {{ selected }}" 
                                 data-value="{{ option_value }}" 
                                 data-title="{{ option_title }}">
                                
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
                                
                                <label for="{{ data._cid }}-{{ option_value }}" class="pratikwp-icon-select-label">
                                    <i class="{{ option_value }}"></i>
                                    <span class="pratikwp-icon-tooltip">{{ option_title }}</span>
                                </label>
                            </div>
                        <# } ); #>
                    </div>
                </div>
            </div>
            
            <# if ( data.description ) { #>
                <div class="elementor-control-field-description">{{{ data.description }}}</div>
            <# } #>
        </div>
        
        <style>
        .pratikwp-icon-select-wrapper {
            position: relative;
        }
        
        .pratikwp-icon-search {
            margin-bottom: 10px;
        }
        
        .pratikwp-icon-search-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e6e9ec;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .pratikwp-icon-grid {
            display: grid;
            gap: 5px;
            grid-template-columns: repeat({{ data.columns }}, 1fr);
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #e6e9ec;
            border-radius: 4px;
            padding: 10px;
        }
        
        .pratikwp-icon-select-item {
            position: relative;
            cursor: pointer;
            border: 1px solid #e6e9ec;
            border-radius: 3px;
            transition: all 0.2s ease;
        }
        
        /* Size variations */
        .pratikwp-icon-select-wrapper[data-size="small"] .pratikwp-icon-select-item {
            height: 28px;
        }
        
        .pratikwp-icon-select-wrapper[data-size="normal"] .pratikwp-icon-select-item {
            height: 36px;
        }
        
        .pratikwp-icon-select-wrapper[data-size="large"] .pratikwp-icon-select-item {
            height: 44px;
        }
        
        /* Style variations */
        .pratikwp-icon-select-wrapper[data-style="rounded"] .pratikwp-icon-select-item {
            border-radius: 50%;
        }
        
        .pratikwp-icon-select-wrapper[data-style="square"] .pratikwp-icon-select-item {
            border-radius: 0;
        }
        
        .pratikwp-icon-select-item:hover {
            border-color: #a4afb7;
            transform: scale(1.05);
        }
        
        .pratikwp-icon-select-item.selected {
            border-color: #007cba;
            background-color: #007cba;
            color: white;
        }
        
        .pratikwp-icon-select-label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            cursor: pointer;
            position: relative;
        }
        
        .pratikwp-icon-select-label i {
            font-size: 14px;
            color: #6c757d;
            transition: color 0.2s ease;
        }
        
        .pratikwp-icon-select-wrapper[data-size="small"] .pratikwp-icon-select-label i {
            font-size: 12px;
        }
        
        .pratikwp-icon-select-wrapper[data-size="large"] .pratikwp-icon-select-label i {
            font-size: 16px;
        }
        
        .pratikwp-icon-select-item.selected .pratikwp-icon-select-label i {
            color: white;
        }
        
        .pratikwp-icon-tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            z-index: 1000;
            pointer-events: none;
            margin-bottom: 5px;
        }
        
        .pratikwp-icon-tooltip:before {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border: 4px solid transparent;
            border-top-color: #333;
        }
        
        .pratikwp-icon-select-item:hover .pratikwp-icon-tooltip {
            opacity: 1;
            visibility: visible;
        }
        
        /* Hide scrollbar but keep functionality */
        .pratikwp-icon-grid::-webkit-scrollbar {
            width: 4px;
        }
        
        .pratikwp-icon-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .pratikwp-icon-grid::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        .pratikwp-icon-grid::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .pratikwp-icon-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Icon selection
            $('.pratikwp-icon-select-item').on('click', function() {
                var wrapper = $(this).closest('.pratikwp-icon-select-wrapper');
                var isMultiple = $(this).find('input[type="checkbox"]').length > 0;
                var value = $(this).data('value');
                
                if (!isMultiple) {
                    // Radio behavior
                    wrapper.find('.pratikwp-icon-select-item').removeClass('selected');
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
            
            // Icon search functionality
            $('.pratikwp-icon-search-input').on('keyup', function() {
                var searchTerm = $(this).val().toLowerCase();
                var wrapper = $(this).closest('.pratikwp-icon-select-wrapper');
                var icons = wrapper.find('.pratikwp-icon-select-item');
                
                icons.each(function() {
                    var iconTitle = $(this).data('title').toLowerCase();
                    var iconValue = $(this).data('value').toLowerCase();
                    
                    if (iconTitle.includes(searchTerm) || iconValue.includes(searchTerm)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * Get icon select control default value.
     */
    public function get_default_value() {
        return $this->get_settings('multiple') ? [] : '';
    }

    /**
     * Parse icon select control value.
     */
    public function get_value( $control, $settings ) {
        $value = parent::get_value( $control, $settings );
        
        if ( $control['multiple'] && ! is_array( $value ) ) {
            $value = explode( ',', $value );
        }
        
        return $value;
    }

    /**
     * Sanitize icon select control value.
     */
    protected function sanitize_value( $value, $control ) {
        if ( is_array( $value ) ) {
            return array_map( 'sanitize_text_field', $value );
        }
        
        return sanitize_text_field( $value );
    }
}