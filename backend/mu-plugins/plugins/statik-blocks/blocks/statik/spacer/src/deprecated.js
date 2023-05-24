const v1 = {
	attributes: {
		height: {
			type: 'array',
			items: {
				type: 'string',
			},
			default: [ '100px' ],
		},
	},
	save() {
		return null;
	},
};

const deprecated = [ v1 ];

export default deprecated;
