import { useInnerBlocksProps } from '@wordpress/block-editor';
import { ALLOWED_BLOCKS, TEMPLATE } from '../consts';

export const Slides = () => {
	const innerBlocksProps = useInnerBlocksProps(
		{
			className: 'wp-block-statik-slider__slides',
		},
		{
			allowedBlocks: ALLOWED_BLOCKS,
			template: TEMPLATE,
			renderAppender: false,
			orientation: 'horizontal',
		}
	);
	return <div { ...innerBlocksProps } />;
};
