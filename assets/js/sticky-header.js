/*!
 * PratikWP Sticky Header JavaScript
 * Advanced sticky header functionality with animations and performance optimization
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // PratikWP Sticky Header Class
    class PratikWPStickyHeader {
        constructor(element, options = {}) {
            this.element = $(element);
            this.isSticky = false;
            this.isHidden = false;
            this.lastScrollTop = 0;
            this.headerHeight = 0;
            this.adminBarHeight = 0;
            this.ticking = false;
            this.resizeTimer = null;
            
            // Default options
            this.options = {
                offset: 100,                    // Offset before header becomes sticky
                hideOnScroll: true,             // Hide header when scrolling down
                showOnScrollUp: true,           // Show header when scrolling up
                hideDistance: 200,              // Distance to scroll before hiding
                animation: 'slide',             // Animation type: 'slide', 'fade', 'none'
                animationSpeed: 300,            // Animation speed in ms
                shrinkOnScroll: false,          // Shrink header when sticky
                changeBackground: true,         // Change background when sticky
                addShadow: true,                // Add shadow when sticky
                breakpoint: 768,               // Disable on screens smaller than this
                zIndex: 9999,                  // Z-index for sticky header
                className: 'sticky-header',    // CSS class for sticky state
                hiddenClassName: 'header-hidden', // CSS class for hidden state
                shrinkClassName: 'header-shrink', // CSS class for shrunk state
                placeholder: true,             // Add placeholder to prevent layout shift
                smartHide: true,               // Smart hide based on scroll direction and speed
                tolerance: 10,                 // Tolerance for scroll direction detection
                onStick: null,                 // Callback when header becomes sticky
                onUnstick: null,               // Callback when header becomes unsticky
                onHide: null,                  // Callback when header is hidden
                onShow: null,                  // Callback when header is shown
                debug: false                   // Debug mode
            };

            // Merge options
            this.options = { ...this.options, ...options };

            this.init();
        }

        init() {
            this.calculateDimensions();
            this.createPlaceholder();
            this.bindEvents();
            this.setupStyles();
            
            // Initial check
            this.checkPosition();
            
            this.log('Sticky header initialized');
        }

        calculateDimensions() {
            this.headerHeight = this.element.outerHeight();
            this.adminBarHeight = $('#wpadminbar').length ? $('#wpadminbar').outerHeight() : 0;
            
            this.log('Header height:', this.headerHeight, 'Admin bar height:', this.adminBarHeight);
        }

        createPlaceholder() {
            if (!this.options.placeholder) return;

            this.placeholder = $('<div class="sticky-header-placeholder"></div>');
            this.placeholder.css({
                height: this.headerHeight + 'px',
                display: 'none'
            });
            
            this.element.after(this.placeholder);
        }

        setupStyles() {
            // Add base CSS for sticky functionality
            const stickyStyles = `
                <style id="pratikwp-sticky-header-styles">
                    .${this.options.className} {
                        position: fixed !important;
                        top: ${this.adminBarHeight}px !important;
                        left: 0 !important;
                        right: 0 !important;
                        width: 100% !important;
                        z-index: ${this.options.zIndex} !important;
                        transition: transform ${this.options.animationSpeed}ms ease,
                                  opacity ${this.options.animationSpeed}ms ease,
                                  background-color ${this.options.animationSpeed}ms ease,
                                  box-shadow ${this.options.animationSpeed}ms ease !important;
                    }
                    
                    .${this.options.className}.${this.options.hiddenClassName} {
                        transform: translateY(-100%) !important;
                    }
                    
                    .${this.options.className}.${this.options.shrinkClassName} {
                        padding-top: 0.5rem !important;
                        padding-bottom: 0.5rem !important;
                    }
                    
                    .${this.options.className}.${this.options.shrinkClassName} .site-logo img,
                    .${this.options.className}.${this.options.shrinkClassName} .custom-logo {
                        max-height: 40px !important;
                        width: auto !important;
                    }
                    
                    .sticky-header-placeholder {
                        transition: height ${this.options.animationSpeed}ms ease !important;
                    }
                    
                    @media (max-width: ${this.options.breakpoint - 1}px) {
                        .${this.options.className} {
                            position: relative !important;
                            top: auto !important;
                            transform: none !important;
                        }
                    }
                </style>
            `;
            
            if (!$('#pratikwp-sticky-header-styles').length) {
                $('head').append(stickyStyles);
            }
        }

        bindEvents() {
            // Optimized scroll event with requestAnimationFrame
            $(window).on('scroll.stickyHeader', () => {
                this.requestTick();
            });

            // Debounced resize event
            $(window).on('resize.stickyHeader', () => {
                if (this.resizeTimer) {
                    clearTimeout(this.resizeTimer);
                }
                
                this.resizeTimer = setTimeout(() => {
                    this.handleResize();
                }, 100);
            });

            // Handle admin bar changes
            if (this.adminBarHeight > 0) {
                $(window).on('wp-responsive-activate.stickyHeader wp-responsive-deactivate.stickyHeader', () => {
                    setTimeout(() => {
                        this.calculateDimensions();
                        this.updateStickyPosition();
                    }, 100);
                });
            }

            // Handle orientation change on mobile
            $(window).on('orientationchange.stickyHeader', () => {
                setTimeout(() => {
                    this.handleResize();
                }, 500);
            });

            // Keyboard navigation support
            $(document).on('keydown.stickyHeader', (e) => {
                // Focus management when header is hidden
                if (this.isHidden && e.keyCode === 9) { // Tab key
                    this.showHeader();
                }
            });
        }

        requestTick() {
            if (!this.ticking) {
                requestAnimationFrame(() => {
                    this.update();
                    this.ticking = false;
                });
                this.ticking = true;
            }
        }

        update() {
            // Skip on small screens if breakpoint is set
            if (window.innerWidth < this.options.breakpoint) {
                this.unstick();
                return;
            }

            this.checkPosition();
        }

        checkPosition() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollDirection = scrollTop > this.lastScrollTop ? 'down' : 'up';
            const scrollDistance = Math.abs(scrollTop - this.lastScrollTop);
            
            this.log('Scroll position:', scrollTop, 'Direction:', scrollDirection, 'Distance:', scrollDistance);

            // Determine if header should be sticky
            const shouldBeSticky = scrollTop > (this.headerHeight + this.options.offset);
            
            if (shouldBeSticky && !this.isSticky) {
                this.stick();
            } else if (!shouldBeSticky && this.isSticky) {
                this.unstick();
            }

            // Handle hide/show logic
            if (this.isSticky && this.options.hideOnScroll) {
                this.handleHideShow(scrollDirection, scrollDistance, scrollTop);
            }

            this.lastScrollTop = scrollTop;
        }

        handleHideShow(direction, distance, scrollTop) {
            if (this.options.smartHide) {
                // Smart hide: consider scroll speed and direction
                const shouldHide = direction === 'down' && 
                                 distance > this.options.tolerance && 
                                 scrollTop > this.options.hideDistance;
                
                const shouldShow = direction === 'up' || 
                                 distance > this.options.tolerance * 2;

                if (shouldHide && !this.isHidden) {
                    this.hideHeader();
                } else if (shouldShow && this.isHidden) {
                    this.showHeader();
                }
            } else {
                // Simple hide: only based on direction
                if (direction === 'down' && !this.isHidden && scrollTop > this.options.hideDistance) {
                    this.hideHeader();
                } else if (direction === 'up' && this.isHidden) {
                    this.showHeader();
                }
            }
        }

        stick() {
            if (this.isSticky) return;

            this.isSticky = true;
            this.element.addClass(this.options.className);
            
            // Show placeholder to prevent layout shift
            if (this.placeholder) {
                this.placeholder.show();
            }

            // Add background change
            if (this.options.changeBackground) {
                this.element.addClass('sticky-background');
            }

            // Add shadow
            if (this.options.addShadow) {
                this.element.addClass('sticky-shadow');
            }

            // Shrink header
            if (this.options.shrinkOnScroll) {
                this.element.addClass(this.options.shrinkClassName);
            }

            // Handle animation
            this.animateStick();

            // Callback
            if (typeof this.options.onStick === 'function') {
                this.options.onStick.call(this);
            }

            // Trigger event
            this.element.trigger('stickyHeader:stick');
            
            this.log('Header stuck');
        }

        unstick() {
            if (!this.isSticky) return;

            this.isSticky = false;
            this.isHidden = false;
            
            this.element.removeClass([
                this.options.className,
                this.options.hiddenClassName,
                this.options.shrinkClassName,
                'sticky-background',
                'sticky-shadow'
            ].join(' '));

            // Hide placeholder
            if (this.placeholder) {
                this.placeholder.hide();
            }

            // Handle animation
            this.animateUnstick();

            // Callback
            if (typeof this.options.onUnstick === 'function') {
                this.options.onUnstick.call(this);
            }

            // Trigger event
            this.element.trigger('stickyHeader:unstick');
            
            this.log('Header unstuck');
        }

        hideHeader() {
            if (this.isHidden || !this.isSticky) return;

            this.isHidden = true;
            this.element.addClass(this.options.hiddenClassName);

            // Callback
            if (typeof this.options.onHide === 'function') {
                this.options.onHide.call(this);
            }

            // Trigger event
            this.element.trigger('stickyHeader:hide');
            
            this.log('Header hidden');
        }

        showHeader() {
            if (!this.isHidden) return;

            this.isHidden = false;
            this.element.removeClass(this.options.hiddenClassName);

            // Callback
            if (typeof this.options.onShow === 'function') {
                this.options.onShow.call(this);
            }

            // Trigger event
            this.element.trigger('stickyHeader:show');
            
            this.log('Header shown');
        }

        animateStick() {
            switch (this.options.animation) {
                case 'fade':
                    this.element.css('opacity', 0).animate({ opacity: 1 }, this.options.animationSpeed);
                    break;
                case 'slide':
                    this.element.css('transform', 'translateY(-100%)').animate({
                        transform: 'translateY(0)'
                    }, this.options.animationSpeed);
                    break;
                case 'none':
                default:
                    // No animation
                    break;
            }
        }

        animateUnstick() {
            switch (this.options.animation) {
                case 'fade':
                    this.element.animate({ opacity: 0 }, this.options.animationSpeed, () => {
                        this.element.css('opacity', '');
                    });
                    break;
                case 'slide':
                    this.element.animate({
                        transform: 'translateY(-100%)'
                    }, this.options.animationSpeed, () => {
                        this.element.css('transform', '');
                    });
                    break;
                case 'none':
                default:
                    // No animation
                    break;
            }
        }

        updateStickyPosition() {
            if (this.isSticky) {
                this.element.css('top', this.adminBarHeight + 'px');
            }
        }

        handleResize() {
            const oldHeight = this.headerHeight;
            this.calculateDimensions();
            
            // Update placeholder height
            if (this.placeholder && oldHeight !== this.headerHeight) {
                this.placeholder.css('height', this.headerHeight + 'px');
            }

            this.updateStickyPosition();
            this.checkPosition();
            
            this.log('Resized - New header height:', this.headerHeight);
        }

        // Public API methods
        enable() {
            this.bindEvents();
            this.checkPosition();
        }

        disable() {
            this.unstick();
            this.unbindEvents();
        }

        destroy() {
            this.unstick();
            this.unbindEvents();
            
            if (this.placeholder) {
                this.placeholder.remove();
            }
            
            $('#pratikwp-sticky-header-styles').remove();
            this.element.removeData('stickyHeader');
            
            this.log('Sticky header destroyed');
        }

        unbindEvents() {
            $(window).off('.stickyHeader');
            $(document).off('.stickyHeader');
        }

        refresh() {
            this.calculateDimensions();
            this.checkPosition();
        }

        toggle() {
            if (this.isSticky) {
                this.unstick();
            } else {
                this.stick();
            }
        }

        isCurrentlySticky() {
            return this.isSticky;
        }

        isCurrentlyHidden() {
            return this.isHidden;
        }

        updateOptions(newOptions) {
            this.options = { ...this.options, ...newOptions };
            this.setupStyles();
        }

        log(...args) {
            if (this.options.debug) {
                console.log('[StickyHeader]', ...args);
            }
        }
    }

    // jQuery plugin
    $.fn.stickyHeader = function(options) {
        return this.each(function() {
            const $element = $(this);
            
            // Prevent multiple initializations
            if ($element.data('stickyHeader')) {
                return;
            }
            
            const stickyHeader = new PratikWPStickyHeader(this, options);
            $element.data('stickyHeader', stickyHeader);
        });
    };

    // Auto-initialize sticky headers
    $(document).ready(function() {
        // Initialize main site header
        const siteHeader = $('.site-header, .elementor-location-header');
        if (siteHeader.length) {
            // Get options from data attributes or theme customizer
            const options = {
                offset: parseInt(siteHeader.data('sticky-offset')) || 100,
                hideOnScroll: siteHeader.data('hide-on-scroll') !== false,
                showOnScrollUp: siteHeader.data('show-on-scroll-up') !== false,
                animation: siteHeader.data('sticky-animation') || 'slide',
                shrinkOnScroll: siteHeader.data('shrink-on-scroll') === true,
                changeBackground: siteHeader.data('change-background') !== false,
                addShadow: siteHeader.data('add-shadow') !== false,
                debug: siteHeader.data('debug') === true
            };
            
            siteHeader.stickyHeader(options);
        }

        // Initialize any other elements with sticky-header class
        $('.pratikwp-sticky-header').each(function() {
            const $element = $(this);
            const options = $element.data('sticky-options') || {};
            
            $element.stickyHeader(options);
        });
    });

    // Refresh on window load (after all images are loaded)
    $(window).on('load', function() {
        $('.site-header, .elementor-location-header, .pratikwp-sticky-header').each(function() {
            const stickyHeader = $(this).data('stickyHeader');
            if (stickyHeader) {
                stickyHeader.refresh();
            }
        });
    });

    // Handle Elementor frontend initialization
    $(window).on('elementor/frontend/init', function() {
        setTimeout(() => {
            const elementorHeader = $('.elementor-location-header');
            if (elementorHeader.length && !elementorHeader.data('stickyHeader')) {
                elementorHeader.stickyHeader();
            }
        }, 100);
    });

    // Expose class globally
    window.PratikWPStickyHeader = PratikWPStickyHeader;

})(jQuery);