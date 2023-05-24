/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import icon from './assets/icon';

/**
 * Internal dependencies
 */
import edit from './edit';
import metadata from '../block.json';
import save from './save';
import transforms from './transforms';
import { overrideBlockType } from '@statik-space/wordpress-statik-blocks';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon,
	example: {
		attributes: {
			content:
				'<marquee>' +
				__( 'Welcome to the wonderful world of blocks…' ) +
				'</marquee>',
		},
	},
	edit,
	save,
	transforms,
};

overrideBlockType( name, metadata, settings );
