import { registerBlockType } from '@wordpress/blocks';
import icon from './icon';
import save from './save';
import metadata from '../block.json';
import edit from './edit';
import deprecated from './deprecated';
import './style.scss';

const settings = {
	icon,
	edit,
	save,
	deprecated,
};

registerBlockType( metadata, settings );
