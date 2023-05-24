import {
	useBlockAttributes,
	Skeleton,
} from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';
import { SkeletonTags } from './skeleton/SkeletonTags';
import { SkeletonCategories } from './skeleton/SkeletonCategories';

export function CardPreview() {
	const { attributes } = useBlockAttributes();
	const {
		displayFeaturedImage,
		displayExcerpt,
		displayTitle,
		displayCategories,
		displayTags,
		displayLastUpdatedDate,
		displayReadMoreButton,
		// displayPlayButton,
	} = attributes;

	const className = clsx(
		'wp-block-statik-video-cards__card',
		'wp-block-statik-video-cards__card--preview'
	);

	return (
		<div className={ className }>
			{ displayFeaturedImage && (
				<div className="wp-block-statik-video-cards__image">
					<Skeleton style={ { height: 200, width: '100%' } } />
				</div>
			) }
			{ displayCategories && (
				<ul className="wp-block-statik-video-cards__categories">
					<SkeletonCategories />
				</ul>
			) }
			{ displayLastUpdatedDate && (
				<time
					className="wp-block-statik-video-cards__updated"
					dateTime="2012-12-12"
				>
					<Skeleton.Inline>12.12.2012</Skeleton.Inline>
				</time>
			) }
			{ displayTitle && (
				<h3 className="wp-block-statik-video-cards__title">
					<Skeleton.Inline>Sample title</Skeleton.Inline>
				</h3>
			) }
			{ displayExcerpt && (
				<div className="wp-block-statik-video-cards__excerpt">
					<Skeleton.Text tag="p">
						Lorem ipsum dolor sit amet Consectetur adipiscing elit.
						Suspendisse et ligula eu est eleifend molestie.
					</Skeleton.Text>
				</div>
			) }
			{ displayTags && (
				<ul className="wp-block-statik-video-cards__tags">
					<SkeletonTags />
				</ul>
			) }
			{ displayReadMoreButton && (
				<button className="wp-block-statik-video-cards__read-more">
					<Skeleton.Inline>Read More</Skeleton.Inline>
				</button>
			) }
		</div>
	);
}
