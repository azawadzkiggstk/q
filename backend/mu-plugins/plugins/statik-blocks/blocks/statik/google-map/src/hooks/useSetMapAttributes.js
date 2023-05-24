import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { __ } from '@wordpress/i18n';

export const useSetMapAttributes = () => {
	const { setAttributes } = useBlockAttributes();

	return ( url ) => {
		const regex = RegExp( '/@(.*),(.*),(.*)z' );
		const params = url.match( regex );
		if ( params ) {
			const [ , lat, long, zoom ] = params;
			setAttributes( {
				latitude: parseFloat( lat ),
				longitude: parseFloat( long ),
				zoom: parseFloat( zoom ),
			} );
		} else {
			console.error( __( 'Please enter correct URL', 'statik-blocks' ) );
		}
	};
};
