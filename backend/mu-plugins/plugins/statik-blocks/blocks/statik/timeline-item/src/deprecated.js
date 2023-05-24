import { InnerBlocks } from '@wordpress/block-editor';

const v1 = {
	attributes: {
		label: {
			type: 'string',
		},
		labelAlign: {
			enum: [ 'left', 'right' ],
			default: 'left',
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1 ];

export default deprecated;
