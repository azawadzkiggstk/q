import { registerBlockType } from '@wordpress/blocks';
import save from './save';
import icon from './icon';
import deprecated from './deprecated';
import metadata from '../block.json';
import edit from './edit';
import './style.scss';
import { resolveSave } from '@statik-space/wordpress-statik-blocks';

const settings = {
	icon,
	edit,
	save: resolveSave( save ),
	deprecated,
};

registerBlockType( metadata, settings );
