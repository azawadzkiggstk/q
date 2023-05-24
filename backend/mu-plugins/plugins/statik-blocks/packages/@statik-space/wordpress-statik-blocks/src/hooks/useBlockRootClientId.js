import { useBlockEditContext } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

export function useBlockRootClientId( blockClientId ) {
	const { clientId } = useBlockEditContext();
	const finalClientId = blockClientId ?? clientId;

	return useSelect(
		( select ) => {
			const { getBlockRootClientId } = select( 'core/block-editor' );
			return getBlockRootClientId( finalClientId );
		},
		[ finalClientId ]
	);
}
