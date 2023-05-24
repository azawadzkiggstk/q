import React from 'react';
import { graphql } from 'gatsby';
import { Layout } from '../components/layout/Layout';
import { Seo } from '../components/commons/seo/Seo';
import { PostTemplate } from '../components/templates/PostTemplate';

const Post = (props) => {
  const {
    data: { post },
  } = props;

  return (
    <Layout>
      <PostTemplate post={post} />
    </Layout>
  );
};

export default Post;

export const Head = (props) => {
  const {
    data: { post },
  } = props;

  return <Seo {...post.seo.node} />
};

export const query = graphql`
  query PostQuery($id: ID!) {
    post(id: $id) {
      ...Post
    }
  }
`;
