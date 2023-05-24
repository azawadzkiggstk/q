import { useMemo } from 'react';
import { useStaticQuery, graphql } from 'gatsby';

export const useHeaderMenu = () => {
  const { allMenus } = useStaticQuery(
    graphql`
      query HeaderMenuQuery {
        allMenus(first: 1, where: { location: PRIMARY }) {
          nodes {
            # @todo
            # If there is no element matching the above filters, first available menu is returned.
            # This is incorrect behaviour, so we need filter elements by "locations" field
            locations
            isHierarchical
            items: menuItems {
              nodes {
                ...MenuItem
              }
            }
          }
        }
      }
    `,
  );

  return useMemo(() => {
    const menus = allMenus.nodes.filter((menu) => {
      return menu.locations.includes('PRIMARY');
    });

    if (menus.length === 0) {
      return null;
    }

    const menu = menus[0];

    return {
      items: menu.items.nodes,
      hierarchical: menu.isHierarchical,
    };
  }, [allMenus]);
};
