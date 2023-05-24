import { useBlockData } from '@statik-space/wordpress-statik-blocks';

export function useGoogleMapSettings() {
	const data = useBlockData();

	return {
		apiToken: data?.settings?.apiToken,
	};
}
