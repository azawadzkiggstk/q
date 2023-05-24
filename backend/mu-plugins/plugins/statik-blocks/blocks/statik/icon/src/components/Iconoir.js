import { memo, createElement } from '@wordpress/element';
import IconoirIcons from '../icons/iconoir-icons.js';

export const Iconoir = memo( ( props ) => {
	const { icon, width = 24, height = 24, ...restProps } = props;

	const IconElement = IconoirIcons[ icon ];
	if ( IconElement ) {
		return createElement( IconElement, {
			width,
			height,
			...restProps,
		} );
	}
	return null;
} );
