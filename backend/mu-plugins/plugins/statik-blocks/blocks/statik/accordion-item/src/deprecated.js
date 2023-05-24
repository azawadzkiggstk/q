import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		title: {
			type: 'string',
		},
		defaultExpanded: {
			type: 'boolean',
			default: false,
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
	migrate( attributes ) {
		return {
			...attributes,
			headerSelector: 'h3',
		};
	},
};

const deprecated = [ v1 ];

export default deprecated;
