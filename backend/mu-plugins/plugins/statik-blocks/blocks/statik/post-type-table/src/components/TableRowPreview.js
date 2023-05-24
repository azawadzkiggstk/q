import {
	useBlockAttributes,
	Skeleton,
} from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';
import { SkeletonCategories } from './skeleton/SkeletonCategories';
import { SkeletonTags } from './skeleton/SkeletonTags';

export function TableRowPreview() {
	const { attributes } = useBlockAttributes();
	const {
		displayTitle,
		displayCategories,
		displayTags,
		displayLastUpdatedDate,
		displayReadMoreButton,
	} = attributes;

	const className = clsx(
		'wp-block-statik-post-type-table__row',
		'wp-block-statik-post-type-table__row--preview'
	);

	return (
		<tr className={ className }>
			{ displayTitle && (
				<td className="wp-block-statik-post-type-table__title">
					<h3>
						<Skeleton.Inline>Sample title</Skeleton.Inline>
					</h3>
				</td>
			) }
			{ displayCategories && (
				<td className="wp-block-statik-post-type-table__categories">
					<ul>
						<SkeletonCategories />
					</ul>
				</td>
			) }
			{ displayTags && (
				<td className="wp-block-statik-post-type-table__tags">
					<ul>
						<SkeletonTags />
					</ul>
				</td>
			) }
			{ displayLastUpdatedDate && (
				<td className="wp-block-statik-post-type-table__updated">
					<time dateTime="2012-12-12">
						<Skeleton.Inline>12.12.2012</Skeleton.Inline>
					</time>
				</td>
			) }
			{ displayReadMoreButton && (
				<td className="wp-block-statik-post-type-table__read-more">
					<button>
						<Skeleton.Inline>Read More</Skeleton.Inline>
					</button>
				</td>
			) }
		</tr>
	);
}
