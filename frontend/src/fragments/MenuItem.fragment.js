import { graphql } from 'gatsby';

export const MenuItemFragment = graphql`
  fragment MenuItem on WP_MenuItem {
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
    childItems {
      nodes {
        id
      }
    }
    megamenu {
      node {
        gutenbergBlocks(first: 1000) {
          nodes {
            ...GutenbergBlock
          }
        }
      }
    }
  }
`;
