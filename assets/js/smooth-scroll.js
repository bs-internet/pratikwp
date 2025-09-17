/*!
 * PratikWP Smooth Scroll JavaScript
 * Enhanced smooth scrolling with performance optimization and advanced features
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // PratikWP Smooth Scroll Class
    class PratikWPSmoothScroll {
        constructor(options = {}) {
            // Default options
            this.options = {
                selector: 'a[href*="#"]',        // Selector for anchor links
                speed: 800,                      // Animation duration in ms
                easing: 'easeInOutCubic',       // Easing function
                offset: 0,                       // Additional offset from target
                headerSelector: '.site-header, .elementor-location-header', // Header to account for
                updateURL: true,                 // Update URL with hash
                beforeScroll: null,              // Callback before scroll starts
                afterScroll: null,               // Callback after scroll completes
                interrupt: true,                 // Allow user to interrupt scrolling
                debug: false,                    // Debug mode
                mobile: true,                    // Enable on mobile devices
                accessibility: true,             // Accessibility features
                focusTarget: true,               // Focus target element after scroll
                smoothBehavior: 'auto',          // Use native smooth behavior where available
                tolerance: 5,                    // Tolerance for detecting if already at target
                exclude: [                       // Selectors to exclude from smooth scrolling
                    '[href="#"]',
                    '[href*="javascript:"]',
                    '[href*="mailto:"]',
                    '[href*="tel:"]',
                    '.no-smooth-scroll',
                    '[data-no-smooth]'
                ]
            };

            // Merge options
            this.options = { ...this.options, ...options };

            this.isScrolling = false;
            this.scrollTimer = null;
            this.headerHeight = 0;
            this.bindEvents();
            this.calculateHeaderHeight();
            
            this.log('Smooth scroll initialized');
        }

        bindEvents() {
            // Handle anchor link clicks
            $(document).on('click.smoothScroll', this.options.selector, (e) => {
                this.handleClick(e);
            });

            // Handle browser back/forward navigation
            $(window).on('popstate.smoothScroll', (e) => {
                if (e.originalEvent.state && e.originalEvent.state.smoothScroll) {
                    this.scrollToHash(window.location.hash, false);
                }
            });

            // Recalculate header height on resize
            $(window).on('resize.smoothScroll', this.debounce(() => {
                this.calculateHeaderHeight();
            }, 100));

            // Handle keyboard navigation
            if (this.options.accessibility) {
                $(document).on('keydown.smoothScroll', (e) => {
                    // Handle Enter and Space on anchor links
                    if ((e.keyCode === 13 || e.keyCode === 32) && $(e.target).is(this.options.selector)) {
                        this.handleClick(e);
                    }
                });
            }

            // Allow interruption of scrolling
            if (this.options.interrupt) {
                $(document).on('wheel.smoothScroll touchstart.smoothScroll', () => {
                    if (this.isScrolling) {
                        this.stopScrolling();
                    }
                });
            }

            // Handle hash on page load
            $(document).ready(() => {
                if (window.location.hash) {
                    // Small delay to ensure page is fully loaded
                    setTimeout(() => {
                        this.scrollToHash(window.location.hash, false);
                    }, 100);
                }
            });
        }

        handleClick(e) {
            const link = $(e.currentTarget);
            const href = link.attr('href');
            
            // Skip if excluded
            if (this.isExcluded(link, href)) {
                this.log('Link excluded from smooth scroll:', href);
                return;
            }

            // Skip on mobile if disabled
            if (!this.options.mobile && this.isMobileDevice()) {
                return;
            }

            // Extract hash
            const hash = this.getHashFromHref(href);
            if (!hash) {
                this.log('No valid hash found:', href);
                return;
            }

            // Find target element
            const target = this.findTarget(hash);
            if (!target || !target.length) {
                this.log('Target not found:', hash);
                return;
            }

            e.preventDefault();

            // Check if already at target position
            if (this.isAtTarget(target)) {
                this.log('Already at target position');
                return;
            }

            // Perform smooth scroll
            this.scrollToTarget(target, hash, link);
        }

        isExcluded(link, href) {
            // Check if link matches any exclude pattern
            for (const excludeSelector of this.options.exclude) {
                if (link.is(excludeSelector) || href.match(excludeSelector)) {
                    return true;
                }
            }

            // Check if different page
            const linkPathname = link[0].pathname || '';
            if (linkPathname !== window.location.pathname) {
                return true;
            }

            return false;
        }

        getHashFromHref(href) {
            try {
                const url = new URL(href, window.location.origin);
                return url.hash;
            } catch {
                // If URL parsing fails, try to extract hash manually
                const hashIndex = href.indexOf('#');
                return hashIndex !== -1 ? href.substring(hashIndex) : '';
            }
        }

        findTarget(hash) {
            let target;
            
            try {
                // Try direct ID match first
                target = $(hash);
                
                if (!target.length) {
                    // Try escaped version for special characters
                    const escapedHash = hash.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, '\\$&');
                    target = $(escapedHash);
                }
                
                if (!target.length) {
                    // Try name attribute match
                    target = $(`[name="${hash.substring(1)}"]`);
                }
                
                return target;
            } catch (error) {
                this.log('Error finding target:', error);
                return $();
            }
        }

        isAtTarget(target) {
            const targetPosition = this.getTargetPosition(target);
            const currentPosition = window.pageYOffset || document.documentElement.scrollTop;
            
            return Math.abs(currentPosition - targetPosition) <= this.options.tolerance;
        }

        getTargetPosition(target) {
            const targetOffset = target.offset().top;
            const headerOffset = this.getHeaderOffset();
            const customOffset = this.options.offset;
            
            return Math.max(0, targetOffset - headerOffset - customOffset);
        }

        getHeaderOffset() {
            if (this.headerHeight > 0) {
                return this.headerHeight;
            }
            
            const header = $(this.options.headerSelector);
            if (header.length && header.hasClass('sticky-header')) {
                return header.outerHeight();
            }
            
            return 0;
        }

        calculateHeaderHeight() {
            const header = $(this.options.headerSelector);
            if (header.length && header.hasClass('sticky-header')) {
                this.headerHeight = header.outerHeight();
            } else {
                this.headerHeight = 0;
            }
            
            this.log('Header height calculated:', this.headerHeight);
        }

        scrollToTarget(target, hash, sourceLink = null) {
            const targetPosition = this.getTargetPosition(target);
            
            // Before scroll callback
            if (typeof this.options.beforeScroll === 'function') {
                this.options.beforeScroll.call(this, target, hash, sourceLink);
            }

            // Trigger event
            $(document).trigger('smoothScroll:start', [target, hash]);

            this.animateScroll(targetPosition, () => {
                this.handleScrollComplete(target, hash, sourceLink);
            });
        }

        scrollToHash(hash, updateURL = true) {
            if (!hash) return;
            
            const target = this.findTarget(hash);
            if (!target || !target.length) return;
            
            this.scrollToTarget(target, hash);
            
            if (updateURL && this.options.updateURL) {
                this.updateURL(hash);
            }
        }

        animateScroll(targetPosition, callback) {
            this.isScrolling = true;
            
            // Use native smooth behavior if available and preferred
            if (this.options.smoothBehavior === 'native' && 'scrollBehavior' in document.documentElement.style) {
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Fallback timing since we can't detect when native scroll completes
                setTimeout(() => {
                    this.isScrolling = false;
                    if (callback) callback();
                }, this.options.speed);
                
                return;
            }

            // Custom animation
            const startPosition = window.pageYOffset || document.documentElement.scrollTop;
            const distance = targetPosition - startPosition;
            const startTime = performance.now();
            
            const animateFrame = (currentTime) => {
                const timeElapsed = currentTime - startTime;
                const progress = Math.min(timeElapsed / this.options.speed, 1);
                
                const easedProgress = this.easing(progress);
                const currentPosition = startPosition + (distance * easedProgress);
                
                window.scrollTo(0, currentPosition);
                
                if (progress < 1 && this.isScrolling) {
                    requestAnimationFrame(animateFrame);
                } else {
                    this.isScrolling = false;
                    if (callback) callback();
                }
            };
            
            requestAnimationFrame(animateFrame);
        }

        handleScrollComplete(target, hash, sourceLink) {
            // Update URL
            if (this.options.updateURL) {
                this.updateURL(hash);
            }

            // Focus target for accessibility
            if (this.options.accessibility && this.options.focusTarget) {
                this.focusTarget(target);
            }

            // After scroll callback
            if (typeof this.options.afterScroll === 'function') {
                this.options.afterScroll.call(this, target, hash, sourceLink);
            }

            // Trigger event
            $(document).trigger('smoothScroll:complete', [target, hash]);
            
            this.log('Scroll complete to:', hash);
        }

        updateURL(hash) {
            if (history.pushState) {
                const newURL = window.location.pathname + window.location.search + hash;
                history.pushState({ smoothScroll: true }, '', newURL);
            } else {
                window.location.hash = hash;
            }
        }

        focusTarget(target) {
            // Make target focusable if it isn't already
            if (!target.is(':focusable')) {
                target.attr('tabindex', '-1');
            }
            
            target.focus();
            
            // Remove tabindex if we added it
            target.one('blur', function() {
                if ($(this).attr('tabindex') === '-1') {
                    $(this).removeAttr('tabindex');
                }
            });
        }

        stopScrolling() {
            this.isScrolling = false;
            $('html, body').stop();
            this.log('Scrolling interrupted');
        }

        // Easing functions
        easing(t) {
            const easingFunctions = {
                linear: t => t,
                easeInQuad: t => t * t,
                easeOutQuad: t => t * (2 - t),
                easeInOutQuad: t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
                easeInCubic: t => t * t * t,
                easeOutCubic: t => (--t) * t * t + 1,
                easeInOutCubic: t => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1,
                easeInQuart: t => t * t * t * t,
                easeOutQuart: t => 1 - (--t) * t * t * t,
                easeInOutQuart: t => t < 0.5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t,
                easeInQuint: t => t * t * t * t * t,
                easeOutQuint: t => 1 + (--t) * t * t * t * t,
                easeInOutQuint: t => t < 0.5 ? 16 * t * t * t * t * t : 1 + 16 * (--t) * t * t * t * t
            };

            const easingFunction = easingFunctions[this.options.easing] || easingFunctions.easeInOutCubic;
            return easingFunction(t);
        }

        // Utility functions
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
                   ('ontouchstart' in window) ||
                   (navigator.maxTouchPoints > 0);
        }

        log(...args) {
            if (this.options.debug) {
                console.log('[SmoothScroll]', ...args);
            }
        }

        // Public API methods
        scrollTo(target, options = {}) {
            const $target = $(target);
            if (!$target.length) {
                this.log('Target not found:', target);
                return;
            }

            const hash = $target.attr('id') ? `#${$target.attr('id')}` : '';
            this.scrollToTarget($target, hash);
        }

        scrollToPosition(position, callback) {
            this.animateScroll(position, callback);
        }

        scrollToTop(callback) {
            this.animateScroll(0, callback);
        }

        enable() {
            this.bindEvents();
            this.log('Smooth scroll enabled');
        }

        disable() {
            this.destroy();
            this.log('Smooth scroll disabled');
        }

        updateOptions(newOptions) {
            this.options = { ...this.options, ...newOptions };
            this.log('Options updated');
        }

        destroy() {
            $(document).off('.smoothScroll');
            $(window).off('.smoothScroll');
            this.stopScrolling();
        }
    }

    // Initialize smooth scroll
    let smoothScrollInstance;

    $(document).ready(function() {
        // Get options from data attributes or global settings
        const options = {
            speed: parseInt($('body').data('smooth-scroll-speed')) || 800,
            easing: $('body').data('smooth-scroll-easing') || 'easeInOutCubic',
            offset: parseInt($('body').data('smooth-scroll-offset')) || 0,
            mobile: $('body').data('smooth-scroll-mobile') !== false,
            debug: $('body').data('smooth-scroll-debug') === true
        };

        // Check if smooth scroll is enabled
        if ($('body').data('smooth-scroll') !== false) {
            smoothScrollInstance = new PratikWPSmoothScroll(options);
        }
    });

    // Expose to global scope
    window.PratikWPSmoothScroll = PratikWPSmoothScroll;
    
    // jQuery plugin
    $.fn.smoothScroll = function(options) {
        if (typeof options === 'string') {
            // Handle method calls
            const method = options;
            const args = Array.prototype.slice.call(arguments, 1);
            
            if (smoothScrollInstance && typeof smoothScrollInstance[method] === 'function') {
                return smoothScrollInstance[method].apply(smoothScrollInstance, args);
            }
        } else {
            // Initialize or update options
            if (smoothScrollInstance) {
                smoothScrollInstance.updateOptions(options);
            } else {
                smoothScrollInstance = new PratikWPSmoothScroll(options);
            }
        }
        
        return this;
    };

    // Global methods
    window.smoothScrollTo = function(target, options) {
        if (smoothScrollInstance) {
            smoothScrollInstance.scrollTo(target, options);
        }
    };

    window.smoothScrollToTop = function(callback) {
        if (smoothScrollInstance) {
            smoothScrollInstance.scrollToTop(callback);
        }
    };

})(jQuery);