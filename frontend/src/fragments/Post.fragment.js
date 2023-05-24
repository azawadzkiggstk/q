import { graphql } from 'gatsby';

export const PostFragment = graphql`
  fragment Post on WP_Post {
    id
    databaseId
    title(format: RENDERED)
    slug
    uri
    dateFormat(format: DATE)
    timeFormat: dateFormat(format: TIME)
    readTime
    seo {
      node {
        ...NodeSeoField
      }
    }
    featuredImage {
      node {
        ...MediaItem
      }
    }
    author {
      node {
        name
      }
    }
    categories {
      nodes {
        slug
        name
        uri
      }
    }
    gutenbergBlocks {
      nodes {
        ...GutenbergBlock
      }
    }
  }
`;
