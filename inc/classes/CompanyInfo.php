<?php
/**
 * Company Info Admin Page
 *
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

class PratikWp_CompanyInfo
{
    /**
     * Render the admin page
     */
    public function render_page(): void
    {
        // Form işleme
        if (isset($_POST['submit']) && check_admin_referer('pratikwp_company_nonce')) {
            $this->save_form();
            ?>
            <div class="pratikwp-alert pratikwp-alert-success">
                <strong><?php esc_html_e('Başarılı!', 'pratikwp'); ?></strong>
                <?php esc_html_e('Firma bilgileri kaydedildi.', 'pratikwp'); ?>
            </div>
            <?php
        }

        $company_info = get_option('pratikwp_company_info', []);
        ?>
        <div class="wrap pratikwp-admin-wrap">
            <h1><?php esc_html_e('Firma İletişim Bilgileri', 'pratikwp'); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('pratikwp_company_nonce'); ?>
                
                <div class="pratikwp-card">
                    <div class="card-header">
                        <h3><?php esc_html_e('Temel Bilgiler', 'pratikwp'); ?></h3>
                    </div>
                    <div class="card-body">
                        
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_name"><?php esc_html_e('Firma Adı', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="text" id="company_name" name="company_name" value="<?php echo esc_attr($company_info['name'] ?? ''); ?>" class="regular-text" /></div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_address"><?php esc_html_e('Adres', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><textarea id="company_address" name="company_address" rows="3" class="large-text"><?php echo esc_textarea($company_info['address'] ?? ''); ?></textarea></div>
                        </div>

                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_district"><?php esc_html_e('İlçe / İl', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field" style="display: flex; gap: 15px;">
                                <input type="text" id="company_district" name="company_district" value="<?php echo esc_attr($company_info['district'] ?? ''); ?>" placeholder="<?php esc_attr_e('İlçe', 'pratikwp'); ?>" />
                                <input type="text" name="company_city" value="<?php echo esc_attr($company_info['city'] ?? ''); ?>" placeholder="<?php esc_attr_e('İl', 'pratikwp'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pratikwp-card">
                    <div class="card-header">
                        <h3><?php esc_html_e('İletişim Bilgileri', 'pratikwp'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_phone1"><?php esc_html_e('Sabit Telefon 1', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="tel" id="company_phone1" name="company_phone1" value="<?php echo esc_attr($company_info['phone1'] ?? ''); ?>" class="regular-text" /></div>
                        </div>
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_phone2"><?php esc_html_e('Sabit Telefon 2', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="tel" id="company_phone2" name="company_phone2" value="<?php echo esc_attr($company_info['phone2'] ?? ''); ?>" class="regular-text" /></div>
                        </div>
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_gsm1"><?php esc_html_e('GSM 1', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="tel" id="company_gsm1" name="company_gsm1" value="<?php echo esc_attr($company_info['gsm1'] ?? ''); ?>" class="regular-text" /></div>
                        </div>
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_gsm2"><?php esc_html_e('GSM 2', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="tel" id="company_gsm2" name="company_gsm2" value="<?php echo esc_attr($company_info['gsm2'] ?? ''); ?>" class="regular-text" /></div>
                        </div>
                        <div class="pratikwp-setting-row">
                            <div class="pratikwp-setting-label"><label for="company_email"><?php esc_html_e('E-posta', 'pratikwp'); ?></label></div>
                            <div class="pratikwp-setting-field"><input type="email" id="company_email" name="company_email" value="<?php echo esc_attr($company_info['email'] ?? ''); ?>" class="regular-text" /></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <?php submit_button(__('Bilgileri Kaydet', 'pratikwp'), 'primary', 'submit', false); ?>
                    </div>
                </div>

            </form>
        </div>
        <?php
    }

    /**
     * Save form data
     */
    private function save_form(): void
    {
        $company_info = [
            'name'      => sanitize_text_field($_POST['company_name'] ?? ''),
            'address'   => sanitize_textarea_field($_POST['company_address'] ?? ''),
            'district'  => sanitize_text_field($_POST['company_district'] ?? ''),
            'city'      => sanitize_text_field($_POST['company_city'] ?? ''),
            'phone1'    => sanitize_text_field($_POST['company_phone1'] ?? ''),
            'phone2'    => sanitize_text_field($_POST['company_phone2'] ?? ''),
            'gsm1'      => sanitize_text_field($_POST['company_gsm1'] ?? ''),
            'gsm2'      => sanitize_text_field($_POST['company_gsm2'] ?? ''),
            'email'     => sanitize_email($_POST['company_email'] ?? ''),
        ];

        update_option('pratikwp_company_info', $company_info);
    }
}