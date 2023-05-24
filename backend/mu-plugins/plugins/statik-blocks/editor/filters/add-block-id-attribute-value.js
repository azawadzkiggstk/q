import { assign } from 'lodash';
import { v1 as uuidv1 } from 'uuid';
import { simpleHash } from '@statik-space/wordpress-statik-blocks';

export function addBlockIdAttributeValue(
	blockAttributes,
	blockType,
	innerHTML,
	attributes
) {
	const hasAttrValue = 'blockId' in attributes;
	const hasAttrDefinition =
		'blockId' in blockType.attributes &&
		blockType.attributes.blockId.type === 'string';

	if ( ! hasAttrDefinition || hasAttrValue ) {
		return blockAttributes;
	}

	const blockId = simpleHash( uuidv1() );

	return assign( blockAttributes, { blockId } );
}
