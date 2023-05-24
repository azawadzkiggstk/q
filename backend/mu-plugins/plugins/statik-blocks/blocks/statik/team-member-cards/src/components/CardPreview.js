import {
	useBlockAttributes,
	Skeleton,
} from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';

export function CardPreview() {
	const { attributes } = useBlockAttributes();
	const {
		displayImage,
		displayName,
		displayShortDescription,
		displayLongDescription,
		displaySocialMedia,
		displayReadMoreButton,
	} = attributes;

	const className = clsx(
		'wp-block-statik-team-member-cards__card',
		'wp-block-statik-team-member-cards__card--preview'
	);

	const SkeletonIcon = <Skeleton style={ { height: 40, width: 40 } } />;

	return (
		<div className={ className }>
			{ displayImage && (
				<div className="wp-block-statik-team-member-cards__image">
					<Skeleton style={ { height: 200, width: '100%' } } />
				</div>
			) }
			{ displayName && (
				<h3 className="wp-block-statik-team-member-cards__name">
					<Skeleton.Inline>John Doe</Skeleton.Inline>
				</h3>
			) }
			{ displayShortDescription && (
				<div className="wp-block-statik-team-member-cards__short-desc">
					<Skeleton.Text tag="p">
						Lorem ipsum dolor sit amet Consectetur adipiscing elit.
					</Skeleton.Text>
				</div>
			) }
			{ displayLongDescription && (
				<div className="wp-block-statik-team-member-cards__long-desc">
					<Skeleton.Text tag="p">
						Lorem ipsum dolor sit amet, consectetur adipiscing elit.
						Vivamus metus lectus, rutrum eu egestas a, bibendum ut
						mauris. Nunc rutrum mattis rutrum. Donec sed molestie
						tortor, in sodales metus.
					</Skeleton.Text>
				</div>
			) }
			{ displaySocialMedia && (
				<ul className="wp-block-statik-team-member-cards__social-media">
					<li>{ SkeletonIcon }</li>
					<li>{ SkeletonIcon }</li>
					<li>{ SkeletonIcon }</li>
					<li>{ SkeletonIcon }</li>
				</ul>
			) }
			{ displayReadMoreButton && (
				<button className="wp-block-statik-team-member-cards__read-more">
					<Skeleton.Inline>Read More</Skeleton.Inline>
				</button>
			) }
		</div>
	);
}
