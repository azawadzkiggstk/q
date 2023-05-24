import {
	useInnerBlocksProps,
	useBlockProps,
	RichText,
	InnerBlocks,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { useHasInnerBlocks } from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';
import { BlockControls } from './components/ui/BlockControls';
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

export default function TimelineBoxEdit( props ) {
	const { attributes, setAttributes } = props;

	const { label, labelAlign } = attributes;

	const hasInnerBlocks = useHasInnerBlocks();

	const blockProps = useBlockProps( {
		className: clsx( {
			'wp-block-statik-timeline-item--ltr':
				labelAlign === 'left' || labelAlign === undefined,
			'wp-block-statik-timeline-item--rtl': labelAlign === 'right',
		} ),
	} );

	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-timeline-item__inner-blocks',
		},
		{
			template: TEMPLATE,
			renderAppender: hasInnerBlocks
				? false
				: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<>
			<BlockControls />
			<div { ...blockProps }>
				<div className="wp-block-statik-timeline-item__label">
					<RichText
						tagName="p"
						value={ label }
						onChange={ ( newLabel ) =>
							setAttributes( {
								label: newLabel,
							} )
						}
						placeholder={ __( 'Section label…', 'statik-blocks' ) }
						withoutInteractiveFormatting
					/>
				</div>
				<div className="wp-block-statik-timeline-item__bar">
					<div className="wp-block-statik-timeline-item__indicator" />
					<div className="wp-block-statik-timeline-item__dot" />
				</div>
				<div className="wp-block-statik-timeline-item__content">
					<div { ...innerBlocksProps } />
				</div>
			</div>
		</>
	);
}
