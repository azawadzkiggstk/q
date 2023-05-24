import { addFilter } from '@wordpress/hooks';
import { dispatch, select } from '@wordpress/data';
import { merge, isNil } from 'lodash';

export function overrideBlockType( name, metadata, settings ) {
	addFilter(
		'blocks.registerBlockType',
		`statik/override/${ name }`,
		( defaultSettings, defaultName, deprecation ) => {
			if ( defaultName !== name || ! isNil( deprecation ) ) {
				return defaultSettings;
			}
			const overriddenBlocks = select( 'statik' ).getOverriddenBlocks();
			if ( ! overriddenBlocks.includes( name ) ) {
				dispatch( 'statik' ).addOverriddenBlock( name );
			}

			const mergedSettings = merge(
				{},
				defaultSettings,
				metadata,
				settings
			);
			return mergedSettings;
		}
	);
}
