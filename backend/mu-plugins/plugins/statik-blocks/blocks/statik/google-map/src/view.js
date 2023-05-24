import { Loader } from '@googlemaps/js-api-loader';
import { mapStyles } from './components/map-styles';

export default function ( { attributes, apiToken } ) {
	document.addEventListener( 'DOMContentLoaded', () => {
		new GoogleMap(
			document.querySelector( `.wp-block-${ attributes.blockId }` ),
			attributes,
			apiToken
		).init();
	} );
}

class GoogleMap {
	constructor( element, attributes, apiToken ) {
		this.element = element;
		this.attributes = attributes;
		this.apiToken = apiToken;
	}

	init() {
		this.initGoogleMap();
	}

	initGoogleMap() {
		const { latitude, longitude, zoom, mapStyle, showMarker } =
			this.attributes;

		const center = {
			lat: latitude,
			lng: longitude,
		};

		const mapLoader = new Loader( {
			apiKey: this.apiToken,
			version: 'weekly',
		} );

		const mapOptions = {
			center,
			zoom,
			streetViewControl: false,
			scaleControl: false,
			fullscreenControl: false,
			zoomControl: false,
			gestureHandling: 'none',
			styles: mapStyles[ mapStyle ].json,
		};

		mapLoader
			.load()
			.then( ( google ) => {
				if ( this.element ) {
					const map = new google.maps.Map( this.element, mapOptions );
					if ( showMarker ) {
						new google.maps.Marker( {
							position: center,
							map,
						} );
					}
				} else {
					throw new Error( 'Block element does not exist' );
				}
			} )
			.catch( ( e ) => {
				this.element.innerText =
					'Google Maps - Something went wrong while loading';
				console.error(
					'Google Maps - Something went wrong while loading.',
					e
				);
			} );
	}
}
