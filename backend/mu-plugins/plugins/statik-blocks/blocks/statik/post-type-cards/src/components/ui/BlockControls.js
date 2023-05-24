import { BlockControls as WPBlockControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { ToolbarButton } from '@wordpress/components';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

const label = __( 'Reset Post Type Selection', 'statik-blocks' );

export function BlockControls() {
	const { setAttributes } = useBlockAttributes();

	const resetPostType = () => {
		setAttributes( {
			postType: undefined,
		} );
	};

	return (
		<WPBlockControls>
			<ToolbarButton label={ label } onClick={ resetPostType }>
				<span>{ label }</span>
			</ToolbarButton>
		</WPBlockControls>
	);
}
