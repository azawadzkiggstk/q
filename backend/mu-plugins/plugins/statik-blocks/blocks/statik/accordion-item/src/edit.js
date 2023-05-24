import clsx from 'clsx';
import {
	InnerBlocks,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { BlockControls } from './components/ui/BlockControls';
import { InspectorControls } from './components/ui/InspectorControls';
import {
	useHasInnerBlocks,
	useHasSelectedInnerBlock,
} from '@statik-space/wordpress-statik-blocks';
import { HeaderTitle } from './components/HeaderTitle';
import { TEMPLATE } from './consts';
import './editor.scss';
import { ChevronDown } from './components/icons/ChevronDown';

export default function AccordionItemEdit( props ) {
	const { blockId, attributes, isSelected } = props;

	const { defaultExpanded } = attributes;

	const hasInnerBlocks = useHasInnerBlocks();
	const isInnerBlockSelected = useHasSelectedInnerBlock();

	const isExpanded = defaultExpanded || isSelected || isInnerBlockSelected;

	const blockProps = useBlockProps( {
		className: clsx( `wp-block-${ blockId }`, {
			'wp-block-statik-accordion-item--expanded': isExpanded,
			'wp-block-statik-accordion-item--default-expanded': defaultExpanded,
		} ),
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-accordion-item__inner-blocks',
		},
		{
			template: TEMPLATE,
			templateInsertUpdatesSelection: true,
			renderAppender: hasInnerBlocks
				? false
				: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<header className="wp-block-statik-accordion-item__header">
					<HeaderTitle />
					<ChevronDown className="wp-block-statik-accordion-item__icon" />
				</header>
				{ isExpanded && (
					<div
						className={ clsx(
							'wp-block-statik-accordion-item__body',
							{
								'wp-block-statik-accordion-item__body--empty':
									! hasInnerBlocks,
							}
						) }
					>
						<div { ...innerBlocksProps } />
					</div>
				) }
			</div>
		</>
	);
}
