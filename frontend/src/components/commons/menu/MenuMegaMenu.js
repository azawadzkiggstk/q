import React from 'react';
import clsx from 'clsx';
import { StatikBlocks } from '@statik-space/gatsby-theme-statik';
import { useMenuItemContext } from './stores/hooks/useMenuItemContext';

export const MenuMegaMenu = () => {
  const { item } = useMenuItemContext();
  const blocks = item.megamenu.node.gutenbergBlocks.nodes;

  const className = clsx({
    menu__megamenu: true,
  });

  return (
    <div className={className}>
      {blocks && <StatikBlocks blocks={blocks} />}
    </div>
  );
};
