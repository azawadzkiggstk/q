/**
 * WordPress dependencies
 */
import icon from './assets/icon';

/**
 * Internal dependencies
 */
import edit from './edit';
import metadata from '../block.json';
import save from './save';
import transforms from './transforms';
import './style.scss';
import { overrideBlockType } from '@statik-space/wordpress-statik-blocks';
import deprecated from './deprecated';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon,
	example: {
		attributes: {
			customColor: '#065174',
			className: 'is-style-wide',
		},
	},
	transforms,
	edit,
	save,
	deprecated,
};

overrideBlockType( name, metadata, settings );
