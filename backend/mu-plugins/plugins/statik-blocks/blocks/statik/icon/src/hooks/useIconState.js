import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { useCalcIconHeight } from './useCalcIconHeight';
import { useIsIconBorderRespected } from './useIsIconBorderRespected';
import { MIN_ICON_BORDER, MIN_ICON_SIZE, MIN_ICON_SPACING } from '../edit';
import { useIsIconBgColorRespected } from './useIsIconBgColorRespected';
import { useDispatch } from '@wordpress/data';

export const useIconState = () => {
	const { attributes, setAttributes } = useBlockAttributes();

	const {
		alt,
		icon,
		iconUrl,
		iconColor,
		accentColor,
		iconSize,
		iconSpacing,
		iconBorder,
	} = attributes;

	const iconSizeValue = parseInt( iconSize );
	const iconSpacingValue = parseInt( iconSpacing );
	const iconBorderValue = parseInt( iconBorder );

	const height = useCalcIconHeight();

	const isBorderRespected = useIsIconBorderRespected();
	const borderWidth = isBorderRespected ? iconBorder : MIN_ICON_BORDER;
	const borderColor = isBorderRespected ? accentColor : 'transparent';

	const isBgColorRespected = useIsIconBgColorRespected();
	const bgColor = isBgColorRespected ? accentColor : 'transparent';

	const padding = iconSpacing ?? MIN_ICON_SPACING;
	const size = iconSize ?? MIN_ICON_SIZE;

	const { toggleSelection } = useDispatch( 'core/editor' );

	const handleResizeStart = () => {
		toggleSelection( false );
	};

	const handleResizeStop = ( _event, _direction, _elt, delta ) => {
		const base =
			iconSizeValue +
			iconSpacingValue +
			( isBorderRespected ? iconBorderValue : 0 );

		const _iconSize =
			iconSizeValue +
			Math.floor( ( iconSizeValue / base ) * delta.height );
		const _iconSpacing =
			iconSpacingValue +
			Math.floor( ( iconSpacingValue / base ) * delta.height );
		const _iconBorder = isBorderRespected
			? parseInt( iconBorder ) +
			  Math.floor( ( parseInt( iconBorder ) / base ) * delta.height )
			: parseInt( iconBorder );

		setAttributes( {
			iconSize: `${ _iconSize }px`,
			iconSpacing: `${ _iconSpacing }px`,
			iconBorder: `${ _iconBorder }px`,
		} );

		toggleSelection( true );
	};

	return {
		alt,
		hasIcon: Boolean( icon || iconUrl ),
		handleResizeStart,
		handleResizeStop,
		blockHeight: height,
		style: {
			padding,
			borderWidth,
			borderColor,
			backgroundColor: bgColor,
			color: iconColor,
			width: size,
			height: size,
		},
	};
};
