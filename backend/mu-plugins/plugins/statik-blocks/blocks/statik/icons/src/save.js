import clsx from 'clsx';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

export default function IconsSave( props ) {
	const { attributes } = props;
	const { contentJustification, orientation } = attributes;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			[ `is-content-justification-${ contentJustification }` ]:
				contentJustification,
			'is-vertical': orientation === 'vertical',
		} ),
	} );

	const innerBlocksProps = useInnerBlocksProps.save( blockProps );

	return <div { ...innerBlocksProps } />;
}
