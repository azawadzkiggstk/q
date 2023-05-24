import { BlockControls as WPBlockControls } from '@wordpress/block-editor';
import { InnerBlocksControls } from './InnerBlocksControls';
import { useBlockClientId } from '@statik-space/wordpress-statik-blocks';

export function BlockControls() {
	const clientId = useBlockClientId();

	return (
		<WPBlockControls>
			<InnerBlocksControls rootClientId={ clientId } />
		</WPBlockControls>
	);
}
