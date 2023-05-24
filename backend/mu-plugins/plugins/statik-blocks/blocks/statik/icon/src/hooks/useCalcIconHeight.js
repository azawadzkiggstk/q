import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { MIN_ICON_BORDER, MIN_ICON_SIZE, MIN_ICON_SPACING } from '../edit';
import { useIsIconBorderRespected } from './useIsIconBorderRespected';

export const useCalcIconHeight = () => {
	const {
		attributes: {
			iconSize = MIN_ICON_SIZE,
			iconBorder = MIN_ICON_BORDER,
			iconSpacing = MIN_ICON_SPACING,
		},
	} = useBlockAttributes();

	const isBorderRespected = useIsIconBorderRespected();

	return (
		parseInt( iconSize ) +
		parseInt( iconSpacing ) * 2 +
		( isBorderRespected ? parseInt( iconBorder ) * 2 : 0 )
	);
};
