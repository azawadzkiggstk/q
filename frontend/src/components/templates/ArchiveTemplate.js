import React from 'react';
import clsx from 'clsx';
import { Pagination } from '../commons/pagination/Pagination';
import { CategoriesNav } from '../layout/CategoriesNav';
import { useStatikPageContext } from '@statik-space/gatsby-theme-statik';
import { ArchiveItem } from '../commons/archive-item/ArchiveItem';

export const ArchiveTemplate = (props) => {
  const { entities } = props;

  const { pageContext: ctx } = useStatikPageContext();

  const hasEntities = Boolean(entities.length);

  return (
    <article
      id={`archive-${ctx.postType}`}
      className={clsx({
        archive: true,
        [`archive--type-${ctx.postType}`]: Boolean(ctx.postType),
        [`archive--category-${ctx.category}`]: Boolean(ctx.category),
      })}
    >
      <CategoriesNav />

      {!hasEntities && <h1>Nothing found</h1>}

      <section className="archive-list">
        {entities.map((entity, index) => (
          <ArchiveItem
            key={entity.databaseId}
            entity={entity}
            isFeatured={index === 0}
          />
        ))}
      </section>

      {hasEntities && ctx.total > 1 && (
        <Pagination
          basePath={ctx.basePath}
          firstPath={ctx.firstPath}
          prevPath={ctx.prevPath}
          nextPath={ctx.nextPath}
          lastPath={ctx.lastPath}
          current={ctx.current}
          total={ctx.total}
        />
      )}
    </article>
  );
};
