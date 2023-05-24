import React from 'react';
import clsx from 'clsx';
import { MenuList } from './MenuList';
import { useMenu } from '../../../hooks/useMenu';
import { MenuProvider } from './stores/MenuProvider';

export const Menu = (props) => {
  const {
    items,
    interaction = 'default', // default | hover | click
    className,
    ...restProps
  } = props;

  const menuItems = useMenu(items);

  if (!menuItems.length) {
    return null;
  }

  const navClassName = clsx(
    {
      menu: true,
      'menu--default': interaction === 'default',
      'menu--hoverable': interaction === 'hover',
      'menu--clickable': interaction === 'click',
    },
    className,
  );

  return (
    <MenuProvider items={menuItems} interaction={interaction}>
      <nav className={navClassName} {...restProps}>
        <MenuList items={menuItems} level={0} role="menubar" />
      </nav>
    </MenuProvider>
  );
};
