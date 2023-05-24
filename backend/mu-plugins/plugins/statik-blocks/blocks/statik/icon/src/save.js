import clsx from 'clsx';
import { createElement } from '@wordpress/element';
import { useBlockProps } from '@wordpress/block-editor';
import IconoirIcons from './icons/iconoir-icons.js';

export default function IconSave( props ) {
	const { attributes } = props;
	const { align } = attributes;

	const { style } = useSaveIconState( attributes );

	const blockProps = useBlockProps.save( {
		className: clsx( {
			'has-block-align-left': align === 'left',
			'has-block-align-center': align === 'center',
			'has-block-align-right': align === 'right',
		} ),
	} );

	return (
		<div { ...blockProps }>
			<div className="wp-block-statik-icon__icon" style={ style }>
				<IconPreview { ...attributes } />
			</div>
		</div>
	);
}

const useSaveIconState = ( attributes ) => {
	const { className, iconBorder, accentColor, iconSpacing, iconSize } =
		attributes;
	const mainStyles = ( classNameString ) => {
		const styles = {
			'is-style-outline': {
				borderWidth: iconBorder,
				borderColor: accentColor,
			},
			'is-style-rectangular': {
				backgroundColor: accentColor,
			},
			'is-style-circular': {
				backgroundColor: accentColor,
			},
		};

		const possibleStyles = Object.keys( styles );

		const blockStyle = ( classNameString ?? '' )
			.split( ' ' )
			.find( ( value ) => {
				return possibleStyles.includes( value );
			} );

		if ( blockStyle ) {
			return styles[ blockStyle ];
		}

		return {};
	};

	const style = {
		...mainStyles( className ),
		padding: iconSpacing,
		width: iconSize,
		height: iconSize,
	};

	return { style };
};

const IconPreview = ( props ) => {
	const { icon, iconUrl, iconSize, altText, iconColor } = props;

	if ( iconUrl ) {
		return (
			<img
				src={ iconUrl }
				alt={ altText }
				width={ iconSize }
				height={ iconSize }
			/>
		);
	} else if ( icon ) {
		return (
			<Iconoir
				icon={ icon }
				width={ iconSize }
				height={ iconSize }
				color={ iconColor }
			/>
		);
	}

	return null;
};

export const Iconoir = ( props ) => {
	const { icon, width, height, color } = props;

	const IconElement = IconoirIcons[ icon ];
	if ( IconElement ) {
		return createElement( IconElement, {
			color,
			width,
			height,
		} );
	}
	return null;
};
