import { useSelect } from '@wordpress/data';

export function usePostTypes( excludedPostTypes = [ 'attachment' ] ) {
	return useSelect(
		( select ) => {
			const { getPostTypes } = select( 'core' );
			const filteredPostTypes = getPostTypes( { per_page: -1 } )?.filter(
				( { viewable, slug } ) =>
					viewable && ! excludedPostTypes.includes( slug )
			);
			return filteredPostTypes;
		},
		[ excludedPostTypes ]
	);
}
