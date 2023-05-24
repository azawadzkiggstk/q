import { BlockIcon, MediaPlaceholder } from '@wordpress/block-editor';
import { starEmpty } from '@wordpress/icons';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { __ } from '@wordpress/i18n';
import { IconsCollection } from './IconsCollection';

export const Placeholder = () => {
	const { setAttributes } = useBlockAttributes();

	const setIconUrl = ( media ) => {
		setAttributes( {
			id: media.id,
			iconUrl: media.url,
		} );
	};

	return (
		<MediaPlaceholder
			icon={ <BlockIcon icon={ starEmpty } /> }
			labels={ {
				title: __( 'Icon', 'statik-blocks' ),
				instructions: __(
					'Insert an icon to draw attention of your visitors.',
					'statik-blocks'
				),
			} }
			onSelect={ setIconUrl }
			accept="image/svg+xml"
			allowedTypes={ [ 'image/svg+xml' ] }
			disableMediaButtons={ false }
		>
			<IconsCollection />
		</MediaPlaceholder>
	);
};
