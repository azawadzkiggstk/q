import { Button, Icon } from '@wordpress/components';
import { plus } from '@wordpress/icons';
import { createBlock } from '@wordpress/blocks';
import { useDispatch } from '@wordpress/data';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export const TabAppender = ( props ) => {
	const { tabsRootId } = props;
	const { attributes, setAttributes } = useBlockAttributes( tabsRootId );
	const { activeTab } = attributes;

	const { selectBlock, insertBlock } = useDispatch( 'core/block-editor' );

	const selectTab = async ( index, blockId ) => {
		setAttributes( {
			activeTab: index,
		} );
		await selectBlock( blockId );
	};

	const addTab = async () => {
		const block = createBlock( 'statik/tab' );
		const index = activeTab + 1;
		await insertBlock( block, index, tabsRootId, true );
		await selectTab( index, block.clientId );
	};

	return (
		<Button
			className="wp-block-statik-tabs__nav-item-appender"
			onClick={ addTab }
		>
			<Icon icon={ plus } />
		</Button>
	);
};
