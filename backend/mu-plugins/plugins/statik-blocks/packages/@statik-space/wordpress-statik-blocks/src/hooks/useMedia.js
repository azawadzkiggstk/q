import { useSelect } from '@wordpress/data';

export function useMedia( mediaId ) {
	return useSelect(
		( select ) => {
			const { getMedia } = select( 'core' );
			return getMedia( mediaId );
		},
		[ mediaId ]
	);
}
