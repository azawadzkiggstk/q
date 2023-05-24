import { useContext } from 'react';
import { MenuItemContext } from '../MenuItemContext';

export function useMenuItemContext() {
  const context = useContext(MenuItemContext);
  if (context === undefined) {
    throw new Error(
      'useMenuItemContext was used outside of its MenuItemProvider.',
    );
  }
  return context;
}
