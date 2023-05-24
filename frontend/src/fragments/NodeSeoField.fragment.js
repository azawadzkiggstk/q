import { graphql } from 'gatsby';

export const NodeSeoFieldFragment = graphql`
  fragment NodeSeoField on WP_NodeSeoField {
    canonicalUrl
    description
    imageUrl
    noarchive
    nofollow
    noindex
    ogDescription
    ogTitle
    redirectUrl
    title
    twitterDescription
    twitterTitle
  }
`;
