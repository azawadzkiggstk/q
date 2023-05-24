import { createElement } from "@wordpress/element";
import { InnerBlocks } from '@wordpress/block-editor';
export function resolveSave(save) {
  let fallback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : createElement(InnerBlocks.Content, null);
  return function (props) {
    if (isSaveExecutionEnabled()) {
      return save(props);
    }
    return fallback;
  };
}
function isSaveExecutionEnabled() {
  if (typeof statikBlocks !== 'undefined') {
    return Boolean(statikBlocks?.config?.saveJsExecution);
  }
  throw new Error('`statikBlocks` variable does not exist');
}