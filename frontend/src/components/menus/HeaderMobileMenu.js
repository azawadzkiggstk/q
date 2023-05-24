import React, { useMemo } from 'react';
import { Menu } from '../commons/menu/Menu';
import { useHeaderMobileMenu } from './hooks/useHeaderMobileMenu';
import { useMenuItemContext } from '../commons/menu/stores/hooks/useMenuItemContext';

export const HeaderMobileMenu = () => {
  const mobileMenu = useHeaderMobileMenu();

  const items = useMemo(() => {
    return mobileMenu?.items.map((item) => {
      return {
        ...item,
        render: item.childItems.nodes.length ? ItemRender : undefined,
      };
    });
  }, [mobileMenu?.items]);

  if (!mobileMenu) {
    return null;
  }

  return <Menu id="header-mobile-menu" interaction="click" items={items} />;
};

const ItemRender = () => {
  const { item, hasOpenState, setOpenState } = useMenuItemContext();

  return (
    <>
      <a className="menu__link" href={item.url}>
        {item.label}
      </a>
      <button
        className="menu__indicator"
        onClick={() => setOpenState(!hasOpenState)}
      >
        Open Submenu
      </button>
    </>
  );
};
