import { __ } from '@wordpress/i18n';
import { dateI18n, __experimentalGetSettings } from '@wordpress/date';
import {
	useMedia,
	useBlockAttributes,
	useEntitiesByTaxonomy,
} from '@statik-space/wordpress-statik-blocks';
import { SkeletonTags } from './skeleton/SkeletonTags';
import { SkeletonCategories } from './skeleton/SkeletonCategories';
import { useMemo } from '@wordpress/element';
import { Play } from './icons/Play';

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
		displayPlayButton,
	} = attributes;

	const { entity } = props;

	const {
		title,
		excerpt = {},
		date_gmt: dateGMT,
		featured_media: featuredMedia,
		videos_category: videosCategory = [],
		videos_tag: videosTag = [],
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
	} = useEntitiesByTaxonomy( 'videos_category', {
		include: videosCategory,
	} );

	const {
		entities: tagsEntities,
		isResolvingEntities: isResolvingTags,
		// hasResolvedEntities: hasResolvedTags,
		// hasEntities: hasTags,
	} = useEntitiesByTaxonomy( 'videos_tag', { include: videosTag } );

	const dateFormat = __experimentalGetSettings().formats.date;
	const modifiedDateTime = dateI18n( dateFormat, dateGMT );
	const modifiedDateTimeAttr = dateI18n( 'Y-m-d', dateGMT );

	return (
		<div className="wp-block-statik-video-cards__card">
			{ displayFeaturedImage && imgUrl && (
				<div className="wp-block-statik-video-cards__image">
					<img src={ imgUrl } alt={ title.rendered } />
				</div>
			) }
			{ displayCategories && (
				<ul className="wp-block-statik-video-cards__categories">
					{ isResolvingCategories && <SkeletonCategories /> }
					{ categoriesEntities?.map( ( category ) => (
						<li key={ category.id }>{ category.name }</li>
					) ) }
				</ul>
			) }
			{ displayLastUpdatedDate && (
				<time
					className="wp-block-statik-video-cards__updated"
					dateTime={ modifiedDateTimeAttr }
				>
					{ modifiedDateTime }
				</time>
			) }
			{ displayTitle && (
				<h3
					className="wp-block-statik-video-cards__title"
					dangerouslySetInnerHTML={ { __html: title.rendered } }
				/>
			) }
			{ displayExcerpt && (
				<div
					className="wp-block-statik-video-cards__excerpt"
					dangerouslySetInnerHTML={ { __html: excerpt.rendered } }
				/>
			) }
			{ displayTags && (
				<ul className="wp-block-statik-video-cards__tags">
					{ isResolvingTags && <SkeletonTags /> }
					{ tagsEntities?.map( ( tag ) => (
						<li key={ tag.id }>{ tag.name }</li>
					) ) }
				</ul>
			) }
			{ displayReadMoreButton && (
				<button className="wp-block-statik-video-cards__read-more">
					{ __( 'Read More', 'statik-blocks' ) }
				</button>
			) }
			{ displayPlayButton && (
				<button className="wp-block-statik-video-cards__play-button">
					<Play />
				</button>
			) }
		</div>
	);
}
