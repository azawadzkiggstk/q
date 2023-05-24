import { InspectorControls } from './components/ui/InspectorControls';
import { BlockControls } from './components/ui/BlockControls';
import { useInnerBlocksProps, useBlockProps } from '@wordpress/block-editor';
import { useTabsEffects } from './hooks/useTabsEffects';
import { ALLOWED_BLOCKS, INNER_BLOCKS_TEMPLATE } from './consts';
import { NoTabsWarning } from './components/ui/NoTabsWarning';
import { filterEntries } from '@statik-space/wordpress-statik-blocks';
import './editor.scss';
import clsx from 'clsx';

export default function TabsEdit( props ) {
	const { attributes } = props;

	const {
		blockId,
		accentColor,
		activeAccentColor,
		textColor,
		activeTextColor,
		contentBackgroundColor,
		contentTextColor,
		tabsAlignment,
	} = attributes;

	useTabsEffects();

	const blockProps = useBlockProps( {
		className: clsx( `wp-block-${ blockId }`, {
			[ `wp-block-statik-tabs--nav-align-${ tabsAlignment }` ]:
				Boolean( tabsAlignment ),
		} ),
		style: filterEntries(
			{
				'--statik-tabs--accentColor': accentColor,
				'--statik-tabs--activeAccentColor': activeAccentColor,
				'--statik-tabs--textColor': textColor,
				'--statik-tabs--activeTextColor': activeTextColor,
				'--statik-tabs--contentBackgroundColor': contentBackgroundColor,
				'--statik-tabs--contentTextColor': contentTextColor,
			},
			( [ , v ] ) => v !== ''
		),
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-tabs__inner-blocks',
		},
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: INNER_BLOCKS_TEMPLATE,
			templateInsertUpdatesSelection: true,
			renderAppender: false,
			orientation: 'horizontal',
		}
	);

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<div { ...innerBlocksProps } />
				<NoTabsWarning />
			</div>
		</>
	);
}
