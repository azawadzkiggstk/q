/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import icon from './assets/icon';

/**
 * Internal dependencies
 */
import deprecated from './deprecated';
import edit from './edit';
import metadata from '../block.json';
import save from './save';
import transforms from './transforms';
import './style.scss';
import { overrideBlockType } from '@statik-space/wordpress-statik-blocks';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon,
	example: {
		attributes: {
			value:
				// translators: Quote serving as example for the Pullquote block. Attributed to Matt Mullenweg.
				__(
					'One of the hardest things to do in technology is disrupt yourself.'
				),
			citation: __( 'Matt Mullenweg' ),
		},
	},
	transforms,
	edit,
	save,
	deprecated,
};

overrideBlockType( name, metadata, settings );
