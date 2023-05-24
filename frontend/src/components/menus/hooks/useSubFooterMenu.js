import { useStaticQuery, graphql } from 'gatsby';

export const useSubFooterMenu = () => {
  const { allMenus } = useStaticQuery(
    graphql`
      query SubFooterMenuQuery {
        allMenus(first: 1, where: { location: SUB_FOOTER }) {
          nodes {
            # @todo
            # If there is no element matching the above filters, first available menu is returned.
            # This is incorrect behaviour, so we need filter elements by "locations" field
            locations
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
    return menu.locations.includes('SUB_FOOTER');
  });

  if (menus.length === 0) {
    return null;
  }

  const menu = menus[0];

  return {
    items: menu.items.nodes,
  };
};
