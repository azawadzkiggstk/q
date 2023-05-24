import { useBlockData } from './useBlockData';
import { useMemo } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
export function useBlockAcfSchemaOptions(postType) {
  const data = useBlockData();
  return useMemo(() => {
    if (!data?.extra?.acf) {
      return [];
    }
    const postTypeAcfFields = data.extra.acf[postType];
    return Object.entries(postTypeAcfFields !== null && postTypeAcfFields !== void 0 ? postTypeAcfFields : {}).map(field => {
      const [key, label] = field;
      return {
        value: `acf.${key}`,
        // translators: %1$s: string
        label: sprintf(__('acf: %1$s', 'statik'), label)
      };
    });
  }, [postType, data]);
}