import { registerBlockType } from '@wordpress/blocks';
import save from './save';
import icon from './icon';
import metadata from '../block.json';
import deprecated from './deprecated';
import edit from './edit';
import variations from './variations';
import './style.scss';
import { resolveSave } from '@statik-space/wordpress-statik-blocks';

const settings = {
	icon,
	edit,
	save: resolveSave( save ),
	deprecated,
	variations,
};

registerBlockType( metadata, settings );
