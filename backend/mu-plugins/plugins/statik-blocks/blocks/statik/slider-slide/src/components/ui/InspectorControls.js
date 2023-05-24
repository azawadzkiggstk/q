import { InspectorControls as WPInspectorControls } from '@wordpress/block-editor';
import { Notice, PanelBody } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export function InspectorControls() {
	return (
		<WPInspectorControls>
			<PanelBody title={ __( 'Settings', 'statik-blocks' ) }>
				<Notice isDismissible={ false } status={ 'info' }>
					{ __(
						'Settings are not available for a single slide. Please select “Slider” block out of a breadcrumb navigation located at the bottom section of Gutenberg.',
						'statik-blocks'
					) }
				</Notice>
			</PanelBody>
		</WPInspectorControls>
	);
}
