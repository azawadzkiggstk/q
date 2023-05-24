import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment, useMemo } from '@wordpress/element';
import { InspectorControls, useSetting } from '@wordpress/block-editor';
import { PanelBody, CustomSelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { hasBlockSupport } from '@wordpress/blocks';
import TokenList from '@wordpress/token-list';
import { find } from 'lodash';
import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';

const CLASS_PREFIX = 'is-font-variation-';

export const withBlockFontVariations = createHigherOrderComponent(
	( BlockEdit ) => {
		return ( props ) => {
			const { name } = props;
			const hasTypographySupport = hasBlockSupport( name, 'typography' );

			return (
				<Fragment>
					<BlockEdit { ...props } />
					{ hasTypographySupport && <FontVariationPanel /> }
				</Fragment>
			);
		};
	},
	'withBlockFontVariations'
);

const FontVariationPanel = () => {
	const { attributes, setAttributes } = useBlockAttributes();
	const { className = '' } = attributes;

	const fontsVariationsToRender = useFontVariations();
	const activeFontVariation = getActiveFontVariation(
		fontsVariationsToRender,
		className
	);

	const handleFontVariation = ( fontVariation ) => {
		const styleClassName = replaceActiveFontVariation(
			className,
			activeFontVariation,
			fontVariation
		);
		setAttributes( {
			className: styleClassName,
		} );
	};

	return (
		<InspectorControls>
			<PanelBody
				title={ __( 'Font Variations', 'statik' ) }
				initialOpen={ true }
			>
				<CustomSelectControl
					className="statik-font-variations-control"
					options={ fontsVariationsToRender }
					value={ activeFontVariation }
					onChange={ ( { selectedItem } ) => {
						handleFontVariation( selectedItem );
					} }
				/>
			</PanelBody>
		</InspectorControls>
	);
};

const fontVariationsFallback = [];

function useFontVariations() {
	const fontVariations =
		useSetting( 'custom.font-variations' ) ?? fontVariationsFallback;
	return useMemo(
		() => [
			{
				slug: 'default',
				name: 'Default',
				key: 'default',
				className: `${ CLASS_PREFIX }default`,
				isDefault: true,
			},
			...fontVariations.map( ( fontVariation ) => ( {
				...fontVariation,
				key: fontVariation.slug,
				className: `${ CLASS_PREFIX }${ fontVariation.slug }`,
			} ) ),
		],
		[ fontVariations ]
	);
}

const getActiveFontVariation = ( fontVariations, className ) => {
	for ( const style of new TokenList( className ).values() ) {
		if ( style.indexOf( CLASS_PREFIX ) === -1 ) {
			continue;
		}
		const potentialStyleName = style.substring( CLASS_PREFIX.length );
		const activeStyle = find( fontVariations, {
			slug: potentialStyleName,
		} );
		if ( activeStyle ) {
			return activeStyle;
		}
	}

	return find( fontVariations, 'isDefault' );
};

const replaceActiveFontVariation = (
	className,
	activeFontVariation,
	newFontVariation
) => {
	const list = new TokenList( className );

	if ( activeFontVariation ) {
		list.remove( CLASS_PREFIX + activeFontVariation.slug );
	}

	list.add( CLASS_PREFIX + newFontVariation.slug );

	return list.value;
};
