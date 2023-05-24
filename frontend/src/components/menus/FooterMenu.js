import React from 'react';
import { Menu } from '../commons/menu/Menu';
import { useFooterMenu } from './hooks/useFooterMenu';

export const FooterMenu = () => {
  const footerMenu = useFooterMenu();

  if (!footerMenu) {
    return null;
  }

  return <Menu id="footer-menu" items={footerMenu.items} />;
};
