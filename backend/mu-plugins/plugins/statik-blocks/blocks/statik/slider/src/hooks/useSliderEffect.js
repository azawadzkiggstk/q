import { useEffect } from '@wordpress/element';
import {
	useInnerBlocks,
	useBlockAttributes,
} from '@statik-space/wordpress-statik-blocks';

export function useSliderEffect() {
	const { setAttributes } = useBlockAttributes();
	const innerBlocks = useInnerBlocks();

	/**
	 * Update `slides` attribute if the InnerBlocks have changed
	 */
	useEffect( () => {
		const slides = innerBlocks.map( ( block ) => {
			const { blockId } = block.attributes;
			return {
				blockId,
			};
		} );

		setAttributes( {
			slides,
		} );
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ innerBlocks ] );
}
