import { useMemo } from '@wordpress/element';

export function usePostTypesOptions( postTypes ) {
	return useMemo(
		() =>
			( postTypes || [] ).map( ( { labels, slug } ) => ( {
				label: labels.singular_name,
				value: slug,
			} ) ),
		[ postTypes ]
	);
}
