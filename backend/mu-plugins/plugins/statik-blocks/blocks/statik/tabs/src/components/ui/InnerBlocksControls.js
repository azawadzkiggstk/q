import { list, plusCircle, trash } from '@wordpress/icons';
import { createBlock } from '@wordpress/blocks';
import { useDispatch, useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import {
	MenuGroup,
	MenuItemsChoice,
	ToolbarButton,
	ToolbarDropdownMenu,
} from '@wordpress/components';
import { useMemo } from '@wordpress/element';
import {
	useInnerBlocks,
	useBlockAttributes,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';

export function InnerBlocksControls() {
	const clientId = useBlockClientId();
	const innerBlocks = useInnerBlocks();
	const { selectBlock, insertBlock } = useDispatch( 'core/block-editor' );
	const { getBlockIndex } = useSelect( 'core/block-editor' );
	const { attributes, setAttributes } = useBlockAttributes();
	const { activeTab } = attributes;

	const choices = useMemo(
		() =>
			innerBlocks.map( ( block, index ) => ( {
				label: block.attributes.title || `Tab #${ index }`,
				value: block.clientId,
			} ) ),
		[ innerBlocks ]
	);

	const selectTab = async ( blockId ) => {
		const blockIndex = getBlockIndex( blockId, clientId );
		setAttributes( { activeTab: blockIndex } );
		await selectBlock( blockId );
		const value = `block-${ blockId }`;
		window.document.getElementById( value )?.focus();
	};

	const addTab = async () => {
		const block = createBlock( 'statik/tab' );
		const index = activeTab + 1;
		await insertBlock( block, index, clientId, true );
		await selectTab( block.clientId );
	};

	return (
		<>
			<ToolbarDropdownMenu
				icon={ list }
				label={ __( 'Select Tab', 'statik-blocks' ) }
			>
				{ () => (
					<MenuGroup>
						<MenuItemsChoice
							choices={ choices }
							value={ null }
							onSelect={ selectTab }
						/>
					</MenuGroup>
				) }
			</ToolbarDropdownMenu>
			<ToolbarButton
				icon={ plusCircle }
				label={ __( 'Add new Tab', 'statik-blocks' ) }
				onClick={ addTab }
			/>
			<ToolbarButton
				icon={ trash }
				label={ __( 'Delete Tab', 'statik-blocks' ) }
				isDisabled={ true }
			/>
		</>
	);
}
