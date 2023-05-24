import { getBlockType } from '@wordpress/blocks';
export function hasBlockIdAttributeDefinition(blockName) {
  const blockType = getBlockType(blockName);
  return 'blockId' in blockType.attributes && blockType.attributes.blockId.type === 'string';
}