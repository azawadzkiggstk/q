import { useMemo } from 'react';
import { withPrefix } from 'gatsby';
import { isNil } from 'lodash';
import normalizeUrl from 'normalize-url';
import { useStatikPageLocation } from '@statik-space/gatsby-theme-statik/src/hooks/useStatikPageLocation';

export function useMenu(menuItems) {
  const pageUrl = useStatikPageLocation();

  return useMemo(() => {
    return getMenuStructure(menuItems, pageUrl);
  }, [menuItems, pageUrl]);
}

const getMenuStructure = (menuItems, pageURL) => {
  if (!Array.isArray(menuItems)) {
    return [];
  }

  const menuItemsTree = [];
  const menuItemsMap = getMenuItemsMap(menuItems, pageURL);

  updateMenuItemsMapAndTree(menuItemsMap, menuItemsTree);

  return menuItemsTree;
};

const getMenuItemsMap = (menuItems, pageURL) => {
  return new Map(
    menuItems
      .sort((a, b) => a.order - b.order)
      .map((menuItem) => {
        const hasTarget =
          !isNil(menuItem.target) && menuItem.target !== '_self';
        const isCustomLink = isNil(menuItem.connectedNode);

        const url =
          hasTarget && !isCustomLink ? withPrefix(menuItem.url) : menuItem.url;

        const isInternal = !hasTarget && !isCustomLink;
        const isActive = isUrlActive(isInternal, url, pageURL);
        const isInteractive = Boolean(menuItem.render);
        const hasMegaMenu = Boolean(
          menuItem.megamenu?.node?.gutenbergBlocks?.nodes,
        );

        const render = menuItem.render;

        return [
          menuItem.id,
          {
            ...menuItem,
            url,
            render,
            isInteractive,
            isInternal,
            isActive,
            hasMegaMenu,
            children: [], // might be set later
            hasChildren: false, // might be set later
            hasActiveChild: false, // might be set later
          },
        ];
      }),
  );
};

const updateMenuItemsMapAndTree = (menuItemsMap, menuItemsTree) => {
  menuItemsMap.forEach((menuItem) => {
    if (menuItem.parentId === null) {
      // is root menu item
      menuItemsTree.push(menuItem);
    } else {
      const parentMenuItem = menuItemsMap.get(menuItem.parentId);
      if (parentMenuItem !== undefined) {
        parentMenuItem.children.push(menuItem);
        parentMenuItem.hasChildren = true;
        if (menuItem.isActive) {
          updateParentRecurrently(menuItem, menuItemsMap);
        }
      }
    }
  });
};

const updateParentRecurrently = (currentMenuItem, menuItemsMap) => {
  if (currentMenuItem.parentId !== null) {
    const parentMenuItem = menuItemsMap.get(currentMenuItem.parentId);
    if (parentMenuItem !== undefined) {
      parentMenuItem.hasActiveChild = true;
      updateParentRecurrently(parentMenuItem, menuItemsMap);
    }
  }
};

const isUrlActive = (isInternal, url, pageURL) => {
  if (isInternal) {
    return withPrefix(url) === getRelativeUrl(pageURL);
  }
  const itemURL = new URL(url, pageURL.origin);
  return normalizeUrl(itemURL.toString()) === normalizeUrl(pageURL.toString());
};

const getRelativeUrl = (url) => {
  return url.toString().slice(url.origin.length);
};
