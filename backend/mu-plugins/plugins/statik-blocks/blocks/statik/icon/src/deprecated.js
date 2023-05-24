const v1 = {
	attributes: {
		align: {
			type: 'string',
			default: 'center',
		},
		icon: {
			type: 'string',
		},
		alt: {
			type: 'string',
		},
		id: {
			type: 'number',
		},
		iconUrl: {
			type: 'string',
		},
		iconSize: {
			type: 'string',
			default: '120px',
		},
		iconSpacing: {
			type: 'string',
			default: '25px',
		},
		iconBorder: {
			type: 'string',
			default: '10px',
		},
		iconColor: {
			type: 'string',
			default: '#222222',
		},
		accentColor: {
			type: 'string',
			default: '#FF8085',
		},
		style: {
			type: 'object',
		},
	},
	save() {
		return null;
	},
};

const deprecated = [ v1 ];

export default deprecated;
