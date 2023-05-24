/**
 * WordPress dependencies
 */
import icon from './assets/icon';
import { __ } from '@wordpress/i18n';

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

const settings = {
	icon,
	example: {
		innerBlocks: [
			{
				name: 'core/list-item',
				attributes: { content: __( 'Alice.' ) },
			},
			{
				name: 'core/list-item',
				attributes: { content: __( 'The White Rabbit.' ) },
			},
			{
				name: 'core/list-item',
				attributes: { content: __( 'The Cheshire Cat.' ) },
			},
			{
				name: 'core/list-item',
				attributes: { content: __( 'The Mad Hatter.' ) },
			},
			{
				name: 'core/list-item',
				attributes: { content: __( 'The Queen of Hearts.' ) },
			},
		],
	},
	transforms,
	edit,
	save,
	deprecated,
};

export { settings };

overrideBlockType( name, metadata, settings );
