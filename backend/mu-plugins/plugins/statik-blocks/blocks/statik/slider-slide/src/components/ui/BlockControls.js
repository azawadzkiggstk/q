import { BlockControls as WPBlockControls } from '@wordpress/block-editor';
import { ToolbarGroup } from '@wordpress/components';
import { InnerBlocksControls } from '../../../../slider/src/components/ui/InnerBlocksControls';
import { useBlockRootClientId } from '@statik-space/wordpress-statik-blocks';

export function BlockControls() {
	const rootClientId = useBlockRootClientId();

	return (
		<WPBlockControls>
			<ToolbarGroup>
				<InnerBlocksControls rootClientId={ rootClientId } />
			</ToolbarGroup>
		</WPBlockControls>
	);
}
