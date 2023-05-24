import { usePostTypes } from './usePostTypes';

export function usePostTypeSupports( postType = 'post' ) {
	const postTypes = usePostTypes() ?? [];
	const postTypeData = postTypes.find( ( item ) => item.slug === postType );

	return {
		hasThumbnailSupport: Boolean( postTypeData?.supports.thumbnail ),
		hasTitleSupport: Boolean( postTypeData?.supports.title ),
		hasExcerptSupport: Boolean( postTypeData?.supports.excerpt ),
		hasCategoriesSupport: Boolean(
			( postTypeData?.taxonomies ?? [] ).includes( 'category' )
		),
		hasTagsSupport: Boolean(
			( postTypeData?.taxonomies ?? [] ).includes( 'post_tag' )
		),
	};
}
