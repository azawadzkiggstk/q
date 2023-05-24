import { useEffect } from '@wordpress/element';
import {
	useBlockAttributes,
	useBlockRootAttributes,
	useBlockIndex,
	useInnerBlocksIds,
	useBlockRootClientId,
} from '@statik-space/wordpress-statik-blocks';

export const useSliderSlideEffect = () => {
	const { setAttributes } = useBlockAttributes();
	const sliderClientId = useBlockRootClientId();
	const innerBlocksIds = useInnerBlocksIds( sliderClientId );
	const { attributes: sliderAttributes } = useBlockRootAttributes();
	const { preSelectedSlide } = sliderAttributes;
	const blockIndex = useBlockIndex();

	useEffect( () => {
		setAttributes( {
			isPreSelected: Number( blockIndex ) === Number( preSelectedSlide ),
		} );
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ preSelectedSlide ] );

	useEffect( () => {
		setAttributes( {
			slideIndex: blockIndex,
		} );
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ blockIndex ] );

	useEffect( () => {
		setAttributes( {
			slidesCount: innerBlocksIds.length,
		} );
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ innerBlocksIds.length ] );
};
