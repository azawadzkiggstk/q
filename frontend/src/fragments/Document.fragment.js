import { graphql } from 'gatsby';

export const DocumentFragment = graphql`
  fragment Document on WP_Document {
    databaseId
    title
    dateFormat(format: DATE)
    timeFormat: dateFormat(format: TIME)
    acf {
      description
      media {
        fileSize
        mediaItemUrl
      }
    }
  }
`;
