import {
	BlockAlignmentToolbar,
	BlockControls as WPBlockControls,
} from '@wordpress/block-editor';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export function BlockControls() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { labelAlign } = attributes;

	return (
		<WPBlockControls>
			<BlockAlignmentToolbar
				value={ labelAlign }
				onChange={ ( value ) =>
					setAttributes( {
						labelAlign: value,
					} )
				}
				controls={ [ 'left', 'right' ] }
				isCollapsed={ true }
			/>
		</WPBlockControls>
	);
}
