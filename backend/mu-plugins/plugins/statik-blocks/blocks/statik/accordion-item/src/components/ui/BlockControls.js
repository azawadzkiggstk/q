import { BlockControls as WPBlockControls } from '@wordpress/block-editor';
import { InnerBlocksControls } from '../../../../accordion/src/components/ui/InnerBlocksControls';
import { useBlockRootClientId } from '@statik-space/wordpress-statik-blocks';

export function BlockControls() {
	const rootClientId = useBlockRootClientId();

	return (
		<WPBlockControls>
			<InnerBlocksControls rootClientId={ rootClientId } />
		</WPBlockControls>
	);
}
