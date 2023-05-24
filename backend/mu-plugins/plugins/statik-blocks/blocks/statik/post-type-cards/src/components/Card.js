import { useMemo } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { dateI18n, __experimentalGetSettings } from '@wordpress/date';
import {
	useMedia,
	useBlockAttributes,
	usePostTypeSupports,
	useEntitiesByTaxonomy,
	Skeleton,
} from '@statik-space/wordpress-statik-blocks';

export function Card( props ) {
	const { attributes } = useBlockAttributes();
	const {
		displayFeaturedImage,
		displayTitle,
		displayExcerpt,
		displayCategories,
		displayTags,
		displayLastUpdatedDate,
		displayReadMoreButton,
		postType,
	} = attributes;

	const { entity } = props;

	const {
		title,
		excerpt,
		date_gmt: dateGMT,
		featured_media: featuredMedia,
		categories = [],
		tags = [],
	} = entity;

	const media = useMedia( featuredMedia );
	const imgUrl = useMemo( () => {
		const thumbnail =
			media?.media_details?.sizes[ 'post-thumbnail' ]?.source_url;
		const fallback = media?.source_url;
		return thumbnail ?? fallback;
	}, [ media ] );

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

	const {
		hasThumbnailSupport,
		hasTitleSupport,
		hasExcerptSupport,
		hasCategoriesSupport,
		hasTagsSupport,
	} = usePostTypeSupports( postType );

	return (
		<div className="wp-block-statik-post-type-cards__card">
			{ hasThumbnailSupport && displayFeaturedImage && imgUrl && (
				<div className="wp-block-statik-post-type-cards__image">
					<img src={ imgUrl } alt={ title.rendered } />
				</div>
			) }
			{ hasCategoriesSupport && displayCategories && (
				<ul className="wp-block-statik-post-type-cards__categories">
					{ isResolvingCategories && (
						<>
							<li>
								<Skeleton.Inline>Lorem ipsum</Skeleton.Inline>
							</li>
						</>
					) }
					{ categoriesEntities?.map( ( category ) => (
						<li key={ category.id }>{ category.name }</li>
					) ) }
				</ul>
			) }
			{ displayLastUpdatedDate && (
				<time
					className="wp-block-statik-post-type-cards__updated"
					dateTime={ modifiedDateTimeAttr }
				>
					{ modifiedDateTime }
				</time>
			) }
			{ hasTitleSupport && displayTitle && (
				<h3
					className="wp-block-statik-post-type-cards__title"
					dangerouslySetInnerHTML={ { __html: title.rendered } }
				/>
			) }
			{ hasExcerptSupport && displayExcerpt && (
				<div
					className="wp-block-statik-post-type-cards__excerpt"
					dangerouslySetInnerHTML={ { __html: excerpt?.rendered } }
				/>
			) }
			{ hasTagsSupport && displayTags && (
				<ul className="wp-block-statik-post-type-cards__tags">
					{ isResolvingTags && (
						<>
							<li>
								<Skeleton.Inline>Lorem</Skeleton.Inline>
							</li>
							<li>
								<Skeleton.Inline>Dolor amet</Skeleton.Inline>
							</li>
							<li>
								<Skeleton.Inline>Dolor</Skeleton.Inline>
							</li>
						</>
					) }
					{ tagsEntities?.map( ( tag ) => (
						<li key={ tag.id }>{ tag.name }</li>
					) ) }
				</ul>
			) }
			{ displayReadMoreButton && (
				<button className="wp-block-statik-post-type-cards__read-more">
					{ __( 'Read More', 'statik-blocks' ) }
				</button>
			) }
		</div>
	);
}
