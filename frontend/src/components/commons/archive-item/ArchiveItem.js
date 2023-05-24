import React from 'react';
import { StatikImage } from '@statik-space/gatsby-theme-statik';
import { Link } from 'gatsby';
import clsx from 'clsx';
import xss from 'xss';

export const ArchiveItem = (props) => {
  const { entity, isFeatured } = props;

  return (
    <article
      key={entity.databaseId}
      id={`post-${entity.databaseId}`}
      className={clsx({
        'archive-item': true,
        'archive-item--featured': isFeatured,
      })}
    >
      <header
        className={clsx({
          'archive-item__header': true,
          'archive-item__header--has-image': entity.featuredImage,
        })}
      >
        {entity.featuredImage?.node && (
          <StatikImage
            className="archive-item__image"
            mediaItem={entity.featuredImage.node}
          />
        )}
      </header>
      <section className="archive-item__main-content">
        <Link className="archive-item__title" to={entity.uri}>
          {entity.title}
        </Link>

        <div className="archive-item__footer">
          <p className="archive-item__author">{entity.author.node.name}</p>
          <p className="archive-item__read-count">
            {entity.readTime === 1
              ? `${entity.readTime} min read`
              : `${entity.readTime} mins read`}
          </p>
        </div>
        {isFeatured && (
          <div
            className="archive-item__excerpt"
            dangerouslySetInnerHTML={{ __html: xss(entity.excerpt) }}
          />
        )}
      </section>
    </article>
  );
};
