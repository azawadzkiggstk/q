import {
	InspectorControls as WPInspectorControls,
	InspectorAdvancedControls,
} from '@wordpress/block-editor';
import {
	Notice,
	PanelBody,
	BaseControl,
	TextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export function InspectorControls() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { tabClassName } = attributes;

	const handleTabClassNameChange = ( value ) => {
		setAttributes( { tabClassName: value } );
	};

	return (
		<>
			<WPInspectorControls>
				<PanelBody title={ __( 'Settings', 'statik-blocks' ) }>
					<Notice isDismissible={ false } status={ 'info' }>
						{ __(
							'Settings are not available for a single tab. Please select “Tabs” block out of a breadcrumb navigation located at the bottom section of Gutenberg.',
							'statik-blocks'
						) }
					</Notice>
				</PanelBody>
			</WPInspectorControls>
			<InspectorAdvancedControls>
				<BaseControl>
					<TextControl
						value={ tabClassName }
						label={ __(
							'Additional Tab CSS class(es)',
							'statik-blocks'
						) }
						onChange={ handleTabClassNameChange }
						help={ __(
							'Separate multiple classes with spaces.',
							'statik-blocks'
						) }
					/>
				</BaseControl>
			</InspectorAdvancedControls>
		</>
	);
}
