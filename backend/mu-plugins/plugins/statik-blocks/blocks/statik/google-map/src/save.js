import { useBlockProps } from '@wordpress/block-editor';

export default function GoogleMapSave( props ) {
	const { attributes } = props;
	const { height } = attributes;
	const blockProps = useBlockProps.save( {
		style: {
			width: '100%',
			height,
		},
	} );

	return <div { ...blockProps }></div>;
}
