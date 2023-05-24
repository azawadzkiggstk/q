import { registerBlockType } from '@wordpress/blocks';
import edit from './edit';
import icon from './icon';
import save from './save';
import metadata from '../block.json';
import './style.scss';

const settings = {
	icon,
	edit,
	save,
};

registerBlockType( metadata, settings );
