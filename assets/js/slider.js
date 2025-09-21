/* PratikWp Bağımsız Slider Script */
/* v1.0.0 */

document.addEventListener('DOMContentLoaded', () => {
    // Sayfadaki tüm slider'ları bul ve her biri için yeni bir nesne oluştur
    const sliders = document.querySelectorAll('.pratik-slider-container');
    sliders.forEach(sliderElement => {
        new PratikSlider(sliderElement);
    });
});

class PratikSlider {
    constructor(element) {
        // WordPress'ten gelen ayarları al (bkz: slidersettings.php -> wp_localize_script)
        this.options = typeof pratikwpSliderOptions !== 'undefined' ? pratikwpSliderOptions : {
            autoplay: true,
            delay: 5000,
            effect: 'slide'
        };

        // DOM Elementleri
        this.container = element;
        this.track = this.container.querySelector('.pratik-slider-track');
        this.slides = this.container.querySelectorAll('.pratik-slider-slide');
        this.nextButton = this.container.querySelector('.pratik-slider-nav.is-next');
        this.prevButton = this.container.querySelector('.pratik-slider-nav.is-prev');
        this.dotsContainer = this.container.querySelector('.pratik-slider-dots');
        this.dots = this.dotsContainer ? this.dotsContainer.querySelectorAll('.pratik-slider-dot') : [];

        // Durum Değişkenleri
        this.slideCount = this.slides.length;
        this.currentIndex = 0;
        this.autoplayInterval = null;
        this.isDragging = false;
        this.startPos = 0;
        this.currentTranslate = 0;
        this.prevTranslate = 0;

        // Slider'ı başlat
        this.init();
    }

    init() {
        // Eğer 1'den az slayt varsa hiçbir şey yapma
        if (this.slideCount <= 1) {
            if (this.nextButton) this.nextButton.style.display = 'none';
            if (this.prevButton) this.prevButton.style.display = 'none';
            if (this.dotsContainer) this.dotsContainer.style.display = 'none';
            return;
        }

        this.setupEventListeners();

        if (this.options.autoplay) {
            this.startAutoplay();
        }
    }

    setupEventListeners() {
        // Ok butonları
        this.nextButton?.addEventListener('click', () => this.next());
        this.prevButton?.addEventListener('click', () => this.prev());

        // Nokta butonları
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Fare ile üzerine gelince otomatik oynatmayı durdur/başlat
        this.container.addEventListener('mouseenter', () => this.stopAutoplay());
        this.container.addEventListener('mouseleave', () => this.startAutoplay());

        // Dokunmatik (swipe) olayları
        this.container.addEventListener('touchstart', this.touchStart.bind(this), { passive: true });
        this.container.addEventListener('touchmove', this.touchMove.bind(this), { passive: true });
        this.container.addEventListener('touchend', this.touchEnd.bind(this));
    }

    goToSlide(index) {
        // Sınırları kontrol et (döngüsel yapı)
        if (index < 0) {
            index = this.slideCount - 1;
        } else if (index >= this.slideCount) {
            index = 0;
        }
        
        // Geçiş efektini uygula
        if (this.options.effect === 'fade') {
            this.slides[this.currentIndex].classList.remove('active');
            this.slides[index].classList.add('active');
        } else { // 'slide' efekti varsayılan
            this.track.style.transform = `translateX(-${index * 100}%)`;
        }

        // Aktif slayt ve nokta sınıflarını güncelle
        this.slides[this.currentIndex].classList.remove('active');
        this.dots[this.currentIndex]?.classList.remove('active');
        
        this.currentIndex = index;

        this.slides[this.currentIndex].classList.add('active');
        this.dots[this.currentIndex]?.classList.add('active');
    }

    next() {
        this.goToSlide(this.currentIndex + 1);
    }

    prev() {
        this.goToSlide(this.currentIndex - 1);
    }

    startAutoplay() {
        if (!this.options.autoplay || this.autoplayInterval) return;
        this.autoplayInterval = setInterval(() => {
            this.next();
        }, this.options.delay);
    }

    stopAutoplay() {
        clearInterval(this.autoplayInterval);
        this.autoplayInterval = null;
    }

    // --- Dokunmatik Fonksiyonlar ---
    touchStart(event) {
        this.startPos = this.getPositionX(event);
        this.isDragging = true;
        this.stopAutoplay(); // Kaydırırken otomatik oynatmayı durdur
    }

    touchMove(event) {
        if (this.isDragging) {
            const currentPosition = this.getPositionX(event);
            this.currentTranslate = this.prevTranslate + currentPosition - this.startPos;
        }
    }

    touchEnd() {
        this.isDragging = false;
        const movedBy = this.currentTranslate - this.prevTranslate;

        // Belirli bir eşikten fazla kaydırıldıysa slaytı değiştir
        if (movedBy < -50 && this.currentIndex < this.slideCount - 1) {
            this.next();
        } else if (movedBy > 50 && this.currentIndex > 0) {
            this.prev();
        }

        // Reset positions
        this.prevTranslate = this.currentTranslate;
        this.startAutoplay(); // Kaydırma bitince otomatik oynatmayı başlat
    }

    getPositionX(event) {
        return event.type.includes('mouse') ? event.pageX : event.touches[0].clientX;
    }
}