import React from 'react';
import { Layout } from '../components/layout/Layout';
import { Seo } from '../components/commons/seo/Seo';

const ErrorPage = () => {
  return (
    <Layout id="error">
      <div className="entry container">
        <div className="statik-block static-block-heading is-displayed">
          <h2 className="has-text-align-center w-75">There was an error</h2>
        </div>
        <div className="statik-block static-block-paragraph is-displayed">
          <p className="has-text-align-center w-50">
            This page cannot be rendered
          </p>
        </div>
      </div>
    </Layout>
  );
};

export const Head = (props) => {
  const {
    path,
  } = props;

  return (
    <Seo canonicalUrl={path}>
      <title id="head-title">There was an error</title>
    </Seo>
  );
};

export default ErrorPage;
