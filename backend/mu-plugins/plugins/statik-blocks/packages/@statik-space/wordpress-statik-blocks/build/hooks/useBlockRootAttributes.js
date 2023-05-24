import { useBlockRootClientId } from './useBlockRootClientId';
import { useBlockAttributes } from './useBlockAttributes';
export function useBlockRootAttributes() {
  const rootClientId = useBlockRootClientId();
  return useBlockAttributes(rootClientId);
}