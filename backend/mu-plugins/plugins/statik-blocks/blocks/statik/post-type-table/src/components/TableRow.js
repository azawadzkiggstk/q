import { __ } from '@wordpress/i18n';
import { dateI18n, __experimentalGetSettings } from '@wordpress/date';
import {
	useBlockAttributes,
	useEntitiesByTaxonomy,
} from '@statik-space/wordpress-statik-blocks';
import { SkeletonCategories } from './skeleton/SkeletonCategories';
import { SkeletonTags } from './skeleton/SkeletonTags';

export function TableRow( props ) {
	const { attributes } = useBlockAttributes();
	const {
		displayTitle,
		displayCategories,
		displayTags,
		displayLastUpdatedDate,
		displayReadMoreButton,
	} = attributes;

	const { entity } = props;
	const { title, date_gmt: dateGMT, categories = [], tags = [] } = entity;

	const {
		entities: categoriesEntities,
		isResolvingEntities: isResolvingCategories,
		// hasResolvedEntities: hasResolvedCategories,
		// hasEntities: hasCategories,
	} = useEntitiesByTaxonomy( 'category', { include: categories } );

	const {
		entities: tagsEntities,
		isResolvingEntities: isResolvingTags,
		// hasResolvedEntities: hasResolvedTags,
		// hasEntities: hasTags,
	} = useEntitiesByTaxonomy( 'post_tag', { include: tags } );

	const dateFormat = __experimentalGetSettings().formats.date;
	const modifiedDateTime = dateI18n( dateFormat, dateGMT );
	const modifiedDateTimeAttr = dateI18n( 'Y-m-d', dateGMT );

	return (
		<tr className="wp-block-statik-post-type-table__row">
			{ displayTitle && (
				<td className="wp-block-statik-post-type-table__title">
					<h3
						dangerouslySetInnerHTML={ { __html: title?.rendered } }
					/>
				</td>
			) }
			{ displayCategories && (
				<td className="wp-block-statik-post-type-table__categories">
					<ul>
						{ isResolvingCategories && <SkeletonCategories /> }
						{ categoriesEntities?.map( ( category ) => (
							<li key={ category.id }>{ category.name }</li>
						) ) }
					</ul>
				</td>
			) }
			{ displayTags && (
				<td className="wp-block-statik-post-type-table__tags">
					<ul>
						{ isResolvingTags && <SkeletonTags /> }
						{ tagsEntities?.map( ( tag ) => (
							<li key={ tag.id }>{ tag.name }</li>
						) ) }
					</ul>
				</td>
			) }
			{ displayLastUpdatedDate && (
				<td className="wp-block-statik-post-type-table__updated">
					<time dateTime={ modifiedDateTimeAttr }>
						{ modifiedDateTime }
					</time>
				</td>
			) }
			{ displayReadMoreButton && (
				<td className="wp-block-statik-post-type-table__read-more">
					<button>{ __( 'Read More', 'statik-blocks' ) }</button>
				</td>
			) }
		</tr>
	);
}
