/*!
 * PratikWP Admin JavaScript
 * WordPress Admin Panel functionality
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
            this.rangeSliders();
            this.tabSwitcher();
            this.demoImport();
            this.systemInfo();
            this.notifications();
            this.formValidation();
            this.saveSettings();
            this.resetSettings();
            this.exportImport();
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

        // Range sliders
        rangeSliders: function() {
            $('.pratikwp-range-control').each(function() {
                const container = $(this);
                const slider = container.find('.pratikwp-range');
                const display = container.find('.range-value');
                const input = container.find('input[type="hidden"]');
                
                slider.on('input', function() {
                    const value = $(this).val();
                    display.text(value);
                    input.val(value);
                });
                
                // Initialize display
                display.text(slider.val());
            });
        },

        // Tab switcher for settings
        tabSwitcher: function() {
            $('.pratikwp-settings-tabs .tab-button').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const target = button.data('tab');
                const container = button.closest('.pratikwp-settings-container');
                
                // Update active button
                button.addClass('active').siblings().removeClass('active');
                
                // Show target panel
                container.find('.tab-panel').removeClass('active');
                container.find('#' + target).addClass('active');
            });
        },

        // Demo import functionality
        demoImport: function() {
            $('.pratikwp-demo-import .import-button').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const demoId = button.data('demo');
                const progressContainer = $('.pratikwp-import-progress');
                const completeContainer = $('.pratikwp-import-complete');
                
                if (button.hasClass('importing')) return;
                
                // Confirm import
                if (!confirm('This will import demo content and overwrite existing content. Are you sure?')) {
                    return;
                }
                
                button.addClass('importing').text('Importing...');
                progressContainer.show();
                
                // Start import process
                this.startDemoImport(demoId, progressContainer, completeContainer, button);
            });
        },

        // Start demo import process
        startDemoImport: function(demoId, progressContainer, completeContainer, button) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'pratikwp_import_demo',
                    demo_id: demoId,
                    nonce: pratikwp_admin.nonce
                },
                xhr: function() {
                    const xhr = new window.XMLHttpRequest();
                    
                    // Handle progress updates
                    xhr.addEventListener('progress', function(e) {
                        if (e.lengthComputable) {
                            const percentComplete = (e.loaded / e.total) * 100;
                            $('.progress-fill').css('width', percentComplete + '%');
                            $('.progress-text').text(Math.round(percentComplete) + '%');
                        }
                    });
                    
                    return xhr;
                },
                success: function(response) {
                    if (response.success) {
                        progressContainer.hide();
                        completeContainer.show();
                        button.removeClass('importing').text('Import Complete');
                        
                        // Show success notification
                        PratikWP_Admin.showNotification('Demo imported successfully!', 'success');
                    } else {
                        PratikWP_Admin.showNotification('Import failed: ' + response.data.message, 'error');
                        button.removeClass('importing').text('Import Demo');
                    }
                },
                error: function() {
                    PratikWP_Admin.showNotification('Import failed due to server error.', 'error');
                    button.removeClass('importing').text('Import Demo');
                }
            });
        },

        // System info functionality
        systemInfo: function() {
            // Copy system info
            $('.copy-system-info').on('click', function(e) {
                e.preventDefault();
                
                let systemInfo = 'PratikWP System Information\n';
                systemInfo += '================================\n\n';
                
                $('.pratikwp-system-row').each(function() {
                    const label = $(this).find('.pratikwp-system-label').text();
                    const value = $(this).find('.pratikwp-system-value').text();
                    systemInfo += label + ': ' + value + '\n';
                });
                
                // Copy to clipboard
                navigator.clipboard.writeText(systemInfo).then(function() {
                    PratikWP_Admin.showNotification('System information copied to clipboard!', 'success');
                });
            });

            // Download system info
            $('.download-system-info').on('click', function(e) {
                e.preventDefault();
                
                let systemInfo = 'PratikWP System Information\n';
                systemInfo += '================================\n\n';
                
                $('.pratikwp-system-row').each(function() {
                    const label = $(this).find('.pratikwp-system-label').text();
                    const value = $(this).find('.pratikwp-system-value').text();
                    systemInfo += label + ': ' + value + '\n';
                });
                
                // Create download
                const blob = new Blob([systemInfo], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'pratikwp-system-info.txt';
                a.click();
                window.URL.revokeObjectURL(url);
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

        // Save settings
        saveSettings: function() {
            $('.pratikwp-save-settings').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const form = button.closest('form');
                const originalText = button.text();
                
                button.text('Saving...').prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: form.serialize() + '&action=pratikwp_save_settings&nonce=' + pratikwp_admin.nonce,
                    success: function(response) {
                        if (response.success) {
                            PratikWP_Admin.showNotification('Settings saved successfully!', 'success');
                        } else {
                            PratikWP_Admin.showNotification('Failed to save settings: ' + response.data.message, 'error');
                        }
                    },
                    error: function() {
                        PratikWP_Admin.showNotification('Failed to save settings due to server error.', 'error');
                    },
                    complete: function() {
                        button.text(originalText).prop('disabled', false);
                    }
                });
            });
        },

        // Reset settings
        resetSettings: function() {
            $('.pratikwp-reset-settings').on('click', function(e) {
                e.preventDefault();
                
                if (!confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
                    return;
                }
                
                const button = $(this);
                const originalText = button.text();
                
                button.text('Resetting...').prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'pratikwp_reset_settings',
                        nonce: pratikwp_admin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            PratikWP_Admin.showNotification('Settings reset successfully!', 'success');
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            PratikWP_Admin.showNotification('Failed to reset settings: ' + response.data.message, 'error');
                        }
                    },
                    error: function() {
                        PratikWP_Admin.showNotification('Failed to reset settings due to server error.', 'error');
                    },
                    complete: function() {
                        button.text(originalText).prop('disabled', false);
                    }
                });
            });
        },

        // Export/Import settings
        exportImport: function() {
            // Export settings
            $('.pratikwp-export-settings').on('click', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'pratikwp_export_settings',
                        nonce: pratikwp_admin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            const blob = new Blob([JSON.stringify(response.data, null, 2)], {
                                type: 'application/json'
                            });
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'pratikwp-settings-' + new Date().toISOString().split('T')[0] + '.json';
                            a.click();
                            window.URL.revokeObjectURL(url);
                            
                            PratikWP_Admin.showNotification('Settings exported successfully!', 'success');
                        } else {
                            PratikWP_Admin.showNotification('Failed to export settings.', 'error');
                        }
                    }
                });
            });

            // Import settings
            $('.pratikwp-import-settings').on('click', function(e) {
                e.preventDefault();
                $('.pratikwp-import-file').click();
            });

            $('.pratikwp-import-file').on('change', function() {
                const file = this.files[0];
                if (!file) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        const settings = JSON.parse(e.target.result);
                        
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'pratikwp_import_settings',
                                settings: JSON.stringify(settings),
                                nonce: pratikwp_admin.nonce
                            },
                            success: function(response) {
                                if (response.success) {
                                    PratikWP_Admin.showNotification('Settings imported successfully!', 'success');
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                } else {
                                    PratikWP_Admin.showNotification('Failed to import settings: ' + response.data.message, 'error');
                                }
                            }
                        });
                    } catch (error) {
                        PratikWP_Admin.showNotification('Invalid settings file format.', 'error');
                    }
                };
                reader.readAsText(file);
            });
        }
    };

    // Utility functions for admin
    window.PratikWP_Admin_Utils = {
        
        // Serialize form data as object
        serializeObject: function(form) {
            const formData = new FormData(form[0]);
            const object = {};
            
            formData.forEach(function(value, key) {
                if (object[key]) {
                    if (!Array.isArray(object[key])) {
                        object[key] = [object[key]];
                    }
                    object[key].push(value);
                } else {
                    object[key] = value;
                }
            });
            
            return object;
        },

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