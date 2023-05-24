import React from 'react';
import { graphql } from 'gatsby';
import { Layout } from '../components/layout/Layout';
import { ArchiveTemplate } from '../components/templates/ArchiveTemplate';
import { Seo } from '../components/commons/seo/Seo';

const PostsArchive = (props) => {
  const {
    data: { allPosts },
  } = props;

  return (
    <Layout>
      <ArchiveTemplate postType="post" entities={allPosts.nodes} />
    </Layout>
  );
};

export default PostsArchive;

export const Head = (props) => {
  const {
    path,
  } = props;

  return (
    <Seo canonicalUrl={path}>
      <title id="head-title">Insights Archive</title>
    </Seo>
  );
};

export const query = graphql`
  query PostsArchiveQuery($ids: [ID]) {
    allPosts(where: { in: $ids }) {
      nodes {
        ...Post
      }
    }
  }
`;
