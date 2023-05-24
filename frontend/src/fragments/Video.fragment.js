import { graphql } from 'gatsby';

export const VideoFragment = graphql`
  fragment Video on WP_Video {
    databaseId
    title
    acf {
      videoSource
      videoUrl
      localFile {
        mediaItemUrl
      }
    }
  }
`;
