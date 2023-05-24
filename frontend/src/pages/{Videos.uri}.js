import React from 'react';
import { graphql } from 'gatsby';
import { VideoTemplate } from '../components/templates/VideoTemplate';

const Video = (props) => {
  const {
    data: { video },
  } = props;

  return <VideoTemplate video={video} />;
};

export default Video;

export const query = graphql`
  query VideoQuery($id: ID!) {
    video(id: $id) {
      ...Video
    }
  }
`;
