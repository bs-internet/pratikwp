/**
 * PratikWp Main JavaScript - Birleştirilmiş
 * 
 * @package PratikWp
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // Document ready
    $(document).ready(function() {
        initMobileMenu();
        initHeaderSearch();
        initStickyHeader();
        initSmoothScroll();
        initComments();
    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const $toggle = $('.mobile-menu-toggle');
        const $navigation = $('.main-navigation');
        const $body = $('body');

        $toggle.on('click', function(e) {
            e.preventDefault();
            
            $navigation.toggleClass('menu-open');
            $body.toggleClass('menu-open');
            
            // Update aria-expanded
            const isExpanded = $navigation.hasClass('menu-open');
            $toggle.attr('aria-expanded', isExpanded);
            
            // Change icon
            const $icon = $toggle.find('.menu-icon');
            $icon.text(isExpanded ? '✕' : '☰');
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$toggle.is(e.target) && !$navigation.is(e.target) && $navigation.has(e.target).length === 0) {
                $navigation.removeClass('menu-open');
                $body.removeClass('menu-open');
                $toggle.attr('aria-expanded', 'false');
                $toggle.find('.menu-icon').text('☰');
            }
        });

        // Close menu on escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $navigation.hasClass('menu-open')) {
                $navigation.removeClass('menu-open');
                $body.removeClass('menu-open');
                $toggle.attr('aria-expanded', 'false');
                $toggle.find('.menu-icon').text('☰');
            }
        });
    }

    /**
     * Header Search Toggle
     */
    function initHeaderSearch() {
        const $searchToggle = $('.search-toggle');
        const $searchWrapper = $('.search-form-wrapper');
        const $searchInput = $searchWrapper.find('input[type="search"]');

        $searchToggle.on('click', function(e) {
            e.preventDefault();
            
            $searchWrapper.toggleClass('search-open');
            
            // Focus input when opening
            if ($searchWrapper.hasClass('search-open')) {
                setTimeout(function() {
                    $searchInput.focus();
                }, 100);
            }
            
            // Update aria-expanded
            const isExpanded = $searchWrapper.hasClass('search-open');
            $searchToggle.attr('aria-expanded', isExpanded);
        });

        // Close search when clicking outside
        $(document).on('click', function(e) {
            if (!$searchToggle.is(e.target) && !$searchWrapper.is(e.target) && $searchWrapper.has(e.target).length === 0) {
                $searchWrapper.removeClass('search-open');
                $searchToggle.attr('aria-expanded', 'false');
            }
        });

        // Close search on escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $searchWrapper.hasClass('search-open')) {
                $searchWrapper.removeClass('search-open');
                $searchToggle.attr('aria-expanded', 'false');
            }
        });
    }

    /**
     * Sticky Header
     */
    function initStickyHeader() {
        const $header = $('.site-header.header-sticky');
        
        if ($header.length === 0) {
            return;
        }

        const headerOffset = $header.offset().top;
        let isSticky = false;

        $(window).on('scroll', function() {
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > headerOffset && !isSticky) {
                $header.addClass('is-sticky');
                $('body').addClass('has-sticky-header');
                isSticky = true;
            } else if (scrollTop <= headerOffset && isSticky) {
                $header.removeClass('is-sticky');
                $('body').removeClass('has-sticky-header');
                isSticky = false;
            }
        });
    }

    /**
     * Smooth Scroll
     */
    function initSmoothScroll() {
        // Smooth scroll for anchor links
        $('a[href*="#"]:not([href="#"])').on('click', function(e) {
            const href = $(this).attr('href');
            const target = $(href.substring(href.indexOf('#')));
            
            if (target.length) {
                e.preventDefault();
                
                const headerHeight = $('.site-header.header-sticky').outerHeight() || 0;
                const offsetTop = target.offset().top - headerHeight - 20;
                
                $('html, body').animate({
                    scrollTop: offsetTop
                }, 500);
            }
        });
    }

    /**
     * Comment Form Enhancement
     */
    function initComments() {
        const $commentForm = $('#commentform');
        
        if ($commentForm.length === 0) {
            return;
        }

        // Add loading state to submit button
        $commentForm.on('submit', function() {
            const $submitButton = $(this).find('input[type="submit"]');
            const originalText = $submitButton.val();
            
            $submitButton.val('Gönderiliyor...').prop('disabled', true);
            
            // Reset button after 5 seconds (in case of error)
            setTimeout(function() {
                $submitButton.val(originalText).prop('disabled', false);
            }, 5000);
        });

        // Auto-resize comment textarea
        const $commentTextarea = $('#comment');
        if ($commentTextarea.length) {
            $commentTextarea.on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    }

    /**
     * Back to Top Button (Optional)
     */
    function initBackToTop() {
        // Create back to top button
        const $backToTop = $('<button class="back-to-top" aria-label="Yukarı Çık">↑</button>');
        $('body').append($backToTop);

        // Show/hide based on scroll
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 300) {
                $backToTop.addClass('show');
            } else {
                $backToTop.removeClass('show');
            }
        });

        // Scroll to top on click
        $backToTop.on('click', function() {
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });
    }

    initBackToTop();

})(jQuery);