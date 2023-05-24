import { usePostTypes } from './usePostTypes';
export function usePostTypeSupports() {
  var _usePostTypes, _postTypeData$taxonom, _postTypeData$taxonom2;
  let postType = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 'post';
  const postTypes = (_usePostTypes = usePostTypes()) !== null && _usePostTypes !== void 0 ? _usePostTypes : [];
  const postTypeData = postTypes.find(item => item.slug === postType);
  return {
    hasThumbnailSupport: Boolean(postTypeData?.supports.thumbnail),
    hasTitleSupport: Boolean(postTypeData?.supports.title),
    hasExcerptSupport: Boolean(postTypeData?.supports.excerpt),
    hasCategoriesSupport: Boolean(((_postTypeData$taxonom = postTypeData?.taxonomies) !== null && _postTypeData$taxonom !== void 0 ? _postTypeData$taxonom : []).includes('category')),
    hasTagsSupport: Boolean(((_postTypeData$taxonom2 = postTypeData?.taxonomies) !== null && _postTypeData$taxonom2 !== void 0 ? _postTypeData$taxonom2 : []).includes('post_tag'))
  };
}