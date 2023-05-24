import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { GoogleMap, useJsApiLoader, Marker } from '@react-google-maps/api';
import { Spinner } from '@wordpress/components';
import { mapStyles } from './map-styles';
import { useGoogleMapSettings } from '../hooks/useGoogleMapSettings';

export const Map = () => {
	const { attributes } = useBlockAttributes();
	const { latitude, longitude, zoom, mapStyle, height, showMarker } =
		attributes;

	const containerStyle = {
		width: '100%',
		height,
		pointerEvents: 'none',
	};

	const center = {
		lat: latitude,
		lng: longitude,
	};

	const { apiToken } = useGoogleMapSettings();

	const { isLoaded } = useJsApiLoader( {
		id: 'google-map-script',
		googleMapsApiKey: apiToken,
	} );

	const options = {
		streetViewControl: false,
		scaleControl: false,
		fullscreenControl: false,
		zoomControl: false,
		gestureHandling: 'none',
		styles: mapStyles[ mapStyle ].json,
	};

	if ( ! isLoaded ) {
		return <Spinner />;
	}

	return (
		<GoogleMap
			mapContainerStyle={ containerStyle }
			center={ center }
			zoom={ zoom }
			options={ options }
		>
			{ showMarker && <Marker position={ center } /> }
		</GoogleMap>
	);
};
