/*!
 * PratikWP Slider JavaScript
 * Custom slider functionality for PratikWP theme
 * Version: 1.0.0
 */

(function($) {
    'use strict';

    // PratikWP Slider Class
    class PratikWPSlider {
        constructor(element, options = {}) {
            this.element = $(element);
            this.slides = this.element.find('.slider-slide');
            this.currentSlide = 0;
            this.isPlaying = false;
            this.slideInterval = null;
            this.touchStartX = 0;
            this.touchEndX = 0;
            
            // Default options
            this.options = {
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 800,
                arrows: true,
                dots: true,
                infinite: true,
                fade: false,
                pauseOnHover: true,
                swipe: true,
                responsive: true,
                lazyLoad: true,
                preloadImages: 2,
                accessibility: true,
                keyboard: true,
                rtl: false,
                easing: 'ease-in-out',
                beforeSlideChange: null,
                afterSlideChange: null,
                onInit: null,
                ...options
            };

            this.init();
        }

        init() {
            if (this.slides.length <= 1) return;

            this.setupSlider();
            this.createControls();
            this.bindEvents();
            this.setupAccessibility();
            this.preloadImages();
            
            if (this.options.autoplay) {
                this.startAutoplay();
            }
            
            // Trigger init callback
            if (typeof this.options.onInit === 'function') {
                this.options.onInit.call(this);
            }

            // Set first slide as active
            this.goToSlide(0, false);
        }

        setupSlider() {
            // Add wrapper classes
            this.element.addClass('pratikwp-slider-initialized');
            
            if (this.options.fade) {
                this.element.addClass('slider-fade');
            }
            
            if (this.options.rtl) {
                this.element.addClass('slider-rtl');
            }

            // Setup slides
            this.slides.each((index, slide) => {
                const $slide = $(slide);
                $slide.attr({
                    'data-slide-index': index,
                    'aria-hidden': index !== 0 ? 'true' : 'false'
                });

                if (index === 0) {
                    $slide.addClass('active');
                }
            });

            // Set slider height for responsive
            if (this.options.responsive) {
                this.setResponsiveHeight();
            }
        }

        createControls() {
            const controlsContainer = $('<div class="slider-controls"></div>');
            
            // Create navigation arrows
            if (this.options.arrows) {
                const prevArrow = $('<button class="slider-arrow slider-prev" aria-label="Previous slide"><i class="fa fa-chevron-left"></i></button>');
                const nextArrow = $('<button class="slider-arrow slider-next" aria-label="Next slide"><i class="fa fa-chevron-right"></i></button>');
                
                controlsContainer.append(prevArrow, nextArrow);
            }

            // Create dots navigation
            if (this.options.dots) {
                const dotsContainer = $('<div class="slider-dots" role="tablist"></div>');
                
                this.slides.each((index) => {
                    const dot = $(`<button class="slider-dot" data-slide="${index}" role="tab" aria-label="Go to slide ${index + 1}">${index + 1}</button>`);
                    if (index === 0) {
                        dot.addClass('active').attr('aria-selected', 'true');
                    }
                    dotsContainer.append(dot);
                });
                
                controlsContainer.append(dotsContainer);
            }

            // Create play/pause button
            if (this.options.autoplay) {
                const playPauseBtn = $('<button class="slider-play-pause" aria-label="Pause slideshow"><i class="fa fa-pause"></i></button>');
                controlsContainer.append(playPauseBtn);
            }

            this.element.append(controlsContainer);
        }

        bindEvents() {
            const self = this;

            // Arrow navigation
            this.element.on('click', '.slider-prev', () => {
                this.prevSlide();
            });

            this.element.on('click', '.slider-next', () => {
                this.nextSlide();
            });

            // Dots navigation
            this.element.on('click', '.slider-dot', function() {
                const slideIndex = parseInt($(this).data('slide'));
                self.goToSlide(slideIndex);
            });

            // Play/Pause button
            this.element.on('click', '.slider-play-pause', () => {
                this.toggleAutoplay();
            });

            // Pause on hover
            if (this.options.pauseOnHover) {
                this.element.on('mouseenter', () => {
                    this.pauseAutoplay();
                });

                this.element.on('mouseleave', () => {
                    if (this.options.autoplay) {
                        this.startAutoplay();
                    }
                });
            }

            // Keyboard navigation
            if (this.options.keyboard) {
                $(document).on('keydown', (e) => {
                    if (this.element.is(':focus-within')) {
                        if (e.keyCode === 37) { // Left arrow
                            e.preventDefault();
                            this.prevSlide();
                        } else if (e.keyCode === 39) { // Right arrow
                            e.preventDefault();
                            this.nextSlide();
                        }
                    }
                });
            }

            // Touch/Swipe events
            if (this.options.swipe && 'ontouchstart' in window) {
                this.element.on('touchstart', (e) => {
                    this.touchStartX = e.originalEvent.touches[0].clientX;
                });

                this.element.on('touchend', (e) => {
                    this.touchEndX = e.originalEvent.changedTouches[0].clientX;
                    this.handleSwipe();
                });
            }

            // Mouse drag events for desktop
            if (this.options.swipe) {
                let isDragging = false;
                let startX = 0;

                this.element.on('mousedown', (e) => {
                    isDragging = true;
                    startX = e.clientX;
                    this.element.css('cursor', 'grabbing');
                });

                $(document).on('mousemove', (e) => {
                    if (!isDragging) return;
                    e.preventDefault();
                });

                $(document).on('mouseup', (e) => {
                    if (!isDragging) return;
                    isDragging = false;
                    this.element.css('cursor', '');
                    
                    const endX = e.clientX;
                    const diff = startX - endX;
                    
                    if (Math.abs(diff) > 50) {
                        if (diff > 0) {
                            this.nextSlide();
                        } else {
                            this.prevSlide();
                        }
                    }
                });
            }

            // Window resize
            $(window).on('resize.pratikwpSlider', () => {
                if (this.options.responsive) {
                    this.setResponsiveHeight();
                }
            });

            // Visibility change (pause when tab is hidden)
            $(document).on('visibilitychange', () => {
                if (document.hidden) {
                    this.pauseAutoplay();
                } else if (this.options.autoplay) {
                    this.startAutoplay();
                }
            });
        }

        setupAccessibility() {
            if (!this.options.accessibility) return;

            // Add ARIA attributes
            this.element.attr({
                'role': 'region',
                'aria-label': 'Image slider',
                'aria-live': 'polite'
            });

            // Make slides focusable
            this.slides.attr('tabindex', '-1');
            this.slides.eq(0).attr('tabindex', '0');

            // Add screen reader announcements
            const announcement = $('<div class="sr-only slider-announcement" aria-live="polite"></div>');
            this.element.append(announcement);
        }

        preloadImages() {
            if (!this.options.lazyLoad) return;

            const preloadCount = Math.min(this.options.preloadImages, this.slides.length);
            
            for (let i = 0; i < preloadCount; i++) {
                this.loadSlideImages(i);
            }
        }

        loadSlideImages(slideIndex) {
            const slide = this.slides.eq(slideIndex);
            const images = slide.find('img[data-src]');
            
            images.each(function() {
                const img = $(this);
                const src = img.data('src');
                
                if (src) {
                    img.attr('src', src).removeAttr('data-src');
                    img.on('load', function() {
                        $(this).addClass('loaded');
                    });
                }
            });
        }

        setResponsiveHeight() {
            const activeSlide = this.slides.filter('.active');
            const img = activeSlide.find('img').first();
            
            if (img.length) {
                const aspectRatio = img.data('aspect-ratio') || (img[0].naturalHeight / img[0].naturalWidth);
                const containerWidth = this.element.width();
                const newHeight = containerWidth * aspectRatio;
                
                this.element.css('height', newHeight + 'px');
            }
        }

        handleSwipe() {
            const swipeThreshold = 50;
            const diff = this.touchStartX - this.touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
        }

        goToSlide(slideIndex, animate = true) {
            if (slideIndex === this.currentSlide) return;

            const totalSlides = this.slides.length;
            
            // Handle infinite loop
            if (this.options.infinite) {
                if (slideIndex >= totalSlides) {
                    slideIndex = 0;
                } else if (slideIndex < 0) {
                    slideIndex = totalSlides - 1;
                }
            } else {
                slideIndex = Math.max(0, Math.min(slideIndex, totalSlides - 1));
            }

            // Before slide change callback
            if (typeof this.options.beforeSlideChange === 'function') {
                this.options.beforeSlideChange.call(this, this.currentSlide, slideIndex);
            }

            const currentSlideEl = this.slides.eq(this.currentSlide);
            const nextSlideEl = this.slides.eq(slideIndex);

            // Update active states
            this.slides.removeClass('active').attr('aria-hidden', 'true');
            nextSlideEl.addClass('active').attr('aria-hidden', 'false');

            // Update dots
            this.element.find('.slider-dot').removeClass('active').attr('aria-selected', 'false');
            this.element.find(`.slider-dot[data-slide="${slideIndex}"]`).addClass('active').attr('aria-selected', 'true');

            // Handle animation
            if (animate) {
                if (this.options.fade) {
                    this.fadeTransition(currentSlideEl, nextSlideEl);
                } else {
                    this.slideTransition(slideIndex);
                }
            }

            // Load images for next slides
            if (this.options.lazyLoad) {
                this.loadSlideImages(slideIndex);
                
                // Preload next slide
                const nextIndex = slideIndex + 1 >= totalSlides ? 0 : slideIndex + 1;
                this.loadSlideImages(nextIndex);
            }

            // Update current slide
            this.currentSlide = slideIndex;

            // Update accessibility
            if (this.options.accessibility) {
                this.updateAccessibility();
            }

            // After slide change callback
            if (typeof this.options.afterSlideChange === 'function') {
                setTimeout(() => {
                    this.options.afterSlideChange.call(this, slideIndex);
                }, this.options.speed);
            }
        }

        fadeTransition(currentSlide, nextSlide) {
            currentSlide.css({
                'opacity': 1,
                'z-index': 1
            }).animate({
                'opacity': 0
            }, this.options.speed, this.options.easing);

            nextSlide.css({
                'opacity': 0,
                'z-index': 2
            }).animate({
                'opacity': 1
            }, this.options.speed, this.options.easing);
        }

        slideTransition(slideIndex) {
            const slideWidth = this.element.width();
            const translateX = this.options.rtl ? slideIndex * slideWidth : -slideIndex * slideWidth;
            
            this.slides.parent().css({
                'transform': `translateX(${translateX}px)`,
                'transition': `transform ${this.options.speed}ms ${this.options.easing}`
            });
        }

        updateAccessibility() {
            const announcement = this.element.find('.slider-announcement');
            const currentSlideNumber = this.currentSlide + 1;
            const totalSlides = this.slides.length;
            
            announcement.text(`Slide ${currentSlideNumber} of ${totalSlides}`);
            
            // Update focusable slides
            this.slides.attr('tabindex', '-1');
            this.slides.eq(this.currentSlide).attr('tabindex', '0');
        }

        nextSlide() {
            const nextIndex = this.currentSlide + 1;
            this.goToSlide(nextIndex);
        }

        prevSlide() {
            const prevIndex = this.currentSlide - 1;
            this.goToSlide(prevIndex);
        }

        startAutoplay() {
            if (this.slideInterval) {
                clearInterval(this.slideInterval);
            }
            
            this.isPlaying = true;
            this.slideInterval = setInterval(() => {
                this.nextSlide();
            }, this.options.autoplaySpeed);

            // Update play/pause button
            this.element.find('.slider-play-pause')
                .attr('aria-label', 'Pause slideshow')
                .html('<i class="fa fa-pause"></i>');
        }

        pauseAutoplay() {
            if (this.slideInterval) {
                clearInterval(this.slideInterval);
                this.slideInterval = null;
            }
            
            this.isPlaying = false;

            // Update play/pause button
            this.element.find('.slider-play-pause')
                .attr('aria-label', 'Play slideshow')
                .html('<i class="fa fa-play"></i>');
        }

        toggleAutoplay() {
            if (this.isPlaying) {
                this.pauseAutoplay();
            } else {
                this.startAutoplay();
            }
        }

        destroy() {
            // Clear intervals
            if (this.slideInterval) {
                clearInterval(this.slideInterval);
            }

            // Remove event listeners
            this.element.off('.pratikwpSlider');
            $(window).off('resize.pratikwpSlider');
            $(document).off('keydown.pratikwpSlider');

            // Remove added elements
            this.element.find('.slider-controls').remove();
            this.element.find('.slider-announcement').remove();

            // Reset classes and attributes
            this.element.removeClass('pratikwp-slider-initialized slider-fade slider-rtl');
            this.slides.removeClass('active').removeAttr('data-slide-index aria-hidden tabindex');

            // Reset styles
            this.element.removeAttr('style');
            this.slides.removeAttr('style');
        }

        // Public API methods
        goTo(slideIndex) {
            this.goToSlide(slideIndex);
        }

        play() {
            this.options.autoplay = true;
            this.startAutoplay();
        }

        pause() {
            this.pauseAutoplay();
        }

        next() {
            this.nextSlide();
        }

        prev() {
            this.prevSlide();
        }

        getCurrentSlide() {
            return this.currentSlide;
        }

        getTotalSlides() {
            return this.slides.length;
        }

        updateOptions(newOptions) {
            this.options = { ...this.options, ...newOptions };
            this.destroy();
            this.init();
        }
    }

    // jQuery plugin
    $.fn.pratikwpSlider = function(options) {
        return this.each(function() {
            const $element = $(this);
            
            // Prevent multiple initializations
            if ($element.data('pratikwpSlider')) {
                return;
            }
            
            const slider = new PratikWPSlider(this, options);
            $element.data('pratikwpSlider', slider);
        });
    };

    // Auto-initialize sliders
    $(document).ready(function() {
        $('.pratikwp-slider').each(function() {
            const $slider = $(this);
            const options = $slider.data('slider-options') || {};
            
            $slider.pratikwpSlider(options);
        });
    });

    // Reinitialize on AJAX complete (for dynamic content)
    $(document).ajaxComplete(function() {
        $('.pratikwp-slider:not(.pratikwp-slider-initialized)').each(function() {
            const $slider = $(this);
            const options = $slider.data('slider-options') || {};
            
            $slider.pratikwpSlider(options);
        });
    });

    // Expose class globally
    window.PratikWPSlider = PratikWPSlider;

})(jQuery);