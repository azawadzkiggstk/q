import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import clsx from 'clsx';

export default function SliderSlideSave( props ) {
	const { attributes } = props;

	const { isPreSelected, slideIndex, slidesCount } = attributes;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			'wp-block-statik-slider-slide--selected': Boolean( isPreSelected ),
		} ),
		role: 'group',
		'aria-roledescription': 'slide',
		'aria-label': `${ slideIndex + 1 } of ${ slidesCount }`,
	} );

	const innerBlocksProps = useInnerBlocksProps.save( blockProps );

	return <div { ...innerBlocksProps } />;
}
