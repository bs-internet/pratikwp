/*!
 * PratikWP Admin JavaScript
 * WordPress Admin Panel functionality - Basitleştirilmiş
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // Main admin object
    const PratikWP_Admin = {
        
        // Initialize all admin functions
        init: function() {
            this.navigation();
            this.colorPicker();
            this.imageUpload();
            this.toggleControls();
            this.notifications();
            this.formValidation();
            this.saveSettings();
            this.elementorNotice();
            this.initMetaBoxes();
        },

        // Admin navigation
        navigation: function() {
            // Tab navigation
            $('.pratikwp-admin-nav a').on('click', function(e) {
                e.preventDefault();
                
                const target = $(this).attr('href');
                const tabContent = $(target);
                
                if (tabContent.length) {
                    // Update active tab
                    $('.pratikwp-admin-nav a').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active');
                    
                    // Show target content
                    $('.pratikwp-tab-content').hide();
                    tabContent.show();
                    
                    // Update URL hash
                    window.location.hash = target;
                    
                    // Save active tab
                    localStorage.setItem('pratikwp_active_tab', target);
                }
            });

            // Restore active tab from URL or localStorage
            const hash = window.location.hash;
            const savedTab = localStorage.getItem('pratikwp_active_tab');
            const activeTab = hash || savedTab || $('.pratikwp-admin-nav a').first().attr('href');
            
            if (activeTab) {
                $('.pratikwp-admin-nav a[href="' + activeTab + '"]').trigger('click');
            }

            // Sidebar navigation
            $('.pratikwp-sidebar-nav li').on('click', function() {
                const target = $(this).data('target');
                
                $(this).addClass('active').siblings().removeClass('active');
                $('.pratikwp-content-section').hide();
                $('#' + target).show();
            });
        },

        // Color picker initialization
        colorPicker: function() {
            if ($.fn.wpColorPicker) {
                $('.pratikwp-color-picker').wpColorPicker({
                    defaultColor: false,
                    change: function(event, ui) {
                        const element = $(this);
                        const newColor = ui.color.toString();
                        
                        // Update preview if exists
                        const preview = element.siblings('.color-preview');
                        if (preview.length) {
                            preview.css('background-color', newColor);
                        }
                        
                        // Trigger change event for live preview
                        element.trigger('pratikwp_color_change', [newColor]);
                    },
                    clear: function() {
                        $(this).trigger('pratikwp_color_change', ['']);
                    }
                });
            }

            // Custom color picker for better UX
            $('.pratikwp-color-control').each(function() {
                const control = $(this);
                const input = control.find('input[type="text"]');
                const preview = control.find('.color-preview');
                const picker = control.find('.color-picker-button');
                
                picker.on('click', function() {
                    input.trigger('click');
                });
                
                input.on('change', function() {
                    const color = $(this).val();
                    preview.css('background-color', color);
                });
            });
        },

        // Image upload functionality
        imageUpload: function() {
            $('.pratikwp-upload-button').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const inputField = button.siblings('.pratikwp-upload-input');
                const preview = button.siblings('.pratikwp-upload-preview');
                const removeButton = button.siblings('.pratikwp-remove-upload');
                
                // WordPress media uploader
                const mediaUploader = wp.media({
                    title: 'Select Image',
                    button: {
                        text: 'Use This Image'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    
                    inputField.val(attachment.url);
                    
                    if (preview.length) {
                        if (attachment.type === 'image') {
                            preview.html('<img src="' + attachment.url + '" style="max-width: 200px; max-height: 150px;">');
                        } else {
                            preview.html('<p>' + attachment.filename + '</p>');
                        }
                        preview.show();
                    }
                    
                    removeButton.show();
                    button.text('Change Image');
                });
                
                mediaUploader.open();
            });
            
            // Remove uploaded image
            $('.pratikwp-remove-upload').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const inputField = button.siblings('.pratikwp-upload-input');
                const preview = button.siblings('.pratikwp-upload-preview');
                const uploadButton = button.siblings('.pratikwp-upload-button');
                
                inputField.val('');
                preview.empty().hide();
                button.hide();
                uploadButton.text('Select Image');
            });
        },

        // Toggle controls
        toggleControls: function() {
            $('.pratikwp-toggle input[type="checkbox"]').on('change', function() {
                const toggle = $(this);
                const isChecked = toggle.is(':checked');
                const dependent = $('[data-dependency="' + toggle.attr('name') + '"]');
                
                // Show/hide dependent fields
                if (dependent.length) {
                    if (isChecked) {
                        dependent.slideDown(300);
                    } else {
                        dependent.slideUp(300);
                    }
                }
                
                // Update toggle appearance
                toggle.closest('.pratikwp-toggle').toggleClass('active', isChecked);
            });

            // Initialize toggle states
            $('.pratikwp-toggle input[type="checkbox"]').each(function() {
                $(this).trigger('change');
            });
        },

        // Notification system
        notifications: function() {
            // Auto-hide notifications
            $('.pratikwp-notification').each(function() {
                const notification = $(this);
                setTimeout(function() {
                    notification.fadeOut(300);
                }, 5000);
            });

            // Close notification on click
            $(document).on('click', '.pratikwp-notification .close', function() {
                $(this).closest('.pratikwp-notification').fadeOut(300);
            });
        },

        // Show notification
        showNotification: function(message, type = 'info') {
            const notification = $('<div class="pratikwp-notification pratikwp-notification-' + type + '">' +
                '<span>' + message + '</span>' +
                '<button class="close">&times;</button>' +
                '</div>');
            
            $('body').append(notification);
            
            notification.fadeIn(300);
            
            setTimeout(function() {
                notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        },

        // Form validation
        formValidation: function() {
            $('.pratikwp-admin-form').on('submit', function(e) {
                const form = $(this);
                let isValid = true;
                
                // Check required fields
                form.find('[required]').each(function() {
                    const field = $(this);
                    if (!field.val().trim()) {
                        field.addClass('error');
                        isValid = false;
                    } else {
                        field.removeClass('error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    PratikWP_Admin.showNotification('Please fill in all required fields.', 'error');
                    form.find('.error').first().focus();
                }
            });

            // Real-time validation
            $('.pratikwp-admin-form [required]').on('blur', function() {
                const field = $(this);
                if (!field.val().trim()) {
                    field.addClass('error');
                } else {
                    field.removeClass('error');
                }
            });
        },

        // Save settings - Basit form submit
        saveSettings: function() {
            $('.pratikwp-save-settings').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const form = button.closest('form');
                const originalText = button.text();
                
                button.text('Saving...').prop('disabled', true);
                
                // Normal form submit
                form.submit();
            });
        },

        // Elementor notice functionality
        elementorNotice: function() {
            // Handle Elementor notice dismissal
            $(document).on('click', '.pratikwp-elementor-notice .notice-dismiss', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'pratikwp_dismiss_elementor_notice',
                        nonce: pratikwpElementorNotice.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.pratikwp-elementor-notice').fadeOut(300);
                        }
                    }
                });
            });
        }

        /**
         * Meta Box'a özel tüm JS fonksiyonlarını başlatan ana fonksiyon
         */
        initMetaBoxes: function() {
            // Sadece post edit sayfasındaysak bu fonksiyonları çalıştır
            if (!$('body').hasClass('post-type-post') && !$('body').hasClass('post-type-page')) {
                return;
            }

            this.metaBoxPostFormats();
            this.metaBoxGallery();
            this.metaBoxReadingTime();
            this.metaBoxCharCounter();
        },        

        /**
         * Post Format seçeneklerini göster/gizle
         */
        metaBoxPostFormats: function() {
            const toggleOptions = function() {
                const selectedFormat = $('input[name="post_format"]:checked').val() || 'standard';
                $('.post-format-option').hide();
                $('.post-format-option[data-format="' + selectedFormat + '"]').show();
            };
            
            toggleOptions();
            $('input[name="post_format"]').on('change', toggleOptions);
        },

        /**
         * Galeri meta kutusu yönetimi
         */
        metaBoxGallery: function() {
            $('#select-gallery-images').on('click', function(e) {
                e.preventDefault();
                
                const mediaUploader = wp.media({
                    title: 'Galeri Görsellerini Seç',
                    button: { text: 'Seç' },
                    multiple: true,
                    library: { type: 'image' }
                });

                mediaUploader.on('select', function() {
                    const selection = mediaUploader.state().get('selection');
                    let imageIds = [];
                    const preview = $('#gallery-preview').html('');
                    
                    selection.each(function(attachment) {
                        const att = attachment.toJSON();
                        imageIds.push(att.id);
                        
                        preview.append(
                            `<div class="gallery-thumb" data-id="${att.id}">
                                <img src="${att.sizes.thumbnail.url}" alt="" />
                                <button type="button" class="remove-gallery-image">&times;</button>
                            </div>`
                        );
                    });
                    
                    $('#pratikwp_gallery_images').val(imageIds.join(','));
                });

                mediaUploader.open();
            });

            // Galeri görselini kaldır
            $(document).on('click', '.remove-gallery-image', function() {
                const thumb = $(this).closest('.gallery-thumb');
                const imageId = thumb.data('id');
                thumb.remove();
                
                const currentIds = $('#pratikwp_gallery_images').val().split(',');
                const newIds = currentIds.filter(id => id != imageId.toString());
                $('#pratikwp_gallery_images').val(newIds.join(','));
            });
        },

        /**
         * Tahmini okuma süresini hesapla
         */
        metaBoxReadingTime: function() {
            $('#calculate-reading-time').on('click', function() {
                let content = '';
                const editor = tinyMCE.get('content');
                
                if (editor && !editor.isHidden()) {
                    content = editor.getContent();
                } else {
                    content = $('#content').val();
                }
                
                const wordCount = content.replace(/<[^>]*>/g, ' ').split(/\s+/).filter(Boolean).length;
                const readingTime = Math.max(1, Math.ceil(wordCount / 200)); // 200 kelime/dakika
                
                $('#pratikwp_estimated_reading_time').val(readingTime);
            });
        },

        /**
         * Karakter sayacı
         */
        metaBoxCharCounter: function() {
            const counter = function() {
                const count = $(this).val().length;
                const counterSpan = $('#meta-description-count');
                counterSpan.text(count);
                
                if (count > 160) {
                    counterSpan.css('color', 'red');
                } else if (count > 140) {
                    counterSpan.css('color', 'orange');
                } else {
                    counterSpan.css('color', 'green');
                }
            };

            $('#pratikwp_meta_description').on('input', counter).trigger('input');
        }        
    };

    // Utility functions for admin - Sadece gerekli olanlar
    window.PratikWP_Admin_Utils = {
        
        // Format file size
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        // Validate URL
        isValidUrl: function(url) {
            try {
                new URL(url);
                return true;
            } catch {
                return false;
            }
        },

        // Escape HTML
        escapeHtml: function(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    };

    // Initialize when document is ready
    $(document).ready(function() {
        PratikWP_Admin.init();
    });

    // Make admin object available globally
    window.PratikWP_Admin = PratikWP_Admin;

})(jQuery);