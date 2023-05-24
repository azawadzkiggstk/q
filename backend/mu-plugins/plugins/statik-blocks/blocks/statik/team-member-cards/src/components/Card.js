import { __ } from '@wordpress/i18n';
import {
	useMedia,
	useBlockAttributes,
} from '@statik-space/wordpress-statik-blocks';
import { useMemo } from '@wordpress/element';
import { Mail } from './icons/Mail';
import { Website } from './icons/Website';
import { Twitter } from './icons/Twitter';
import { LinkedIn } from './icons/LinkedIn';

export function Card( props ) {
	const { attributes } = useBlockAttributes();
	const {
		displayImage,
		displayName,
		displayShortDescription,
		displayLongDescription,
		displaySocialMedia,
		displayReadMoreButton,
	} = attributes;

	const { entity } = props;

	const { title, featured_media: featuredMedia, acf } = entity;

	const {
		short_description: shortDescription,
		long_description: longDescription,
		contact_details: { email, linkedin, twitter, website },
	} = acf;

	const media = useMedia( featuredMedia );
	const imgUrl = useMemo( () => {
		const thumbnail =
			media?.media_details?.sizes[ 'post-thumbnail' ]?.source_url;
		const fallback = media?.source_url;
		return thumbnail ?? fallback;
	}, [ media ] );

	return (
		<div className="wp-block-statik-team-member-cards__card">
			{ displayImage && imgUrl && (
				<div className="wp-block-statik-team-member-cards__image">
					<img src={ imgUrl } alt={ title.rendered } />
				</div>
			) }
			{ displayName && (
				<h3
					className="wp-block-statik-team-member-cards__name"
					dangerouslySetInnerHTML={ { __html: title.rendered } }
				/>
			) }
			{ displayShortDescription && (
				<div
					className="wp-block-statik-team-member-cards__short-desc"
					dangerouslySetInnerHTML={ { __html: shortDescription } }
				/>
			) }
			{ displayLongDescription && (
				<div
					className="wp-block-statik-team-member-cards__long-desc"
					dangerouslySetInnerHTML={ { __html: longDescription } }
				/>
			) }
			{ displaySocialMedia && (
				<ul className="wp-block-statik-team-member-cards__social-media">
					{ email && (
						<li className="wp-block-statik-team-member-cards__mail">
							<a href={ `mailto:${ email }` }>
								<Mail />
							</a>
						</li>
					) }
					{ linkedin && (
						<li className="wp-block-statik-team-member-cards__linkedin">
							<a href={ linkedin } rel="noreferrer">
								<LinkedIn />
							</a>
						</li>
					) }
					{ twitter && (
						<li className="wp-block-statik-team-member-cards__twitter">
							<a href={ twitter } rel="noreferrer">
								<Twitter />
							</a>
						</li>
					) }
					{ website && (
						<li className="wp-block-statik-team-member-cards__website">
							<a href={ website } rel="noreferrer">
								<Website />
							</a>
						</li>
					) }
				</ul>
			) }
			{ displayReadMoreButton && (
				<button className="wp-block-statik-team-member-cards__read-more">
					{ __( 'Read More', 'statik-blocks' ) }
				</button>
			) }
		</div>
	);
}
