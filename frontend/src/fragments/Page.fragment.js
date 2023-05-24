import { graphql } from 'gatsby';

export const PageFragment = graphql`
  fragment Page on WP_Page {
    id
    databaseId
    slug
    uri
    seo {
      node {
        ...NodeSeoField
      }
    }
    gutenbergBlocks {
      nodes {
        ...GutenbergBlock
      }
    }
  }
`;
