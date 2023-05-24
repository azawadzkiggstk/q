import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		labelsEnabled: {
			type: 'boolean',
			default: true,
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
