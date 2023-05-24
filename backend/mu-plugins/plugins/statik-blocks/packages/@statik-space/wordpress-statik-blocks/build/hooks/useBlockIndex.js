import { useBlockEditContext } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useBlockRootClientId } from './useBlockRootClientId';
export function useBlockIndex(blockClientId) {
  const {
    clientId
  } = useBlockEditContext();
  const finalClientId = blockClientId !== null && blockClientId !== void 0 ? blockClientId : clientId;
  const rootClientId = useBlockRootClientId(finalClientId);
  return useSelect(select => {
    const {
      getBlockIndex
    } = select('core/block-editor');
    return getBlockIndex(finalClientId, rootClientId);
  }, [finalClientId, rootClientId]);
}