import {
	useBlockAttributes,
	useInnerBlocks,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';
import { useDispatch } from '@wordpress/data';

export const DirectionNavButton = ( props ) => {
	const { children, offset } = props;

	const clientId = useBlockClientId();
	const { attributes, setAttributes } = useBlockAttributes();
	const { selectedSlideIndex, loop } = attributes;
	const { selectBlock } = useDispatch( 'core/block-editor' );
	const blocks = useInnerBlocks( clientId );

	const minSlideIdx = 0;
	const maxSlideIdx = blocks.length - 1;

	const isPrev = loop || selectedSlideIndex > minSlideIdx;
	const isNext = loop || selectedSlideIndex < maxSlideIdx;

	let futureIdx = selectedSlideIndex + offset;

	if ( futureIdx < minSlideIdx && loop ) {
		futureIdx = maxSlideIdx;
	}
	if ( futureIdx > maxSlideIdx && loop ) {
		futureIdx = minSlideIdx;
	}

	const selectFutureSlide = () => {
		setAttributes( { selectedSlideIndex: futureIdx } );
		selectBlock( blocks[ futureIdx ].clientId );
	};

	let isDisabled = false;
	if ( offset > 0 ) {
		isDisabled = ! isNext;
	}
	if ( offset < 0 ) {
		isDisabled = ! isPrev;
	}

	return (
		<button
			className="wp-block-statik-slider__direction-nav-button"
			disabled={ isDisabled }
			onClick={ selectFutureSlide }
		>
			{ children }
		</button>
	);
};
