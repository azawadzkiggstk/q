// eslint-disable-next-line import/no-unresolved
import Swiper from 'swiper/bundle';

export default function ( { attributes } ) {
	document.addEventListener( 'DOMContentLoaded', () => {
		new Slider(
			document.querySelector( `.wp-block-${ attributes.blockId }` ),
			attributes
		).init();
	} );
}

class Slider {
	constructor( element, attributes ) {
		this.attributes = attributes;
		this.slidesContainer = element.querySelector(
			'.wp-block-statik-slider__slides-container'
		);
		this.prevButton = element.querySelector(
			'.wp-block-statik-slider__direction-nav-button:first-child'
		);
		this.nextButton = element.querySelector(
			'.wp-block-statik-slider__direction-nav-button:last-child'
		);
		this.pagination = element.querySelector(
			'.wp-block-statik-slider__control-nav'
		);
	}

	init() {
		this.addSwiperCssClasses();

		new Swiper( this.slidesContainer, this.swiperOptions );
	}

	get swiperOptions() {
		const {
			loop,
			autoplay,
			autoHeight,
			interval,
			showControlNav,
			showDirectionNav,
			preSelectedSlide,
		} = this.attributes;

		const autoplayInterval = autoplay ? { delay: interval } : false;

		const navigation = showDirectionNav
			? {
					nextEl: this.nextButton,
					prevEl: this.prevButton,
			  }
			: false;

		const pagination = showControlNav
			? {
					el: this.pagination,
					clickable: true,
					bulletClass: 'wp-block-statik-slider__control-nav-button',
					bulletActiveClass:
						'wp-block-statik-slider__control-nav-button--selected',
			  }
			: false;

		return {
			autoplay: autoplayInterval,
			autoHeight,
			loop,
			initialSlide: preSelectedSlide,
			slideActiveClass: 'wp-block-statik-slider-slide--selected',
			navigation,
			pagination,
			observer: true,
			observeParents: true,
		};
	}

	/**
	 * Add swiper dedicated CSS classes to allow styles from lib to work properly
	 * This method should be removed after the classes will be added to generated HTML(block SAVE)
	 */
	addSwiperCssClasses() {
		this.slidesContainer.classList.add( 'swiper' );
		this.slidesContainer
			.querySelector( '.wp-block-statik-slider__slides' )
			.classList.add( 'swiper-wrapper' );
		this.slidesContainer
			.querySelectorAll( '.wp-block-statik-slider-slide' )
			.forEach( ( element ) => {
				element.classList.add( 'swiper-slide' );
			} );
	}
}
