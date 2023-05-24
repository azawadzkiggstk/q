import { useBlockEditContext } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

export function useHasInnerBlocks( blockClientId ) {
	const { clientId } = useBlockEditContext();
	const finalClientId = blockClientId ?? clientId;

	return useSelect(
		( select ) => {
			const { getBlockOrder } = select( 'core/block-editor' );
			return getBlockOrder( finalClientId ).length > 0;
		},
		[ finalClientId ]
	);
}
