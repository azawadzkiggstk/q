import { useStaticQuery, graphql } from 'gatsby';

export const useHeaderMobileMenu = () => {
  const { allMenus } = useStaticQuery(
    graphql`
      query HeaderMobileMenuQuery {
        allMenus(first: 1, where: { location: MOBILE }) {
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

  const menus = allMenus.nodes.filter((menu) => {
    return menu.locations.includes('MOBILE');
  });

  if (menus.length === 0) {
    return null;
  }

  const menu = menus[0];

  return {
    items: menu.items.nodes,
    hierarchical: menu.isHierarchical,
  };
};
