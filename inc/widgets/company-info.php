<?php
/**
 * Company Info WordPress Widget
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Company_Info_WP_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'pratikwp_company_info_widget',
            __('PratikWp Firma Bilgileri', 'pratikwp'),
            ['description' => __('Firma iletişim bilgilerini gösterir.', 'pratikwp')]
        );
    }

    // Widget çıktısı (Ön Yüz)
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $fields = $instance['fields'] ?? ['name', 'full_address', 'phone', 'email'];
        $shortcode_atts = implode(',', $fields);
        
        // Shortcode'u yeniden kullanarak kodu tekrar etmiyoruz (DRY prensibi)
        echo do_shortcode('[pratikwp_firma_bilgisi goster="' . esc_attr($shortcode_atts) . '"]');

        echo $args['after_widget'];
    }

    // Widget ayarları (Yönetim Paneli)
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : __('İletişim', 'pratikwp');
        $selected_fields = $instance['fields'] ?? ['name', 'full_address', 'phone1', 'email'];

        $all_fields = [
            'name'         => __('Firma Adı', 'pratikwp'),
            'full_address' => __('Adres', 'pratikwp'),
            'address'      => __('Sadece Açık Adres', 'pratikwp'),
            'district'     => __('Sadece İlçe', 'pratikwp'),
            'city'         => __('Sadece İl', 'pratikwp'),            
            'phone1'       => __('Sabit Telefon 1', 'pratikwp'),
            'phone2'       => __('Sabit Telefon 2', 'pratikwp'),
            'gsm1'         => __('GSM 1', 'pratikwp'),
            'gsm2'         => __('GSM 2', 'pratikwp'),
            'email'        => __('E-posta', 'pratikwp'),
        ];
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Başlık:', 'pratikwp'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <strong><?php esc_html_e('Gösterilecek Alanlar:', 'pratikwp'); ?></strong><br>
            <?php foreach ($all_fields as $key => $label) : ?>
                <label>
                    <input type="checkbox" name="<?php echo esc_attr($this->get_field_name('fields')); ?>[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $selected_fields)); ?>>
                    <?php echo esc_html($label); ?>
                </label><br>
            <?php endforeach; ?>
        </p>
        <?php
    }

    // Widget ayarlarını kaydetme
    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['fields'] = (!empty($new_instance['fields'])) ? (array) $new_instance['fields'] : [];
        return $instance;
    }
}

// Widget'ı kaydet
function register_pratikwp_company_info_widget() {
    register_widget('PratikWp_Company_Info_WP_Widget');
}
add_action('widgets_init', 'register_pratikwp_company_info_widget');