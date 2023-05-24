import {
	InnerBlocks,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { InspectorControls } from './components/ui/InspectorControls';
import {
	useBlockIndex,
	useBlockRootClientId,
	useInnerBlocksIds,
	useBlockAttributes,
	useHasInnerBlocks,
} from '@statik-space/wordpress-statik-blocks';
import { TabsNav } from './components/TabsNav';
import { BlockControls } from './components/ui/BlockControls';
import { useTabEffects } from './hooks/useTabEffect';
import clsx from 'clsx';
import './editor.scss';

const TEMPLATE = [
	[
		'core/group',
		{
			style: {
				spacing: {
					padding: {
						top: '20px',
						right: '20px',
						bottom: '20px',
						left: '20px',
					},
				},
			},
		},
		[ [ 'core/paragraph', {} ] ],
	],
];

export default function TabEdit() {
	useTabEffects();

	const rootClientId = useBlockRootClientId();
	const parentInnerBlocksIds = useInnerBlocksIds( rootClientId );
	const {
		attributes: { activeTab },
	} = useBlockAttributes( rootClientId );

	const { attributes } = useBlockAttributes();
	const { blockId } = attributes;

	const hasInnerBlocks = useHasInnerBlocks();
	const blockIndex = useBlockIndex();
	const isTabActive = activeTab === blockIndex;

	const blockProps = useBlockProps( {
		className: clsx( {
			[ `wp-block-${ blockId }` ]: true,
			'wp-block-statik-tab--selected': isTabActive,
		} ),
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-tab__inner-blocks',
		},
		{
			template: TEMPLATE,
			templateInsertUpdatesSelection: false,
			renderAppender: hasInnerBlocks
				? false
				: InnerBlocks.ButtonBlockAppender,
		}
	);

	if ( ! isTabActive ) {
		return <div { ...blockProps } />;
	}

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<TabsNav
					tabsRootId={ rootClientId }
					tabsIds={ parentInnerBlocksIds }
				/>
				<div { ...innerBlocksProps } />
			</div>
		</>
	);
}
