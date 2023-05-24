import { useBlockEditContext } from '@wordpress/block-editor';
import { TabsNavItem } from './TabsNavItem';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { TabAppender } from './ui/TabAppender';
import { useDispatch, select } from '@wordpress/data';
import { SortableList } from './sortable/SortableList';

export function TabsNav( props ) {
	const { tabsRootId, tabsIds } = props;
	const { setAttributes } = useBlockAttributes( tabsRootId );

	const { isSelected } = useBlockEditContext();

	const { moveBlockToPosition } = useDispatch( 'core/block-editor' );
	const { toggleSelection } = useDispatch( 'core/editor' );

	const handleDragStart = () => {
		toggleSelection( false );
	};

	const handleDragCancel = () => {
		toggleSelection( true );
	};

	const handleDragEnd = ( event ) => {
		const { active, over } = event;

		const rootBlockId = tabsRootId;
		const clientId = active.id;
		const newBlockIndex = tabsIds.indexOf( over.id );
		moveBlockToPosition(
			clientId,
			rootBlockId,
			rootBlockId,
			newBlockIndex
		);

		const sortedBlocks =
			select( 'core/block-editor' ).getBlocks( rootBlockId );
		setAttributes( {
			items: sortedBlocks.map( ( block ) => ( {
				blockId: block.attributes.blockId,
				title: block.attributes.title,
				description: block.attributes.description,
			} ) ),
		} );
		toggleSelection( true );
	};

	return (
		<nav className="wp-block-statik-tabs__nav">
			<SortableList
				className="wp-block-statik-tabs__nav-list"
				items={ tabsIds }
				onDragStart={ handleDragStart }
				onDragEnd={ handleDragEnd }
				onDragCancel={ handleDragCancel }
			>
				{ tabsIds.map( ( blockId ) => (
					<TabsNavItem
						key={ blockId }
						tabsRootId={ tabsRootId }
						tabBlockId={ blockId }
					/>
				) ) }
			</SortableList>
			{ isSelected && <TabAppender tabsRootId={ tabsRootId } /> }
		</nav>
	);
}
