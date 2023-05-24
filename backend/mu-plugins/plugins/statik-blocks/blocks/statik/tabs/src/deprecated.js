import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		activeTab: {
			type: 'number',
			default: 0,
		},
		descriptionEnabled: {
			type: 'boolean',
			default: false,
		},
		preSelectedTab: {
			type: 'number',
			default: 0,
		},
		tabsAlignment: {
			enum: [ 'left', 'center', 'right' ],
			default: 'left',
		},
		accentColor: {
			type: 'string',
			default: '#CCE3EB',
		},
		textColor: {
			type: 'string',
			default: '#3858E9',
		},
		activeAccentColor: {
			type: 'string',
			default: '#1D35B4',
		},
		activeTextColor: {
			type: 'string',
			default: '#FFFFFF',
		},
		contentBackgroundColor: {
			type: 'string',
		},
		contentTextColor: {
			type: 'string',
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
