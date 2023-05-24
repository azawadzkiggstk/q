import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import icon from './assets/icon';
import edit from './edit';
import metadata from '../block.json';
import save from './save';
import deprecated from './deprecated';
import './style.scss';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon,
	example: {
		attributes: {
			className: 'is-style-fill',
			text: __( 'Call to Action' ),
		},
	},
	edit,
	save,
	deprecated,
	merge: ( a, { text = '' } ) => ( {
		...a,
		text: ( a.text || '' ) + text,
	} ),
};

registerBlockType( metadata, settings );
