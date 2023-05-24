import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import { BlockControls } from './components/ui/BlockControls';
import { InspectorControls } from './components/ui/InspectorControls';
import { ALLOWED_BLOCKS, TEMPLATE } from './consts';
import { filterEntries } from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';
import './editor.scss';

export default function AccordionEdit( props ) {
	const { attributes } = props;
	const {
		blockId,
		showToggleIcon,
		headerBackgroundColor,
		headerTextColor,
		contentBackgroundColor,
		contentTextColor,
	} = attributes;

	const blockProps = useBlockProps( {
		className: clsx( `wp-block-${ blockId }`, {
			'wp-block-statik-accordion--no-toggle-icons':
				! Boolean( showToggleIcon ),
		} ),
		style: filterEntries(
			{
				'--statik-accordion--headerBackgroundColor':
					headerBackgroundColor,
				'--statik-accordion--headerTextColor': headerTextColor,
				'--statik-accordion--contentBackgroundColor':
					contentBackgroundColor,
				'--statik-accordion--contentTextColor': contentTextColor,
			},
			( [ , v ] ) => v !== ''
		),
	} );
	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-accordion__inner-blocks',
		},
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
			templateInsertUpdatesSelection: true,
		}
	);

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<div { ...innerBlocksProps } />
			</div>
		</>
	);
}
