import { simpleHash } from './simple-hash';
import { dispatch, select } from '@wordpress/data';
export function getBlockId(name, clientId) {
  const prefix = getBlockIdPrefix();
  if (clientId) {
    const block = select('core/block-editor').getBlock(clientId);
    if (block) {
      return simpleHash(`${prefix}-${name}-${createEditorBlockId(name)}`);
    }
  }
  return simpleHash(`${prefix}-${name}-${createBlockId(name)}`);
}
function getBlockIdPrefix() {
  var _select$getBlockIdPre;
  return (_select$getBlockIdPre = select('statik').getBlockIdPrefix()) !== null && _select$getBlockIdPre !== void 0 ? _select$getBlockIdPre : select('core/editor').getCurrentPostId();
}
function createEditorBlockId(blockName) {
  dispatch('statik').incrementEditorBlockId(blockName);
  return select('statik').getEditorBlockIdByName(blockName);
}
function createBlockId(blockName) {
  dispatch('statik').incrementBlockId(blockName);
  return select('statik').getBlockIdByName(blockName);
}