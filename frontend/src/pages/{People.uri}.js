import React from 'react';
import { graphql } from 'gatsby';
import { Layout } from '../components/layout/Layout';
import { PersonTemplate } from '../components/templates/PersonTemplate';

const Person = (props) => {
  const {
    data: { person },
  } = props;

  return (
    <Layout>
      <PersonTemplate person={person} />
    </Layout>
  );
};

export default Person;

export const query = graphql`
  query PersonQuery($id: ID!) {
    person(id: $id) {
      ...Person
    }
  }
`;
