import { graphql } from 'gatsby';

export const PersonFragment = graphql`
  fragment Person on WP_Person {
    id
    databaseId
    title(format: RENDERED)
    slug
    featuredImage {
      node {
        ...MediaItem
      }
    }
    acf {
      contactDetails {
        email
        linkedin
        twitter
        website
      }
      shortDescription
      longDescription
    }
    gutenbergBlocks {
      nodes {
        ...GutenbergBlock
      }
    }
  }
`;
