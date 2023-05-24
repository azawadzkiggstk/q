import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		contentJustification: {
			type: 'string',
		},
		orientation: {
			type: 'string',
			default: 'horizontal',
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
