import { useBlockEditContext } from '@wordpress/block-editor';
export function useBlockClientId() {
  const {
    clientId
  } = useBlockEditContext();
  return clientId;
}