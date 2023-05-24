import {
	useBlockRootClientId,
	useBlockRootAttributes,
	useHasInnerBlocks,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';
import { useSelect } from '@wordpress/data';

export const useSliderSlideState = () => {
	const clientId = useBlockClientId();
	const rootClientId = useBlockRootClientId();

	const { getBlockIndex } = useSelect( 'core/block-editor' );
	const blockIndex = getBlockIndex( clientId, rootClientId );

	const { attributes: parentAttributes } = useBlockRootAttributes();
	const { selectedSlideIndex } = parentAttributes;

	const hasInnerBlocks = useHasInnerBlocks();

	const isSelectedSlide = selectedSlideIndex === blockIndex;

	return {
		isSelectedSlide,
		hasInnerBlocks,
	};
};
