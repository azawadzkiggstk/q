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
	useBlockRootAttributes,
	useBlockRootClientId,
	useInnerBlocks,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';

export function InnerBlocksControls() {
	const clientId = useBlockClientId();
	const rootClientId = useBlockRootClientId();
	const innerBlocks = useInnerBlocks( rootClientId );
	const { selectBlock, insertBlock, removeBlock } =
		useDispatch( 'core/block-editor' );
	const { getBlockIndex } = useSelect( 'core/block-editor' );
	const { attributes, setAttributes } = useBlockRootAttributes();
	const { activeTab } = attributes;
	const canDeleteTab = innerBlocks.length > 1;

	const choices = useMemo(
		() =>
			innerBlocks.map( ( block, index ) => ( {
				label: block.attributes.title || `Tab #${ index }`,
				value: block.clientId,
			} ) ),
		[ innerBlocks ]
	);

	const selectedTabId = innerBlocks[ activeTab ].clientId;

	const selectTab = async ( blockId ) => {
		const blockIndex = getBlockIndex( blockId, rootClientId );
		setAttributes( { activeTab: blockIndex } );
		await selectBlock( blockId );
		const value = `block-${ blockId }`;
		window.document.getElementById( value )?.focus();
	};

	const addTab = async () => {
		const block = createBlock( 'statik/tab' );
		const index = activeTab + 1;
		await insertBlock( block, index, rootClientId, true );
		await selectTab( block.clientId );
	};

	const deleteTab = async () => {
		if ( canDeleteTab ) {
			await removeBlock( clientId );
			setAttributes( { activeTab: Math.max( 0, activeTab - 1 ) } );
		}
	};

	return (
		<>
			<ToolbarButton
				label={ __( 'Tab index', 'statik-blocks' ) }
				isDisabled={ true }
			>
				<span>&nbsp;&nbsp;{ `#${ activeTab }` }</span>
			</ToolbarButton>
			<ToolbarDropdownMenu
				icon={ list }
				label={ __( 'Select Tab', 'statik-blocks' ) }
			>
				{ () => (
					<MenuGroup>
						<MenuItemsChoice
							choices={ choices }
							value={ selectedTabId }
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
				onClick={ deleteTab }
				isDisabled={ ! canDeleteTab }
			/>
		</>
	);
}
