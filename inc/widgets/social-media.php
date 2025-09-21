<?php
/**
 * Social Media WordPress Widget
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_Social_Media_WP_Widget extends WP_Widget
{

    public function __construct()
    {
        parent::__construct(
            'pratikwp_social_media_widget',
            __('PratikWp Sosyal Medya', 'pratikwp'),
            ['description' => __('Sosyal medya hesap linklerini gösterir.', 'pratikwp')]
        );
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $platforms = !empty($instance['platforms']) ? implode(',', $instance['platforms']) : '';
        $style = $instance['style'] ?? 'icons';
        $target = $instance['target'] ?? 'yes';
        
        echo do_shortcode('[pratikwp_sosyal_medya goster="' . esc_attr($platforms) . '" stil="' . esc_attr($style) . '" yeni_pencere="' . esc_attr($target) . '"]');

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = $instance['title'] ?? __('Bizi Takip Edin', 'pratikwp');
        $selected_platforms = $instance['platforms'] ?? ['facebook', 'twitter', 'instagram'];
        $style = $instance['style'] ?? 'icons';
        $target = $instance['target'] ?? 'yes';

        $all_platforms = [
            'facebook'  => 'Facebook',
            'twitter'   => 'Twitter',
            'instagram' => 'Instagram',
            'linkedin'  => 'LinkedIn',
            'youtube'   => 'YouTube',
            'tiktok'    => 'TikTok',
        ];
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Başlık:', 'pratikwp'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <strong><?php esc_html_e('Gösterilecek Platformlar:', 'pratikwp'); ?></strong><br>
            <?php foreach ($all_platforms as $key => $label) : ?>
                <label>
                    <input type="checkbox" name="<?php echo esc_attr($this->get_field_name('platforms')); ?>[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $selected_platforms)); ?>>
                    <?php echo esc_html($label); ?>
                </label><br>
            <?php endforeach; ?>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>"><?php esc_html_e('Stil:', 'pratikwp'); ?></label>
            <select class='widefat' id="<?php echo esc_attr($this->get_field_id('style')); ?>" name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value='icons' <?php selected($style, 'icons'); ?>><?php esc_html_e('Sadece İkonlar', 'pratikwp'); ?></option>
                <option value='text' <?php selected($style, 'text'); ?>><?php esc_html_e('Sadece Metin', 'pratikwp'); ?></option>
                <option value='both' <?php selected($style, 'both'); ?>><?php esc_html_e('İkon + Metin', 'pratikwp'); ?></option>
            </select>
        </p>
         <p>
            <input class="checkbox" type="checkbox" <?php checked($target, 'yes'); ?> id="<?php echo esc_attr($this->get_field_id('target')); ?>" name="<?php echo esc_attr($this->get_field_name('target')); ?>" value="yes" />
            <label for="<?php echo esc_attr($this->get_field_id('target')); ?>"><?php esc_html_e('Yeni pencerede aç', 'pratikwp'); ?></label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['platforms'] = (!empty($new_instance['platforms'])) ? (array) $new_instance['platforms'] : [];
        $instance['style'] = (!empty($new_instance['style'])) ? strip_tags($new_instance['style']) : 'icons';
        $instance['target'] = (!empty($new_instance['target'])) ? 'yes' : 'no';
        return $instance;
    }
}

// Widget'ı kaydet
function register_pratikwp_social_media_widget() {
    register_widget('PratikWp_Social_Media_WP_Widget');
}
add_action('widgets_init', 'register_pratikwp_social_media_widget');