import {
	Placeholder as WPPlaceholder,
	TextControl,
} from '@wordpress/components';
import { BlockIcon, useBlockProps } from '@wordpress/block-editor';
import { video } from '@wordpress/icons';
import { __ } from '@wordpress/i18n';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { prepareVideoUrl } from '../../utils/prepareVideoUrl';

export function Placeholder() {
	const blockProps = useBlockProps();
	const { attributes, setAttributes } = useBlockAttributes();
	const { url } = attributes;

	const handleChange = ( newUrl ) => {
		const videoUrl = prepareVideoUrl( newUrl );
		if ( videoUrl ) {
			setAttributes( { url: newUrl } );
		}
	};

	return (
		<div { ...blockProps }>
			<WPPlaceholder
				icon={ <BlockIcon icon={ video } /> }
				instructions={ __(
					'Embed a video uploaded to either Youtube or Vimeo.',
					'statik-blocks'
				) }
				label={ __( 'Video', 'statik-blocks' ) }
				isColumnLayout={ false }
			>
				<TextControl
					label={ __( 'Youtube or Vimeo URL', 'statik-blocks' ) }
					value={ url }
					onChange={ handleChange }
					placeholder={ __(
						'https://youtube.com/watch?v=…',
						'statik-blocks'
					) }
				/>
			</WPPlaceholder>
		</div>
	);
}
