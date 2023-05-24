import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		showToggleIcon: {
			type: 'boolean',
			default: true,
		},
		openOnlyOne: {
			type: 'boolean',
			default: false,
		},
		headerBackgroundColor: {
			type: 'string',
			default: '#f5f5f5',
		},
		headerTextColor: {
			type: 'string',
			default: '#444444',
		},
		contentBackgroundColor: {
			type: 'string',
			default: '#fafafa',
		},
		contentTextColor: {
			type: 'string',
			default: '#444444',
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
