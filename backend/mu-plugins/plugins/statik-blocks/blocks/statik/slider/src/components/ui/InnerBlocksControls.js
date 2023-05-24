import { list, plusCircle, trash } from '@wordpress/icons';
import { createBlock } from '@wordpress/blocks';
import { useDispatch, useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';
import {
	MenuGroup,
	MenuItemsChoice,
	ToolbarButton,
	ToolbarDropdownMenu,
} from '@wordpress/components';
import {
	useBlockAttributes,
	useInnerBlocksIds,
	useBlockClientId,
} from '@statik-space/wordpress-statik-blocks';

export function InnerBlocksControls( props ) {
	const { rootClientId } = props;

	const clientId = useBlockClientId();
	const innerBlocksIds = useInnerBlocksIds( rootClientId );

	const { selectBlock, insertBlock, removeBlock } =
		useDispatch( 'core/block-editor' );

	const { getBlockIndex } = useSelect( 'core/block-editor' );

	const { attributes, setAttributes } = useBlockAttributes( rootClientId );
	const { selectedSlideIndex } = attributes;

	const isRootBlock = clientId === rootClientId;
	const canDeleteSlide = innerBlocksIds.length > 1 && ! isRootBlock;
	const selectedSlideId = innerBlocksIds[ selectedSlideIndex ];

	const choices = useMemo(
		() =>
			innerBlocksIds.map( ( blockClientId, index ) => ( {
				label: `Slide #${ index }`,
				value: blockClientId,
			} ) ),
		[ innerBlocksIds ]
	);

	const selectSlide = async ( blockId ) => {
		const blockIndex = getBlockIndex( blockId, rootClientId );
		setAttributes( { selectedSlideIndex: blockIndex } );
		await selectBlock( blockId );
		const value = `block-${ blockId }`;
		window.document.getElementById( value )?.focus();
	};

	const addNewSlide = async () => {
		const block = createBlock( 'statik/slider-slide' );
		const index = selectedSlideIndex + 1;
		await insertBlock( block, index, rootClientId, false );
		await selectSlide( block.clientId );
	};

	const deleteSlide = async () => {
		if ( canDeleteSlide ) {
			await removeBlock( clientId );
			setAttributes( {
				selectedSlideIndex: Math.max( 0, selectedSlideIndex - 1 ),
			} );
		}
	};

	return (
		<>
			<ToolbarButton
				label={ __( 'Slide index', 'statik-blocks' ) }
				isDisabled={ true }
			>
				<span>&nbsp;&nbsp;{ `#${ selectedSlideIndex }` }</span>
			</ToolbarButton>
			<ToolbarDropdownMenu
				icon={ list }
				label={ __( 'Select slide', 'statik-blocks' ) }
			>
				{ () => (
					<MenuGroup>
						<MenuItemsChoice
							choices={ choices }
							value={ isRootBlock ? undefined : selectedSlideId }
							onSelect={ selectSlide }
						/>
					</MenuGroup>
				) }
			</ToolbarDropdownMenu>
			<ToolbarButton
				icon={ plusCircle }
				label={ __( 'Add new slide', 'statik-blocks' ) }
				onClick={ addNewSlide }
			/>
			<ToolbarButton
				icon={ trash }
				label={ __( 'Delete slide', 'statik-blocks' ) }
				onClick={ deleteSlide }
				isDisabled={ ! canDeleteSlide }
			/>
		</>
	);
}
