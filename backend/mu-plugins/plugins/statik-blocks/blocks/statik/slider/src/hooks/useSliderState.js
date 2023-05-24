import {
	useBlockAttributes,
	useInnerBlocksIds,
} from '@statik-space/wordpress-statik-blocks';
import { useEffect, useState } from '@wordpress/element';

export const useSliderState = () => {
	const { attributes, setAttributes } = useBlockAttributes();
	const { selectedSlideIndex, showDirectionNav, showControlNav, autoHeight } =
		attributes;

	const innerBlocksIds = useInnerBlocksIds();
	const hasInnerBlocks = innerBlocksIds.length > 0;

	const [ selectedSlideId, setSelectedSlideId ] = useState(
		() => innerBlocksIds[ selectedSlideIndex ]
	);

	/**
	 *  Update `selectedSlideId` if  `selectedSlideIndex` has changed
	 */
	useEffect( () => {
		if ( hasInnerBlocks ) {
			setSelectedSlideId( innerBlocksIds[ selectedSlideIndex ] );
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ selectedSlideIndex ] );

	/**
	 * Update `selectedSlideIndex` if the InnerBlocks order or length has changed
	 * (to point to the same slide which was set previously)
	 */
	useEffect( () => {
		if ( hasInnerBlocks && innerBlocksIds.includes( selectedSlideId ) ) {
			setAttributes( {
				selectedSlideIndex: innerBlocksIds.indexOf( selectedSlideId ),
			} );
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ innerBlocksIds ] );

	return {
		showDirectionNav,
		showControlNav,
		autoHeight,
	};
};
