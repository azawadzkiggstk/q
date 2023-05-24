import { useMemo } from '@wordpress/element';
import { getBlockId } from '../utils';
export function useBlockId(name, clientId) {
  return useMemo(() => getBlockId(name, clientId), [name, clientId]);
}