import { useMemo } from '@wordpress/element';
export function usePostTypesOptions(postTypes) {
  return useMemo(() => (postTypes || []).map(_ref => {
    let {
      labels,
      slug
    } = _ref;
    return {
      label: labels.singular_name,
      value: slug
    };
  }), [postTypes]);
}