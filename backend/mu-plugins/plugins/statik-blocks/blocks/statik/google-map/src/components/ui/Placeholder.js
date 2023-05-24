import {
	Placeholder as WPPlaceholder,
	TextControl,
} from '@wordpress/components';
import { BlockIcon, useBlockProps } from '@wordpress/block-editor';
import { mapMarker } from '@wordpress/icons';
import { useSetMapAttributes } from '../../hooks/useSetMapAttributes';
import { __ } from '@wordpress/i18n';

export const Placeholder = () => {
	const blockProps = useBlockProps();
	const handleChangeValue = useSetMapAttributes();

	return (
		<div { ...blockProps }>
			<WPPlaceholder
				icon={ <BlockIcon icon={ mapMarker } /> }
				instructions={ __(
					'Display a map that is either interactive or static.',
					'statik-blocks'
				) }
				label={ __( 'Google Maps URL', 'statik-blocks' ) }
				isColumnLayout={ false }
			>
				<TextControl
					label={ __( 'Google Map URL', 'statik-blocks' ) }
					onChange={ handleChangeValue }
					placeholder={ __(
						'www.google.com/maps/…',
						'statik-blocks'
					) }
					help={ __( 'Please enter correct URL', 'statik-blocks' ) }
				/>
			</WPPlaceholder>
		</div>
	);
};
