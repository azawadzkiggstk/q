import { useBlockProps } from '@wordpress/block-editor';
import { rwdAttribute } from '@statik-space/wordpress-statik-blocks';

export default function SpacerSave( props ) {
	const { attributes } = props;
	const { height } = attributes;

	const blockProps = useBlockProps.save();
	const heightRwd = rwdAttribute( height );

	return (
		<div { ...blockProps }>
			<div
				className="wp-block-statik-spacer--mobile"
				style={ { height: heightRwd.inherit.mobile } }
			></div>
			<div
				className="wp-block-statik-spacer--tablet"
				style={ { height: heightRwd.inherit.tablet } }
			></div>
			<div
				className="wp-block-statik-spacer--desktop"
				style={ { height: heightRwd.inherit.desktop } }
			></div>
		</div>
	);
}
