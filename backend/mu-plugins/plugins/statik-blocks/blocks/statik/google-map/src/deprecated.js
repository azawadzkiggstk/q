import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		latitude: {
			type: 'number',
		},
		longitude: {
			type: 'number',
		},
		zoom: {
			type: 'number',
		},
		height: {
			type: 'string',
			default: '320px',
		},
		showMarker: {
			type: 'boolean',
			default: false,
		},
		markerTitle: {
			type: 'string',
			default: '',
		},
		mapStyle: {
			enum: [ 'default', 'dark', 'light', 'night' ],
			default: 'default',
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
