import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export const useIsAccentColorRespected = () => {
	const {
		attributes: { className = '' },
	} = useBlockAttributes();

	return (
		className.includes( 'is-style-rectangular' ) ||
		className.includes( 'is-style-circular' ) ||
		className.includes( 'is-style-outline' )
	);
};
