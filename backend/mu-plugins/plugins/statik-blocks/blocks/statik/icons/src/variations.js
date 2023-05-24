import { __ } from '@wordpress/i18n';

const variations = [
	{
		name: 'icons-horizontal',
		isDefault: true,
		title: __( 'Horizontal', 'statik-blocks' ),
		description: __( 'Buttons shown in a row.', 'statik-blocks' ),
		attributes: { orientation: 'horizontal' },
		scope: [ 'transform' ],
	},
	{
		name: 'icons-vertical',
		title: __( 'Vertical', 'statik-blocks' ),
		description: __( 'Buttons shown in a column.', 'statik-blocks' ),
		attributes: { orientation: 'vertical' },
		scope: [ 'transform' ],
	},
];

export default variations;
