import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		selectedSlideIndex: {
			type: 'number',
			default: 0,
		},
		showDirectionNav: {
			type: 'boolean',
			default: true,
		},
		showControlNav: {
			type: 'boolean',
			default: true,
		},
		autoplay: {
			type: 'boolean',
			default: true,
		},
		loop: {
			type: 'boolean',
			default: true,
		},
		autoHeight: {
			type: 'boolean',
			default: false,
		},
		interval: {
			type: 'number',
			default: 3000,
		},
		slides: {
			type: 'array',
			default: [],
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
