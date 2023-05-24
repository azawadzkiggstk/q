import { useBlockEditContext } from '@wordpress/block-editor';
export function useBlockData() {
  const {
    name
  } = useBlockEditContext();
  if (window.statikBlocks) {
    return window.statikBlocks[name];
  }
  return null;
}