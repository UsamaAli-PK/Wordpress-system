/**
 * Sample Elementor Widget Frontend Handler
 *
 * Extends modern Elementor ES6 Base Handler class.
 * Handles interactive behaviors, micro-interactions, and live editor re-init.
 */
class AgencySampleWidgetHandler extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                card: '.agency-card',
                title: '.agency-card__title',
                listItems: '.agency-card__list-item',
            },
            classes: {
                hovered: 'agency-card--hovered',
            },
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $card: this.$element.find(selectors.card),
            $title: this.$element.find(selectors.title),
            $listItems: this.$element.find(selectors.listItems),
        };
    }

    bindEvents() {
        this.elements.$card.on('mouseenter', this.onMouseEnter.bind(this));
        this.elements.$card.on('mouseleave', this.onMouseLeave.bind(this));
    }

    onMouseEnter() {
        this.elements.$card.addClass(this.getSettings('classes.hovered'));
    }

    onMouseLeave() {
        this.elements.$card.removeClass(this.getSettings('classes.hovered'));
    }

    /**
     * Executes when widget settings change live in the Elementor Editor panel.
     */
    onElementChange(propertyName) {
        if (propertyName.startsWith('card_') || propertyName === 'features_list') {
            // Re-bind elements or re-calculate layout bounds
            this.elements.$card = this.$element.find(this.getSettings('selectors.card'));
        }
    }
}

jQuery(window).on('elementor/frontend/init', () => {
    elementorFrontend.elementsHandler.attachHandler('agency_feature_card', AgencySampleWidgetHandler);
});
