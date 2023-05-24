import React from 'react';
import clsx from 'clsx';
import { MenuLink } from './MenuLink';
import { MenuList } from './MenuList';
import { MenuMegaMenu } from './MenuMegaMenu';
import { useMenuItemContext } from './stores/hooks/useMenuItemContext';

export const MenuItem = () => {
  const {
    item,
    level,
    hasOpenState,
    hasHoverState,
    setHoverState,
    hasHoverChildState,
    setHoverChildState,
  } = useMenuItemContext();

  const className = clsx(
    {
      menu__item: true,
      'menu__item--active': item.isActive,
      'menu__item--hover': hasHoverState,
      'menu__item--open': hasOpenState,
      'menu__item--internal': item.isInternal,
      'menu__item--has-megamenu': item.hasMegaMenu,
      'menu__item--has-children': item.hasChildren,
      'menu__item--has-active-child': item.hasActiveChild,
      'menu__item--has-hover-child': !hasHoverState && hasHoverChildState,
    },
    item.cssClasses,
  );

  const handleMouseOver = (event) => {
    event.stopPropagation();
    setHoverState(true);
  };

  const handleMouseOverCapture = () => {
    setHoverChildState(true);
  };

  const handleMouseOut = () => {
    setHoverState(false);
    setHoverChildState(false);
  };

  return (
    <li
      className={className}
      onMouseOver={handleMouseOver}
      onFocus={handleMouseOver}
      onMouseOverCapture={handleMouseOverCapture}
      onMouseOut={handleMouseOut}
      onBlur={handleMouseOut}
      role="none"
    >
      <MenuLink />
      {item.hasMegaMenu && <MenuMegaMenu />}
      {item.hasChildren && (
        <MenuList
          items={item.children}
          level={level + 1}
          role="menu"
          aria-label={item.label}
        />
      )}
    </li>
  );
};
