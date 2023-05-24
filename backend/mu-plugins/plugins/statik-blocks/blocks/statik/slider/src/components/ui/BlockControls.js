import { BlockControls as WPBlockControls } from '@wordpress/block-editor';
import { ToolbarGroup } from '@wordpress/components';
import { InnerBlocksControls } from './InnerBlocksControls';
import { useBlockClientId } from '@statik-space/wordpress-statik-blocks';

export function BlockControls() {
	const clientId = useBlockClientId();

	return (
		<WPBlockControls>
			<ToolbarGroup>
				<InnerBlocksControls rootClientId={ clientId } />
			</ToolbarGroup>
		</WPBlockControls>
	);
}
