/* global IntersectionObserver */
import { debounce } from 'lodash';

export default function ( { attributes } ) {
	document.addEventListener( 'DOMContentLoaded', () => {
		new Accordion(
			document.querySelector( `.wp-block-${ attributes.blockId }` ),
			attributes
		).init();
	} );
}

class Accordion {
	constructor( element, attributes ) {
		this.element = element;
		this.openOnlyOne = attributes.openOnlyOne;
		this.items = [
			...element.querySelectorAll( '.wp-block-statik-accordion-item' ),
		].map( ( item ) => new AccordionItem( item, this ) );
	}
	get openedItems() {
		return this.items.filter( ( item ) => item.isOpen );
	}
	init() {
		this.setItemsHeight();
		this.registerItemsEffects();
		this.registerEffects();
	}
	registerItemsEffects() {
		this.items.forEach( ( item ) => item.registerEffects() );
	}
	registerEffects() {
		const onIntersectionChange = ( entries, observer ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					this.refreshItemsHeight();

					// Remove monitoring because proper height already was set
					observer.unobserve( entry.target );
				}
			} );
		};

		const intersectionObserver = new IntersectionObserver(
			onIntersectionChange,
			{
				threshold: 0.5,
			}
		);

		intersectionObserver.observe( this.element );

		window.addEventListener(
			'resize',
			debounce( () => {
				this.refreshItemsHeight();
			}, 50 )
		);
	}
	refreshItemsHeight() {
		this.items.forEach( ( item ) => item.recalculateHeight() );
		this.setItemsHeight();
	}
	setItemsHeight() {
		this.openedItems.forEach( ( item ) => item.setHeight() );
	}
	closeItems( exclude = [] ) {
		this.openedItems.forEach( ( item ) => {
			if ( ! exclude.includes( item ) ) {
				item.close();
			}
		} );
	}
}

class AccordionItem {
	constructor( element, parent ) {
		this.parent = parent;
		this.element = element;
		this.header = element.querySelector(
			'.wp-block-statik-accordion-item__header'
		);
		this.body = element.querySelector(
			'.wp-block-statik-accordion-item__body'
		);
		this.bodyHeight = this.body.querySelector(
			'.wp-block-statik-accordion-item__inner-blocks'
		).offsetHeight;
	}
	get isOpen() {
		return this.header.getAttribute( 'aria-expanded' ) === 'true';
	}
	toggle() {
		this.element.classList.toggle(
			'wp-block-statik-accordion-item--expanded'
		);

		if ( this.isOpen ) {
			this.body.style.height = null;
			this.header.setAttribute( 'aria-expanded', 'false' );
		} else {
			this.setHeight();
			this.header.setAttribute( 'aria-expanded', 'true' );
		}
	}
	recalculateHeight() {
		this.bodyHeight = this.body.querySelector(
			'.wp-block-statik-accordion-item__inner-blocks'
		).offsetHeight;
	}
	setHeight() {
		this.body.style.height = this.bodyHeight + 'px';
	}
	close() {
		this.header.setAttribute( 'aria-expanded', false );
		this.element.classList.remove(
			'wp-block-statik-accordion-item--expanded'
		);
		this.body.style.height = null;
	}
	registerEffects() {
		this.header.addEventListener( 'click', () => {
			if ( this.parent.openOnlyOne ) {
				this.parent.closeItems( [ this ] );
			}

			this.toggle();
		} );

		// Support space and enter controls for WCAG
		this.header.addEventListener( 'keydown', ( e ) => {
			const keyCode = e.which.toString();

			if ( keyCode.match( /13|32/ ) ) {
				e.preventDefault();

				this.toggle();
			}
		} );

		this.body.addEventListener( 'transitionstart', ( event ) => {
			const { target, propertyName } = event;
			if ( target === this.body && propertyName === 'height' ) {
				this.body.style.overflow = 'hidden';
			}
		} );

		this.body.addEventListener( 'transitionend', ( event ) => {
			const { target, propertyName } = event;
			if ( target === this.body && propertyName === 'height' ) {
				this.body.style.overflow = null;
			}
		} );
	}
}
