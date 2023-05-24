import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

export function TableHeader() {
	const { attributes } = useBlockAttributes();
	const {
		displayTitle,
		displayCategories,
		displayTags,
		displayLastUpdatedDate,
		displayReadMoreButton,
	} = attributes;

	return (
		<thead>
			<tr className="wp-block-statik-post-type-table__header-row">
				{ displayTitle && (
					<th className="wp-block-statik-post-type-table__title">
						Title
					</th>
				) }
				{ displayCategories && (
					<th className="wp-block-statik-post-type-table__categories">
						Categories
					</th>
				) }
				{ displayTags && (
					<th className="wp-block-statik-post-type-table__tags">
						Tags
					</th>
				) }
				{ displayLastUpdatedDate && (
					<th className="wp-block-statik-post-type-table__updated">
						Date
					</th>
				) }
				{ displayReadMoreButton && (
					<th className="wp-block-statik-post-type-table__read-more">
						Read More
					</th>
				) }
			</tr>
		</thead>
	);
}
