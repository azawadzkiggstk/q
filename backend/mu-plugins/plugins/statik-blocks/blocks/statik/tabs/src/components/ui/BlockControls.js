import { BlockControls as WPBlockControls } from '@wordpress/block-editor';
import { ToolbarGroup } from '@wordpress/components';
import { InnerBlocksControls } from './InnerBlocksControls';

export function BlockControls() {
	return (
		<WPBlockControls>
			<ToolbarGroup>
				<InnerBlocksControls />
			</ToolbarGroup>
		</WPBlockControls>
	);
}
