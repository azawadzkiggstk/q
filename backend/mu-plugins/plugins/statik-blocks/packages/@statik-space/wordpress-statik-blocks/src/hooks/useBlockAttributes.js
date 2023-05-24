import { useBlockEditContext } from '@wordpress/block-editor';
import { useSelect, useDispatch } from '@wordpress/data';

export function useBlockAttributes( blockClientId ) {
	const { clientId } = useBlockEditContext();
	const finalClientId = blockClientId ?? clientId;

	const { updateBlockAttributes } = useDispatch( 'core/block-editor' );

	const attributes = useSelect(
		( select ) => {
			const { getBlockAttributes } = select( 'core/block-editor' );
			return getBlockAttributes( finalClientId );
		},
		[ finalClientId ]
	);

	const setAttributes = ( newAttributes ) => {
		updateBlockAttributes( finalClientId, newAttributes );
	};

	return {
		attributes,
		setAttributes,
	};
}
