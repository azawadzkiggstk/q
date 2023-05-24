import {
	Panel,
	PanelBody,
	PanelRow,
	Button,
	BaseControl,
} from '@wordpress/components';
import { select, dispatch, useSelect } from '@wordpress/data';
import { isNil } from 'lodash';
import {
	hasBlockIdAttributeDefinition,
	getBlockId as getFreshBlockId,
} from '@statik-space/wordpress-statik-blocks';
import { replaceBlockIdClassName } from '../../../utils/replace-block-id-class-name';

export function BlocksManagementPanel() {
	const { getEditorMode } = useSelect( 'core/edit-post' );

	return (
		<Panel>
			<PanelBody title="Blocks Management" initialOpen={ true }>
				<PanelRow>
					<BaseControl
						help="Goes through the all blocks and regenerates the `blockId` and the `wp-block-<blockId>` classname. Available only in `visual` editor mode."
						hideLabelFromVision={ true }
					>
						<Button
							variant="secondary"
							onClick={ regenerateBlocksIds }
							style={ { display: 'block', width: '100%' } }
							disabled={ getEditorMode() !== 'visual' }
						>
							Regenerate Blocks IDs
						</Button>
					</BaseControl>
				</PanelRow>
			</PanelBody>
		</Panel>
	);
}

function regenerateBlocksIds() {
	const { getBlockName, getBlockAttributes, getClientIdsWithDescendants } =
		select( 'core/block-editor' );
	const { updateBlockAttributes } = dispatch( 'core/block-editor' );

	dispatch( 'statik' ).resetEditorBlockId();

	const blocksClientIds = getClientIdsWithDescendants();

	blocksClientIds.forEach( ( clientId ) => {
		const blockName = getBlockName( clientId );
		if ( ! hasBlockIdAttributeDefinition( blockName ) ) {
			return;
		}

		const attributes = getBlockAttributes( clientId );
		const { blockId, className = '' } = attributes;
		if ( isNil( blockId ) ) {
			return;
		}

		const newBlockId = getFreshBlockId( blockName, clientId );

		const styleClassName = replaceBlockIdClassName(
			className,
			blockId,
			newBlockId
		);

		updateBlockAttributes( clientId, {
			className: styleClassName,
			blockId: newBlockId,
		} );
	} );
}
