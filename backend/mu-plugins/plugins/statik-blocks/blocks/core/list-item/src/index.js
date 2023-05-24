/**
 * WordPress dependencies
 */
import { listItem as icon } from '@wordpress/icons';

/**
 * Internal dependencies
 */
import metadata from '../block.json';
import edit from './edit';
import save from './save';

import { overrideBlockType } from '@statik-space/wordpress-statik-blocks';

const { name } = metadata;

export { metadata, name };

export const settings = {
	icon,
	edit,
	save,
	merge( attributes, attributesToMerge ) {
		return {
			...attributes,
			content: attributes.content + attributesToMerge.content,
		};
	},
};

overrideBlockType( name, metadata, settings );
