import React from 'react';
import { safeAttrValue } from 'xss';
import { Link as InternalLink } from 'gatsby';
import { useMenuItemContext } from './stores/hooks/useMenuItemContext';
import { useMenuContext } from './stores/hooks/useMenuContext';
import { MenuLinkIndicator } from './MenuLinkIndicator';

export const MenuLink = () => {
  const { interaction } = useMenuContext();
  const { item, hasOpenState, setOpenState } = useMenuItemContext();

  if (item.isInteractive) {
    const ItemRender = item.render;
    return <ItemRender />;
  }

  const handleClick = (event) => {
    if (interaction === 'click' && item.hasChildren) {
      event.preventDefault();
      setOpenState(!hasOpenState);
    }
  };

  if (item.isInternal) {
    return (
      <InternalLink
        to={item.url}
        className="menu__link"
        activeClassName="menu__link--active"
        partiallyActive={false}
        onClick={handleClick}
        role="menuitem"
        aria-haspopup={item.hasChildren}
        aria-expanded={hasOpenState}
      >
        {item.label}
        <MenuLinkIndicator />
      </InternalLink>
    );
  }

  if (item.url) {
    return (
      <a
        href={safeAttrValue('a', 'href', item.url)}
        className="menu__link"
        target={item.target}
        rel="noreferrer"
        onClick={handleClick}
      >
        {item.label}
        <MenuLinkIndicator />
      </a>
    );
  }

  return <span>{item.label}</span>;
};
