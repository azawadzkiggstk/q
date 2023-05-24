export default function ( { attributes } ) {
	document.addEventListener( 'DOMContentLoaded', () => {
		new Tabs(
			document.querySelector( `.wp-block-${ attributes.blockId }` )
		).init();
	} );
}

class Tabs {
	constructor( element ) {
		this.element = element;
		this.navList = element.querySelector(
			'.wp-block-statik-tabs__nav-list'
		);
		this.items = [
			...this.navList.querySelectorAll(
				'.wp-block-statik-tabs__nav-list-item'
			),
		].map( ( item ) => new Tab( item, this ) );
	}
	init() {
		this.registerItemsEffects();
	}
	closeOpenedItem() {
		this.openedItem.toggle();
	}
	openNextItem() {
		if ( this.openedIndex < this.items.length - 1 ) {
			this.openItem( this.openedIndex + 1 );
		}
	}
	openPreviousItem() {
		if ( this.openedIndex > 0 ) {
			this.openItem( this.openedIndex - 1 );
		}
	}
	openItem( index ) {
		this.items[ this.openedIndex ].toggle();
		this.items[ index ].toggle();
	}
	get openedIndex() {
		return this.items.findIndex( ( item ) => item.isOpen );
	}
	get openedItem() {
		return this.items.find( ( item ) => item.isOpen );
	}
	registerItemsEffects() {
		this.items.forEach( ( item ) => item.registerEffects() );
	}
}

class Tab {
	constructor( element, parent ) {
		this.parent = parent;
		this.element = element;
		this.navButton = element.querySelector(
			'.wp-block-statik-tabs__nav-item'
		);
		this.contentWrapper = parent.element.querySelector(
			`#${ this.navButton.getAttribute( 'aria-controls' ) }`
		);
	}
	registerEffects() {
		this.element.addEventListener( 'click', () => {
			this.parent.closeOpenedItem();
			this.toggle();
		} );

		// Support left and right arrows controls for WCAG
		this.navButton.addEventListener( 'keydown', ( e ) => {
			const keyCode = e.which.toString();

			if ( keyCode === '37' ) {
				e.preventDefault();
				this.parent.openPreviousItem();
			}

			if ( keyCode === '39' ) {
				e.preventDefault();
				this.parent.openNextItem();
			}
		} );
	}
	toggle() {
		this.contentWrapper.classList.toggle( 'wp-block-statik-tab--selected' );
		this.element.classList.toggle(
			'wp-block-statik-tabs__nav-list-item--selected'
		);

		if ( this.isOpen ) {
			this.navButton.setAttribute( 'aria-selected', false );
			this.navButton.setAttribute( 'tabindex', -1 );
		} else {
			this.navButton.setAttribute( 'aria-selected', true );
			this.navButton.setAttribute( 'tabindex', 0 );
		}
	}
	get isOpen() {
		return this.navButton.getAttribute( 'aria-selected' ) === 'true';
	}
}
