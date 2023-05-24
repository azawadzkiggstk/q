import React from 'react';
import { graphql } from 'gatsby';
import { Layout } from '../components/layout/Layout';
import { DocumentTemplate } from '../components/templates/DocumentTemplate';

const Document = (props) => {
  const {
    data: { document },
  } = props;

  return (
    <Layout>
      <DocumentTemplate document={document} />
    </Layout>
  );
};

export default Document;

export const query = graphql`
  query DocumentQuery($id: ID!) {
    document(id: $id) {
      ...Document
    }
  }
`;
