import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export const useIsIconBorderRespected = () => {
	const {
		attributes: { className = '' },
	} = useBlockAttributes();

	return className.includes( 'is-style-outline' );
};
