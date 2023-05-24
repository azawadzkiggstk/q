import React from 'react';
import { useHeaderMenu } from './hooks/useHeaderMenu';
import { Menu } from '../commons/menu/Menu';

export const HeaderMenu = () => {
  const primaryMenu = useHeaderMenu();

  if (!primaryMenu) {
    return null;
  }

  return (
    <Menu id="header-menu" interaction="hover" items={primaryMenu.items} />
  );
};
