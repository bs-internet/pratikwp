<?php
/**
 * Social Media Admin Page
 *
 * @package PratikWp
 * @version 1.1.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_SocialMedia {
    
    /**
     * Render the admin page
     */
    public function render_page() {
        // Form işleme
        if (isset($_POST['submit']) && check_admin_referer('pratikwp_social_nonce')) {
            $this->save_form();
            ?>
            <div class="pratikwp-alert pratikwp-alert-success">
                <strong><?php esc_html_e('Başarılı!', 'pratikwp'); ?></strong>
                <?php esc_html_e('Sosyal medya hesapları kaydedildi.', 'pratikwp'); ?>
            </div>
            <?php
        }
        
        $social_links = get_option('pratikwp_social_links', []);
        ?>
        <div class="wrap pratikwp-admin-wrap">
            <h1><?php esc_html_e('Sosyal Medya Hesapları', 'pratikwp'); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('pratikwp_social_nonce'); ?>
                
                <div class="pratikwp-card">
                    <div class="card-header">
                        <h3><?php esc_html_e('Sosyal Medya Hesap Linkleri', 'pratikwp'); ?></h3>
                    </div>
                    <div class="card-body">

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label">
                                <label for="facebook"><?php esc_html_e('Facebook', 'pratikwp'); ?></label>
                            </div>
                            <div class="pratikwp-setting-field">
                                <input type="url" id="facebook" name="facebook" value="<?php echo esc_attr($social_links['facebook'] ?? ''); ?>" class="regular-text" placeholder="https://facebook.com/sayfaniz" />
                            </div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label">
                                <label for="twitter"><?php esc_html_e('X (Twitter)', 'pratikwp'); ?></label>
                            </div>
                            <div class="pratikwp-setting-field">
                                <input type="url" id="twitter" name="twitter" value="<?php echo esc_attr($social_links['twitter'] ?? ''); ?>" class="regular-text" placeholder="https://x.com/hesabiniz" />
                            </div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label">
                                <label for="instagram"><?php esc_html_e('Instagram', 'pratikwp'); ?></label>
                            </div>
                            <div class="pratikwp-setting-field">
                                <input type="url" id="instagram" name="instagram" value="<?php echo esc_attr($social_links['instagram'] ?? ''); ?>" class="regular-text" placeholder="https://instagram.com/hesabiniz" />
                            </div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label">
                                <label for="linkedin"><?php esc_html_e('LinkedIn', 'pratikwp'); ?></label>
                            </div>
                            <div class="pratikwp-setting-field">
                                <input type="url" id="linkedin" name="linkedin" value="<?php echo esc_attr($social_links['linkedin'] ?? ''); ?>" class="regular-text" placeholder="https://linkedin.com/company/firmaniz" />
                            </div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label">
                                <label for="youtube"><?php esc_html_e('YouTube', 'pratikwp'); ?></label>
                            </div>
                            <div class="pratikwp-setting-field">
                                <input type="url" id="youtube" name="youtube" value="<?php echo esc_attr($social_links['youtube'] ?? ''); ?>" class="regular-text" placeholder="https://youtube.com/channel/kanaliniz" />
                            </div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label">
                                <label for="tiktok"><?php esc_html_e('TikTok', 'pratikwp'); ?></label>
                            </div>
                            <div class="pratikwp-setting-field">
                                <input type="url" id="tiktok" name="tiktok" value="<?php echo esc_attr($social_links['tiktok'] ?? ''); ?>" class="regular-text" placeholder="https://tiktok.com/@hesabiniz" />
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <?php submit_button(__('Linkleri Kaydet', 'pratikwp'), 'primary', 'submit', false); ?>
                    </div>
                </div>
                
                <p class="description"><em><?php esc_html_e('Boş bıraktığınız sosyal medya hesapları sitenizde görünmeyecektir.', 'pratikwp'); ?></em></p>
            </form>
        </div>
        <?php
    }

    /**
     * Save form data
     */
    private function save_form() {
        $social_links = [
            'facebook' => esc_url_raw($_POST['facebook'] ?? ''),
            'twitter' => esc_url_raw($_POST['twitter'] ?? ''),
            'instagram' => esc_url_raw($_POST['instagram'] ?? ''),
            'linkedin' => esc_url_raw($_POST['linkedin'] ?? ''),
            'youtube' => esc_url_raw($_POST['youtube'] ?? ''),
            'tiktok' => esc_url_raw($_POST['tiktok'] ?? ''),
        ];
        
        update_option('pratikwp_social_links', $social_links);
    }
}