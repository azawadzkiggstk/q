import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export const useIsIconBgColorRespected = () => {
	const {
		attributes: { className = '' },
	} = useBlockAttributes();

	return (
		className.includes( 'is-style-rectangular' ) ||
		className.includes( 'is-style-circular' )
	);
};
