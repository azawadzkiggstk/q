import { addFilter } from '@wordpress/hooks';
import { registerPlugin } from '@wordpress/plugins';
import { registerStatikStore } from './stores/statik';
import { addBlockAttsAttributeDefinition } from './filters/add-block-atts-attribute-definition';
import { addBlockPropsAttributeDefinition } from './filters/add-block-props-attribute-definition';
import { addBlockPropsAttributeValue } from './filters/add-block-props-attribute-value';
import { withBlockAttsAttribute } from './filters/with-block-atts-attribute';
import { withBlockPropsAttribute } from './filters/with-block-props-attribute';
import { addBlockIdAttributeDefinition } from './filters/add-block-id-attribute-definition';
import { addBlockIdAttributeValue } from './filters/add-block-id-attribute-value';
import { withBlockId } from './filters/with-block-id';
import { withBlockFontVariations } from './filters/with-block-font-variations';
import { statikSettingsPlugin } from './plugins/statik-settings';
import { unregisterNotOverriddenBlocks } from './utils/unregister-not-overridden-blocks';

/**
 * Registers `statik` store via Gutenberg api
 */
registerStatikStore();

/**
 * Unregisters blocks which haven't been overridden (except `statik` blocks)
 */
if ( window.statikBlocks?.config?.coreBlocksEnabled === '1' ) {
	unregisterNotOverriddenBlocks();
}

/**
 * Adds `blockProps` attribute definition to the blocks' settings
 */
addFilter(
	'blocks.registerBlockType',
	'statik/add-block-props-attribute-definition',
	addBlockPropsAttributeDefinition
);

/**
 * Adds `blockAtts` attribute definition to the blocks' settings
 */
addFilter(
	'blocks.registerBlockType',
	'statik/add-block-atts-attribute-definition',
	addBlockAttsAttributeDefinition
);

/**
 * Adds `blockProps` attribute value to the block
 */
addFilter(
	'blocks.getBlockAttributes',
	'statik/add-block-props-attribute-value',
	addBlockPropsAttributeValue
);

/**
 * Adds `blockProps` attribute value to the block (on edit)
 */
addFilter(
	'editor.BlockEdit',
	'statik/with-block-props-attribute',
	withBlockPropsAttribute
);

/**
 * Adds `blockAtts` attribute value to the block (on edit)
 */
addFilter(
	'editor.BlockEdit',
	'statik/with-block-atts-attribute',
	withBlockAttsAttribute
);

/**
 * Adds `blockId` attribute definition to the blocks' settings
 */
addFilter(
	'blocks.registerBlockType',
	'statik/add-block-id-attribute-definition',
	addBlockIdAttributeDefinition
);

/**
 * Adds `blockId` attribute value to the block
 */
addFilter(
	'blocks.getBlockAttributes',
	'statik/add-block-id-attribute-value',
	addBlockIdAttributeValue
);

/**
 * Adds `blockId` attribute value to the block (on edit)
 */
addFilter( 'editor.BlockEdit', 'statik/with-block-id', withBlockId );

/**
 * Adds `Font Variations` panel to the blocks with `typography` support
 */
addFilter(
	'editor.BlockEdit',
	'statik/with-block-font-variations',
	withBlockFontVariations
);

/**
 * Registers the `Statik Settings` panel that brings some helpful functionalities
 */
registerPlugin( 'statik-settings', statikSettingsPlugin );
