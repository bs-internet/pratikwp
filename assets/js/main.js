/*!
 * PratikWP Main JavaScript
 * Frontend functionality and interactions
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // Main theme object
    const PratikWP = {
        
        // Initialize all functions
        init: function() {
            this.stickyHeader();
            this.mobileMenu();
            this.smoothScroll();
            this.lazyLoading();
            this.searchToggle();
            this.backToTop();
            this.accordion();
            this.tabs();
            this.lightbox();
            this.parallax();
            this.animations();
            this.forms();
            this.accessibility();
            this.performance();
        },

        // Sticky Header
        stickyHeader: function() {
            const header = $('.site-header, .elementor-location-header');
            if (!header.length) return;

            let lastScrollTop = 0;
            const headerHeight = header.outerHeight();
            
            $(window).on('scroll', function() {
                const scrollTop = $(this).scrollTop();
                
                if (scrollTop > headerHeight) {
                    header.addClass('sticky-header');
                    
                    // Hide/show header on scroll
                    if (scrollTop > lastScrollTop && scrollTop > headerHeight * 2) {
                        header.addClass('header-hidden');
                    } else {
                        header.removeClass('header-hidden');
                    }
                } else {
                    header.removeClass('sticky-header header-hidden');
                }
                
                lastScrollTop = scrollTop;
            });
        },

        // Mobile Menu
        mobileMenu: function() {
            const mobileToggle = $('.pratikwp-mobile-toggle, .mobile-menu-toggle');
            const mobileMenu = $('.pratikwp-nav-menu.mobile-menu, .mobile-menu');
            const overlay = $('<div class="mobile-menu-overlay"></div>');
            
            if (!mobileToggle.length || !mobileMenu.length) return;

            // Add overlay to body
            $('body').append(overlay);

            // Toggle mobile menu
            mobileToggle.on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('active');
                mobileMenu.toggleClass('active');
                overlay.toggleClass('active');
                $('body').toggleClass('mobile-menu-open');
            });

            // Close menu on overlay click
            overlay.on('click', function() {
                mobileToggle.removeClass('active');
                mobileMenu.removeClass('active');
                overlay.removeClass('active');
                $('body').removeClass('mobile-menu-open');
            });

            // Close menu on escape key
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27 && mobileMenu.hasClass('active')) {
                    mobileToggle.trigger('click');
                }
            });

            // Submenu toggle for mobile
            const submenuToggles = mobileMenu.find('.menu-item-has-children > a');
            submenuToggles.after('<button class="submenu-toggle" aria-label="Toggle submenu"><i class="fa fa-chevron-down"></i></button>');
            
            $('.submenu-toggle').on('click', function(e) {
                e.preventDefault();
                const submenu = $(this).siblings('.sub-menu');
                $(this).toggleClass('active');
                submenu.slideToggle(300);
            });
        },

        // Smooth Scrolling
        smoothScroll: function() {
            $('a[href*="#"]:not([href="#"])').on('click', function(e) {
                const target = $(this.hash);
                if (target.length) {
                    e.preventDefault();
                    const headerOffset = $('.sticky-header').outerHeight() || 0;
                    const targetPosition = target.offset().top - headerOffset - 20;
                    
                    $('html, body').animate({
                        scrollTop: targetPosition
                    }, 800, 'easeInOutCubic');
                }
            });
        },

        // Lazy Loading
        lazyLoading: function() {
            if ('IntersectionObserver' in window) {
                const lazyImageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            const lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;
                            lazyImage.classList.remove('lazy');
                            lazyImage.classList.add('lazy-loaded');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });

                $('.lazy').each(function() {
                    lazyImageObserver.observe(this);
                });
            } else {
                // Fallback for older browsers
                $('.lazy').each(function() {
                    const $img = $(this);
                    $img.attr('src', $img.data('src')).removeClass('lazy').addClass('lazy-loaded');
                });
            }
        },

        // Search Toggle
        searchToggle: function() {
            const searchToggle = $('.search-toggle');
            const searchForm = $('.search-form-popup, .header-search-form');
            
            searchToggle.on('click', function(e) {
                e.preventDefault();
                searchForm.toggleClass('active');
                searchForm.find('input[type="search"]').focus();
            });

            // Close search on escape
            $(document).on('keydown', function(e) {
                if (e.keyCode === 27) {
                    searchForm.removeClass('active');
                }
            });

            // Close search on outside click
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-form-popup, .search-toggle').length) {
                    searchForm.removeClass('active');
                }
            });
        },

        // Back to Top
        backToTop: function() {
            const backToTop = $('<button class="back-to-top" aria-label="Back to top"><i class="fa fa-chevron-up"></i></button>');
            $('body').append(backToTop);

            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    backToTop.addClass('visible');
                } else {
                    backToTop.removeClass('visible');
                }
            });

            backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 800, 'easeInOutCubic');
            });
        },

        // Accordion
        accordion: function() {
            $('.pratikwp-accordion').each(function() {
                const accordion = $(this);
                const items = accordion.find('.accordion-item');
                
                items.each(function() {
                    const header = $(this).find('.accordion-header');
                    const content = $(this).find('.accordion-content');
                    
                    header.on('click', function() {
                        const item = $(this).parent();
                        const isActive = item.hasClass('active');
                        
                        if (accordion.hasClass('single-open')) {
                            items.removeClass('active');
                            items.find('.accordion-content').slideUp(300);
                        }
                        
                        if (!isActive) {
                            item.addClass('active');
                            content.slideDown(300);
                        } else {
                            item.removeClass('active');
                            content.slideUp(300);
                        }
                    });
                });
            });
        },

        // Tabs
        tabs: function() {
            $('.pratikwp-tabs').each(function() {
                const tabsContainer = $(this);
                const tabs = tabsContainer.find('.tab-nav button');
                const panels = tabsContainer.find('.tab-panel');
                
                tabs.on('click', function() {
                    const target = $(this).data('tab');
                    
                    tabs.removeClass('active');
                    $(this).addClass('active');
                    
                    panels.removeClass('active');
                    $('#' + target).addClass('active');
                });
            });
        },

        // Lightbox
        lightbox: function() {
            if ($.fn.magnificPopup) {
                // Image galleries
                $('.gallery').magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1]
                    },
                    image: {
                        titleSrc: 'title',
                        verticalFit: true
                    }
                });

                // Single images
                $('a[href$=".jpg"], a[href$=".jpeg"], a[href$=".png"], a[href$=".gif"]').not('.gallery a').magnificPopup({
                    type: 'image',
                    closeOnContentClick: true,
                    mainClass: 'mfp-img-mobile',
                    image: {
                        verticalFit: true
                    }
                });

                // Videos
                $('.video-popup').magnificPopup({
                    type: 'iframe',
                    mainClass: 'mfp-fade',
                    removalDelay: 160,
                    preloader: false,
                    iframe: {
                        markup: '<div class="mfp-iframe-scaler">' +
                               '<div class="mfp-close"></div>' +
                               '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' +
                               '</div>',
                        patterns: {
                            youtube: {
                                index: 'youtube.com/',
                                id: 'v=',
                                src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                            },
                            vimeo: {
                                index: 'vimeo.com/',
                                id: '/',
                                src: 'https://player.vimeo.com/video/%id%?autoplay=1'
                            }
                        },
                        srcAction: 'iframe_src'
                    }
                });
            }
        },

        // Parallax Effect
        parallax: function() {
            if ($(window).width() > 768) {
                $(window).on('scroll', function() {
                    const scrolled = $(this).scrollTop();
                    const parallaxElements = $('.parallax-element');
                    
                    parallaxElements.each(function() {
                        const element = $(this);
                        const speed = element.data('speed') || 0.5;
                        const yPos = -(scrolled * speed);
                        element.css('transform', `translateY(${yPos}px)`);
                    });
                });
            }
        },

        // Scroll Animations
        animations: function() {
            if ('IntersectionObserver' in window) {
                const animationObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animated');
                            animationObserver.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                });

                $('.animate-on-scroll').each(function() {
                    animationObserver.observe(this);
                });
            }
        },

        // Form Enhancements
        forms: function() {
            // Floating labels
            $('.floating-label input, .floating-label textarea').on('focus blur', function() {
                $(this).parent().toggleClass('focused', this.value !== '' || document.activeElement === this);
            });

            // Form validation
            $('form[data-validate]').on('submit', function(e) {
                const form = $(this);
                let isValid = true;
                
                form.find('[required]').each(function() {
                    const field = $(this);
                    const value = field.val().trim();
                    
                    if (!value) {
                        field.addClass('error');
                        isValid = false;
                    } else {
                        field.removeClass('error');
                    }
                    
                    // Email validation
                    if (field.attr('type') === 'email' && value) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(value)) {
                            field.addClass('error');
                            isValid = false;
                        }
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    form.find('.error').first().focus();
                }
            });

            // File upload preview
            $('input[type="file"]').on('change', function() {
                const file = this.files[0];
                const preview = $(this).siblings('.file-preview');
                
                if (file && preview.length) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (file.type.startsWith('image/')) {
                            preview.html(`<img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px;">`);
                        } else {
                            preview.html(`<p>Selected: ${file.name}</p>`);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        },

        // Accessibility Enhancements
        accessibility: function() {
            // Skip link
            const skipLink = $('<a class="skip-link screen-reader-text" href="#main">Skip to content</a>');
            $('body').prepend(skipLink);

            // Focus management for mobile menu
            $('.mobile-menu-toggle').on('click', function() {
                setTimeout(() => {
                    if ($('.mobile-menu').hasClass('active')) {
                        $('.mobile-menu a').first().focus();
                    }
                }, 100);
            });

            // Keyboard navigation for dropdowns
            $('.menu-item-has-children > a').on('keydown', function(e) {
                if (e.keyCode === 13 || e.keyCode === 32) { // Enter or Space
                    e.preventDefault();
                    $(this).siblings('.sub-menu').toggle();
                }
            });

            // ARIA labels for buttons without text
            $('button:not(:has(text)):not([aria-label])').each(function() {
                const icon = $(this).find('i').attr('class');
                if (icon) {
                    $(this).attr('aria-label', icon.replace(/fa fa-/, '').replace(/-/g, ' '));
                }
            });
        },

        // Performance Optimizations
        performance: function() {
            // Debounce scroll events
            let scrollTimer;
            $(window).on('scroll', function() {
                if (scrollTimer) {
                    clearTimeout(scrollTimer);
                }
                scrollTimer = setTimeout(function() {
                    $(window).trigger('scroll.debounced');
                }, 10);
            });

            // Debounce resize events
            let resizeTimer;
            $(window).on('resize', function() {
                if (resizeTimer) {
                    clearTimeout(resizeTimer);
                }
                resizeTimer = setTimeout(function() {
                    $(window).trigger('resize.debounced');
                }, 100);
            });

            // Preload critical images
            const criticalImages = $('img[data-preload]');
            criticalImages.each(function() {
                const img = new Image();
                img.src = $(this).attr('src');
            });

            // Reduce motion for users who prefer it
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                $('*').css({
                    'animation-duration': '0.01ms !important',
                    'animation-iteration-count': '1 !important',
                    'transition-duration': '0.01ms !important'
                });
            }
        }
    };

    // Custom easing function
    $.easing.easeInOutCubic = function(x) {
        return x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2;
    };

    // Utility functions
    window.PratikWP_Utils = {
        
        // Throttle function
        throttle: function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        // Debounce function
        debounce: function(func, wait, immediate) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    timeout = null;
                    if (!immediate) func(...args);
                };
                const callNow = immediate && !timeout;
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
                if (callNow) func(...args);
            };
        },

        // Check if element is in viewport
        isInViewport: function(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        // Get scroll position
        getScrollPosition: function() {
            return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        },

        // Animate counter
        animateCounter: function(element, start, end, duration) {
            const range = end - start;
            const minTimer = 50;
            const stepTime = Math.abs(Math.floor(duration / range));
            const timer = stepTime < minTimer ? minTimer : stepTime;
            const startTime = new Date().getTime();
            const endTime = startTime + duration;
            
            function run() {
                const now = new Date().getTime();
                const remaining = Math.max((endTime - now) / duration, 0);
                const value = Math.round(end - (remaining * range));
                element.textContent = value;
                
                if (value === end) {
                    clearInterval(timer);
                }
            }
            
            const timer_id = setInterval(run, timer);
            run();
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        PratikWP.init();
    });

    // Re-initialize on Elementor frontend
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
            // Re-run specific functions for Elementor widgets
            PratikWP.animations();
            PratikWP.lightbox();
        });
    });

    // AJAX complete event
    $(document).ajaxComplete(function() {
        PratikWP.lazyLoading();
        PratikWP.animations();
    });

    // Expose PratikWP object globally
    window.PratikWP = PratikWP;

})(jQuery);