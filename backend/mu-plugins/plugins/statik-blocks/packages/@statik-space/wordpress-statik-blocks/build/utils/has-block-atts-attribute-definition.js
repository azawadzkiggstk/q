import { getBlockType } from '@wordpress/blocks';
export function hasBlockAttsAttributeDefinition(blockName) {
  const blockType = getBlockType(blockName);
  return 'blockAtts' in blockType.attributes && blockType.attributes.blockId.type === 'object';
}