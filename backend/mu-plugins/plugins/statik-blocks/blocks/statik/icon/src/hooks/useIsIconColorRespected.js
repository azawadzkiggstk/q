import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export const useIsIconColorRespected = () => {
	const {
		attributes: { icon },
	} = useBlockAttributes();

	return Boolean( icon );
};
