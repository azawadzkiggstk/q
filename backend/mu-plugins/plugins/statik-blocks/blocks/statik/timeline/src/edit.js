import {
	useInnerBlocksProps,
	useBlockProps,
	InnerBlocks,
} from '@wordpress/block-editor';
import './editor.scss';

const ALLOWED_BLOCKS = [ 'statik/timeline-item' ];

const TEMPLATE = [ [ 'statik/timeline-item', {} ] ];

export default function TimelineEdit() {
	const blockProps = useBlockProps();
	const innerBlockProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-timeline__inner-blocks',
		},
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
			renderAppender: InnerBlocks.ButtonBlockAppender,
		}
	);

	return (
		<div { ...blockProps }>
			<div { ...innerBlockProps } />
		</div>
	);
}
