import React from 'react';
import { StatikBlocks } from '@statik-space/gatsby-theme-statik';

export const PageTemplate = (props) => {
  const { page } = props;

  return (
    <article id={`page-${page.databaseId}`} className="page">
      <section className="page__content">
        {page.gutenbergBlocks?.nodes && (
          <StatikBlocks blocks={page.gutenbergBlocks.nodes} />
        )}
      </section>
    </article>
  );
};
