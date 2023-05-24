import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import clsx from 'clsx';
import { ArrowRight } from './components/icons/ArrowRight';
import { ArrowLeft } from './components/icons/ArrowLeft';

export default function SliderSave( props ) {
	const { attributes } = props;

	const { blockId, showDirectionNav, showControlNav, autoHeight } =
		attributes;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			'wp-block-statik-slider--auto-height': Boolean( autoHeight ),
		} ),
		'aria-roledescription': 'carousel',
		'aria-label': 'Slideshow',
	} );

	const innerBlocksProps = useInnerBlocksProps.save( {
		id: `wp-block-${ blockId }-slides`,
		className: 'wp-block-statik-slider__slides',
		'aria-live': 'off',
	} );

	return (
		<div { ...blockProps }>
			<div className="wp-block-statik-slider__slides-container">
				<div { ...innerBlocksProps } />
			</div>
			{ showDirectionNav && <DirectionNav attributes={ attributes } /> }
			{ showControlNav && <ControlNav attributes={ attributes } /> }
		</div>
	);
}

const DirectionNav = ( props ) => {
	const { attributes } = props;
	const { blockId, loop, preSelectedSlide, slides } = attributes;

	const isPrevDisabled =
		! Boolean( loop ) && Number( preSelectedSlide ) === 0;
	const isNextDisabled =
		! Boolean( loop ) && Number( preSelectedSlide ) === slides.length - 1;

	const blockSlidesId = `wp-block-${ blockId }-slides`;

	return (
		<div className="wp-block-statik-slider__direction-nav">
			<button
				className="wp-block-statik-slider__direction-nav-button"
				disabled={ isPrevDisabled }
				aria-controls={ blockSlidesId }
				aria-label="Previous Slide"
			>
				<ArrowLeft />
			</button>
			<button
				className="wp-block-statik-slider__direction-nav-button"
				disabled={ isNextDisabled }
				aria-controls={ blockSlidesId }
				aria-label="Next Slide"
			>
				<ArrowRight />
			</button>
		</div>
	);
};

const ControlNav = ( props ) => {
	const { attributes } = props;
	const { blockId, slides, preSelectedSlide } = attributes;

	return (
		<div className="wp-block-statik-slider__control-nav">
			{ slides.map( ( slide, index ) => {
				const isSlideSelected = preSelectedSlide === index;
				return (
					<button
						key={ slide.blockId }
						className={ clsx(
							'wp-block-statik-slider__control-nav-button',
							{
								'wp-block-statik-slider__control-nav-button--selected':
									isSlideSelected,
							}
						) }
						aria-controls={ `wp-block-${ blockId }-slides` }
						aria-label={ `Go to the Slide ${ index + 1 }` }
					/>
				);
			} ) }
		</div>
	);
};
