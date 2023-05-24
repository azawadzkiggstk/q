import React from 'react';
import { graphql } from 'gatsby';
import { Layout } from '../components/layout/Layout';
import { Seo } from '../components/commons/seo/Seo';
import { PageTemplate } from '../components/templates/PageTemplate';

const Page = (props) => {
  const {
    data: { page },
  } = props;

  return (
    <Layout>
      <PageTemplate page={page} />
    </Layout>
  );
};

export default Page;

export const Head = (props) => {
  const {
    data: { page },
  } = props;
  
  return <Seo {...page.seo.node} />
};

export const query = graphql`
  query PageQuery($id: ID!) {
    page(id: $id) {
      ...Page
    }
  }
`;
