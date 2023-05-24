import { applyFilters } from '@wordpress/hooks';
import { assign } from 'lodash';
import { clearEmpties } from '@statik-space/wordpress-statik-blocks';

export function addBlockPropsAttributeValue( blockAttributes, blockType ) {
	const hasBlockPropsDefinition =
		'blockProps' in blockType.attributes &&
		blockType.attributes.blockProps.type === 'object';

	if ( ! hasBlockPropsDefinition ) {
		return blockAttributes;
	}

	const blockProps = applyFilters(
		'blocks.getSaveContent.extraProps',
		{},
		blockType,
		blockAttributes
	);

	clearEmpties( blockProps );

	return assign( blockAttributes, { blockProps } );
}
