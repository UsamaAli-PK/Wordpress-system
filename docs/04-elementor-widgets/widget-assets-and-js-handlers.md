---
topic: Widget Scoped Assets and Frontend JS Handlers
source_url: https://developers.elementor.com/docs/widgets/widget-dependencies
last_synced: 2026-11-20
---

# Widget Scoped Assets and Frontend JavaScript Handlers

## 1. Scoped Dependency Enqueueing

Instead of globally enqueuing CSS and JS files on every WordPress page load, modern Elementor widgets declare scoped dependencies using `get_script_depends()` and `get_style_depends()`. Elementor scans the page layout tree and enqueues **only** the assets required by widgets present on that specific page.

### Registering Assets (`functions.php` or Addon Bootstrap)

```php
declare(strict_types=1);

namespace Agency\Addons;

add_action( 'wp_enqueue_scripts', static function(): void {
    // 1. Register third-party library
    wp_register_script(
        'swiper-bundle',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        [],
        '11.1.0',
        true
    );

    // 2. Register widget frontend handler script (depends on elementor-frontend and swiper)
    wp_register_script(
        'agency-carousel-handler',
        plugins_url( 'assets/js/carousel-handler.js', __DIR__ ),
        [ 'elementor-frontend', 'swiper-bundle' ],
        '1.0.0',
        true
    );

    // 3. Register widget stylesheet
    wp_register_style(
        'agency-carousel-style',
        plugins_url( 'assets/css/carousel.css', __DIR__ ),
        [],
        '1.0.0'
    );
} );
```

### Declaring Dependencies in Widget Class

```php
namespace Agency\Addons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

final class CarouselWidget extends Widget_Base {
    public function get_script_depends(): array {
        return [ 'agency-carousel-handler' ];
    }

    public function get_style_depends(): array {
        return [ 'agency-carousel-style' ];
    }

    protected function register_controls(): void {
        $this->start_controls_section( 'section_carousel_settings', [
            'label' => esc_html__( 'Carousel Settings', 'agency-addons' ),
            'tab'   => Controls_Manager::TAB_CONTENT,
        ] );

        // Expose control value directly to frontend JS
        $this->add_control(
            'slides_to_show',
            [
                'label'              => esc_html__( 'Slides per View', 'agency-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 3,
                'min'                => 1,
                'max'                => 6,
                'frontend_available' => true, // Accessible in JS via getElementSettings()
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'              => esc_html__( 'Autoplay Delay (ms)', 'agency-addons' ),
                'type'               => Controls_Manager::NUMBER,
                'default'            => 3000,
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }
}
```

---

## 2. Modern ES6 Frontend Handler (`carousel-handler.js`)

Elementor provides `elementorModules.frontend.handlers.Base`. By extending this class, your JS handler:
1. Automatically mounts when the page renders.
2. Re-initializes seamlessly inside the Elementor editor preview iframe when controls change, without breaking event listeners.

```javascript
/**
 * Modern Elementor Frontend Handler for Agency Carousel.
 */
class AgencyCarouselHandler extends elementorModules.frontend.handlers.Base {
    /**
     * Map internal DOM element references.
     */
    getDefaultElements() {
        return {
            $container: this.$element.find( '.agency-swiper-container' ),
            $wrapper: this.$element.find( '.swiper-wrapper' ),
            $pagination: this.$element.find( '.swiper-pagination' ),
            $nextButton: this.$element.find( '.swiper-button-next' ),
            $prevButton: this.$element.find( '.swiper-button-prev' ),
        };
    }

    /**
     * Component Lifecycle: Initialized.
     */
    onInit() {
        super.onInit();
        this.initSwiper();
    }

    /**
     * Initialize Swiper instance using exposed settings.
     */
    initSwiper() {
        if ( ! this.elements.$container.length ) {
            return;
        }

        // Destroy existing instance before re-initializing (prevents memory leaks in Editor)
        if ( this.swiperInstance ) {
            this.swiperInstance.destroy( true, true );
        }

        const slidesPerView = this.getElementSettings( 'slides_to_show' ) || 3;
        const autoplayDelay = this.getElementSettings( 'autoplay_speed' ) || 3000;

        this.swiperInstance = new Swiper( this.elements.$container[0], {
            slidesPerView: parseInt( slidesPerView, 10 ),
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: parseInt( autoplayDelay, 10 ),
                disableOnInteraction: false,
            },
            pagination: {
                el: this.elements.$pagination[0],
                clickable: true,
            },
            navigation: {
                nextEl: this.elements.$nextButton[0],
                prevEl: this.elements.$prevButton[0],
            },
        } );
    }

    /**
     * Real-Time Editor Change Observer: Runs when a user edits a control in the sidebar.
     *
     * @param {string} propertyName Control key modified.
     */
    onElementChange( propertyName ) {
        if ( 'slides_to_show' === propertyName || 'autoplay_speed' === propertyName ) {
            this.initSwiper();
        }
    }
}

/**
 * Hook into Elementor Frontend Initialization
 */
jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( AgencyCarouselHandler, {
            $element,
        } );
    };

    // Attach handler specifically to our widget slug
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/agency_carousel.default',
        addHandler
    );
} );
```
