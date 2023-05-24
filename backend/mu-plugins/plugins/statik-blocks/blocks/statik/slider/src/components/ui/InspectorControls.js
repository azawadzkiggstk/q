import { InspectorControls as WPInspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	ToggleControl,
	__experimentalUnitControl as UnitControl,
	SelectControl,
} from '@wordpress/components';
import {
	useBlockAttributes,
	useInnerBlocks,
} from '@statik-space/wordpress-statik-blocks';
import { __ } from '@wordpress/i18n';
import { useMemo } from '@wordpress/element';

export function InspectorControls() {
	const { attributes, setAttributes } = useBlockAttributes();
	const {
		showDirectionNav,
		showControlNav,
		autoplay,
		loop,
		autoHeight,
		interval,
		preSelectedSlide,
	} = attributes;

	const innerBlocks = useInnerBlocks();

	const slides = useMemo( () => {
		return innerBlocks.map( ( slide, index ) => ( {
			value: index,
			label: `#${ index + 1 }`,
		} ) );
	}, [ innerBlocks ] );

	const setPreSelectedSlide = ( value ) => {
		setAttributes( { preSelectedSlide: Number( value ) } );
	};

	return (
		<WPInspectorControls>
			<PanelBody title={ __( 'Slider settings', 'statik-blocks' ) }>
				<SelectControl
					label={ __( 'Pre-selected Slide', 'statik-blocks' ) }
					value={ preSelectedSlide }
					onChange={ setPreSelectedSlide }
					options={ slides }
				/>
				<ToggleControl
					label={ __( 'Display navigation dots', 'statik-blocks' ) }
					checked={ showControlNav }
					onChange={ () =>
						setAttributes( { showControlNav: ! showControlNav } )
					}
				/>
				<ToggleControl
					label={ __( 'Display navigation arrows', 'statik-blocks' ) }
					checked={ showDirectionNav }
					onChange={ () =>
						setAttributes( {
							showDirectionNav: ! showDirectionNav,
						} )
					}
				/>
				<ToggleControl
					label={ __(
						'Start carousel automatically',
						'statik-blocks'
					) }
					checked={ autoplay }
					onChange={ () => setAttributes( { autoplay: ! autoplay } ) }
				/>
				<ToggleControl
					label={ __(
						'Loop carousel indefinitely',
						'statik-blocks'
					) }
					checked={ loop }
					onChange={ () => setAttributes( { loop: ! loop } ) }
				/>
				<ToggleControl
					label={ __(
						'Adjust carousel height to fit slides',
						'statik-blocks'
					) }
					checked={ autoHeight }
					onChange={ () =>
						setAttributes( { autoHeight: ! autoHeight } )
					}
				/>
				<UnitControl
					label={ __( 'Interval', 'statik-blocks' ) }
					units={ [
						{
							value: 'ms',
							label: __( 'ms', 'statik-blocks' ),
							default: 3000,
						},
					] }
					value={ `${ interval }ms` }
					onChange={ ( value ) =>
						setAttributes( { interval: parseInt( value ) } )
					}
					__unstableInputWidth="80px"
				/>
			</PanelBody>
		</WPInspectorControls>
	);
}
