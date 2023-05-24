import { list, plusCircle, trash } from '@wordpress/icons';
import { createBlock } from '@wordpress/blocks';
import { useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import {
	ToolbarGroup,
	MenuGroup,
	MenuItemsChoice,
	ToolbarButton,
	ToolbarDropdownMenu,
} from '@wordpress/components';
import {
	useInnerBlocks,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';
import { useMemo } from '@wordpress/element';

export function InnerBlocksControls( props ) {
	const { rootClientId } = props;

	const innerBlocks = useInnerBlocks( rootClientId );
	const clientId = useBlockClientId();

	const { selectBlock, insertBlock, removeBlock } =
		useDispatch( 'core/block-editor' );

	const isParentBlock = rootClientId === clientId;
	const hasEnoughInnerBlock = innerBlocks.length > 1;

	const choices = useMemo( () => {
		return innerBlocks.map( ( blockData, index ) => ( {
			label: blockData.attributes.title || `Untitled #${ index }`,
			value: blockData.clientId,
		} ) );
	}, [ innerBlocks ] );

	const selectAccordionItem = async ( blockId ) => {
		await selectBlock( blockId );
		const value = `block-${ blockId }`;
		try {
			window.document.getElementById( value )?.focus();
		} catch ( error ) {
			console.error( 'Something went wrong', error );
		}
	};

	const addAccordionItem = async () => {
		const block = createBlock( 'statik/accordion-item' );
		await insertBlock( block, undefined, rootClientId );
	};

	const removeAccordionItem = async () => {
		await removeBlock( clientId );
	};

	return (
		<ToolbarGroup>
			<ToolbarDropdownMenu
				icon={ list }
				label={ __( 'Select item', 'statik-blocks' ) }
			>
				{ () => (
					<MenuGroup>
						<MenuItemsChoice
							choices={ choices }
							value={ clientId }
							onSelect={ selectAccordionItem }
						/>
					</MenuGroup>
				) }
			</ToolbarDropdownMenu>
			<ToolbarButton
				icon={ plusCircle }
				label={ __( 'Add new item', 'statik-blocks' ) }
				onClick={ addAccordionItem }
			/>
			<ToolbarButton
				icon={ trash }
				label={ __( 'Delete item', 'statik-blocks' ) }
				onClick={ removeAccordionItem }
				isDisabled={ isParentBlock || ! hasEnoughInnerBlock }
			/>
		</ToolbarGroup>
	);
}
