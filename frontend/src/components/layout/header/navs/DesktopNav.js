import React from 'react';
import { HeaderMenu } from '../../../menus/HeaderMenu';
import { SwitchableSearch } from '../../switchable-search/SwitchableSearch';

export const DesktopNav = () => {
  return (
    <div className="main-header__desktop-nav" role="navigation">
      <HeaderMenu />
      <SwitchableSearch />
    </div>
  );
};
