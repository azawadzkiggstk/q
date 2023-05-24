import { useSelect } from '@wordpress/data';
export function usePostTypes() {
  let excludedPostTypes = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : ['attachment'];
  return useSelect(select => {
    const {
      getPostTypes
    } = select('core');
    const filteredPostTypes = getPostTypes({
      per_page: -1
    })?.filter(_ref => {
      let {
        viewable,
        slug
      } = _ref;
      return viewable && !excludedPostTypes.includes(slug);
    });
    return filteredPostTypes;
  }, [excludedPostTypes]);
}