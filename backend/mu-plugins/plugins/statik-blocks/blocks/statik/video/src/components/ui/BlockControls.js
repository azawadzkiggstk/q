import {
	MediaReplaceFlow,
	BlockControls as WPBlockControls,
} from '@wordpress/block-editor';
import {
	ReplaceUrlControl,
	useBlockAttributes,
} from '@statik-space/wordpress-statik-blocks';

export function BlockControls() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { hasOverlay, url, overlayImage } = attributes;

	const onSelectUrl = ( value ) => {
		setAttributes( { url: value } );
	};

	const onSelectOverlay = ( media ) => {
		if ( media && media.url ) {
			setAttributes( {
				overlayImage: media.url,
			} );
		}
	};

	return (
		<WPBlockControls group="other">
			<ReplaceUrlControl mediaURL={ url } onSelectURL={ onSelectUrl } />
			{ hasOverlay && (
				<MediaReplaceFlow
					name={ 'Replace Overlay' }
					mediaURL={ overlayImage }
					onSelect={ onSelectOverlay }
					accept="image/*"
					allowedTypes={ [ 'image' ] }
				/>
			) }
		</WPBlockControls>
	);
}
