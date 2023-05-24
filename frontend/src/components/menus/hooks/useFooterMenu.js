import { useStaticQuery, graphql } from 'gatsby';

export const useFooterMenu = () => {
  const { allMenus } = useStaticQuery(
    graphql`
      query FooterMenuQuery {
        allMenus(first: 1, where: { location: FOOTER }) {
          nodes {
            # @todo
            # If there is no element matching the above filters, first available menu is returned.
            # This is incorrect behaviour, so we need filter elements by "locations" field
            locations
            isHierarchical
            items: menuItems {
              nodes {
                id
                parentId
                databaseId
                parentDatabaseId
                url
                target
                label
                cssClasses
                order
                connectedNode {
                  node {
                    id
                  }
                }
              }
            }
          }
        }
      }
    `,
  );

  const menus = allMenus.nodes.filter((menu) => {
    return menu.locations.includes('FOOTER');
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
