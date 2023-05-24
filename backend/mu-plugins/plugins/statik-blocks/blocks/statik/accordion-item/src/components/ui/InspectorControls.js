import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { InspectorControls as WPInspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export function InspectorControls() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { defaultExpanded } = attributes;

	const setDefaultExpanded = () => {
		setAttributes( { defaultExpanded: ! defaultExpanded } );
	};

	return (
		<WPInspectorControls>
			<PanelBody title={ __( 'Settings', 'statik-blocks' ) }>
				<ToggleControl
					label={ __(
						'Display expanded by default',
						'statik-blocks'
					) }
					checked={ !! defaultExpanded }
					onChange={ setDefaultExpanded }
				/>
			</PanelBody>
		</WPInspectorControls>
	);
}
