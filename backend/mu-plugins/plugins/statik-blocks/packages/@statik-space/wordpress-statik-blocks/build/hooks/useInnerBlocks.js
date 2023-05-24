import { useBlockEditContext } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
export function useInnerBlocks(blockClientId) {
  const {
    clientId
  } = useBlockEditContext();
  const finalClientId = blockClientId !== null && blockClientId !== void 0 ? blockClientId : clientId;
  return useSelect(select => {
    const {
      getBlocks
    } = select('core/block-editor');
    return getBlocks(finalClientId);
  }, [finalClientId]);
}