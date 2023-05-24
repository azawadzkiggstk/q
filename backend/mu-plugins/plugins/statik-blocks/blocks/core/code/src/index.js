/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import icon from './assets/icon';

/**
 * Internal dependencies
 */
import edit from './edit';
import metadata from './../block.json';
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
			/* eslint-disable @wordpress/i18n-no-collapsible-whitespace */
			// translators: Preserve \n markers for line breaks
			content: __(
				'// A “block” is the abstract term used\n// to describe units of markup that\n// when composed together, form the\n// content or layout of a page.\nregisterBlockType( name, settings );'
			),
			/* eslint-enable @wordpress/i18n-no-collapsible-whitespace */
		},
	},
	transforms,
	edit,
	save,
};

overrideBlockType( name, metadata, settings );
