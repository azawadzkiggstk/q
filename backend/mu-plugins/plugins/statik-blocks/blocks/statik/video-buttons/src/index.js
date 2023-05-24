import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import icon from './assets/icon';
import transforms from './transforms';
import edit from './edit';
import metadata from '../block.json';
import save from './save';
import './style.scss';

const { name } = metadata;

export { metadata, name };

const settings = {
	icon,
	example: {
		innerBlocks: [
			{
				name: 'statik/video-button',
				attributes: { text: __( 'Find out more' ) },
			},
			{
				name: 'statik/video-button',
				attributes: { text: __( 'Contact us' ) },
			},
		],
	},
	transforms,
	edit,
	save,
};

registerBlockType( metadata, settings );
