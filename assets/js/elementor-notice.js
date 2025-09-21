/*!
 * Elementor Notice JavaScript
 * Version: 1.0.1
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Handle Elementor notice dismissal
        $(document).on('click', '.pratikwp-elementor-notice .notice-dismiss', function(e) {
            e.preventDefault();
            
            // AJAX ile bildirimi kapat
            $.ajax({
                url: pratikwpElementorNotice.ajax_url,
                type: 'POST',
                data: {
                    action: 'pratikwp_dismiss_elementor_notice',
                    nonce: pratikwpElementorNotice.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.pratikwp-elementor-notice').fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        console.error('Bildirim kapatılamadı:', response.data.message);
                        
                        // AJAX başarısız olursa sayfa yenileme ile kapatmayı dene
                        window.location.href = pratikwpElementorNotice.dismiss_url;
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX hatası:', error);
                    
                    // AJAX başarısız olursa sayfa yenileme ile kapatmayı dene
                    window.location.href = pratikwpElementorNotice.dismiss_url;
                }
            });
        });

        // Manuel kapatma butonu (isteğe bağlı)
        $(document).on('click', '.pratikwp-dismiss-notice', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: pratikwpElementorNotice.ajax_url,
                type: 'POST',
                data: {
                    action: 'pratikwp_dismiss_elementor_notice',
                    nonce: pratikwpElementorNotice.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.pratikwp-elementor-notice').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }
            });
        });
    });

})(jQuery);