import React from 'react';
import { Menu } from '../commons/menu/Menu';
import { useSubFooterMenu } from './hooks/useSubFooterMenu';

export const SubFooterMenu = () => {
  const subFooterMenu = useSubFooterMenu();

  if (!subFooterMenu) {
    return null;
  }

  return <Menu id="sub-footer-menu" items={subFooterMenu.items} />;
};
