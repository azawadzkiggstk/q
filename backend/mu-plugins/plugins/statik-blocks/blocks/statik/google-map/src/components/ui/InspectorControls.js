import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { MapStyles } from './MapStyles';
import { __ } from '@wordpress/i18n';
import {
	__experimentalUnitControl as UnitControl,
	PanelBody,
	ToggleControl,
	BaseControl,
} from '@wordpress/components';
import { InspectorControls as WPInspectorControls } from '@wordpress/block-editor';

export function InspectorControls() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { showMarker, mapStyle, height } = attributes;

	const changeMapStyle = ( value ) => {
		setAttributes( { mapStyle: value } );
	};

	const changeMapHeight = ( value ) => {
		setAttributes( { height: value } );
	};

	const toggleMapMarker = ( value ) => {
		setAttributes( { showMarker: value } );
	};

	return (
		<WPInspectorControls>
			<PanelBody
				title={ __( 'Map style', 'statik-blocks' ) }
				initialOpen={ true }
			>
				<MapStyles value={ mapStyle } onChange={ changeMapStyle } />
			</PanelBody>
			<PanelBody title={ __( 'Google map settings', 'statik-blocks' ) }>
				<BaseControl>
					<UnitControl
						label={ __( 'Height of a Map', 'statik-blocks' ) }
						placeholder={ __( 'inherit', 'statik-blocks' ) }
						value={ height }
						isPressEnterToChange={ true }
						onChange={ changeMapHeight }
						step={ 1 }
						__unstableInputWidth="80px"
					/>
				</BaseControl>
				<BaseControl>
					<ToggleControl
						title={ __( 'Marker', 'statik-blocks' ) }
						label={ __( 'Show Marker', 'statik-blocks' ) }
						checked={ showMarker }
						onChange={ toggleMapMarker }
					/>
				</BaseControl>
			</PanelBody>
		</WPInspectorControls>
	);
}
