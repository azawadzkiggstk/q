import React from 'react';
import clsx from 'clsx';
import { MenuItem } from './MenuItem';
import { MenuItemProvider } from './stores/MenuItemProvider';

export const MenuList = (props) => {
  const { items, level = 0, className, ...restProps } = props;

  const classNames = clsx(
    {
      menu__list: true,
      [`menu__list--lvl-${level}`]: true,
    },
    className,
  );

  return (
    <ul className={classNames} {...restProps}>
      {items.map((item) => (
        <MenuItemProvider key={item.id} item={item} level={level}>
          <MenuItem />
        </MenuItemProvider>
      ))}
    </ul>
  );
};
