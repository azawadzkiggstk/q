import { useContext } from 'react';
import { MenuContext } from '../MenuContext';

export function useMenuContext() {
  const context = useContext(MenuContext);
  if (context === undefined) {
    throw new Error('useMenuContext was used outside of its MenuProvider.');
  }
  return context;
}
