import { InspectorControls } from './components/ui/InspectorControls';
import { useBlockProps } from '@wordpress/block-editor';
import { PostTypeCards } from './components/PostTypeCards';
import { Placeholder } from './components/ui/Placeholder';
import { BlockControls } from './components/ui/BlockControls';
import {
	useRwdAttribute,
	useBlockStyle,
} from '@statik-space/wordpress-statik-blocks';
import clsx from 'clsx';
import './editor.scss';

export default function PostTypeCardsEdit( props ) {
	const { attributes } = props;

	const { cardsPerRow, postType } = attributes;

	const blockProps = useBlockProps( {
		className: clsx( {
			[ `wp-block-statik-post-type-cards--${ postType }` ]: !! postType,
		} ),
	} );

	const cardsPreRowRwd = useRwdAttribute( cardsPerRow );

	const defaultStyle = useBlockStyle( `
		--wp-block-statik-post-type-cards--attr--cardsPerRow: ${ cardsPreRowRwd.inherit.default };
	` );

	const mobileStyle = useBlockStyle(
		`
		--wp-block-statik-post-type-cards--attr--cardsPerRow: ${ cardsPreRowRwd.inherit.mobile };
	`,
		{
			before: '@media (min-width: 0) {',
			after: '}',
		}
	);

	const tabletStyle = useBlockStyle(
		`
		--wp-block-statik-post-type-cards--attr--cardsPerRow: ${ cardsPreRowRwd.inherit.tablet };
	`,
		{
			before: '@media (min-width: 768px) {',
			after: '}',
		}
	);

	const desktopStyle = useBlockStyle(
		`
		--wp-block-statik-post-type-cards--attr--cardsPerRow: ${ cardsPreRowRwd.inherit.desktop };
	`,
		{
			before: '@media (min-width: 1000px) {',
			after: '}',
		}
	);

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<style { ...defaultStyle } />
				<style { ...mobileStyle } />
				<style { ...tabletStyle } />
				<style { ...desktopStyle } />
				{ postType && <PostTypeCards /> }
				{ ! postType && <Placeholder /> }
			</div>
		</>
	);
}
