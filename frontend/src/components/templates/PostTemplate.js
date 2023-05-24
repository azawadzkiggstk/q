import React from 'react';
import clsx from 'clsx';
import { StatikBlocks, StatikImage } from '@statik-space/gatsby-theme-statik';
import { Link } from 'gatsby';
import { ShareBox } from '../commons/share-box/ShareBox';
import { useStatikPageLocation } from '@statik-space/gatsby-theme-statik/src/hooks/useStatikPageLocation';

export const PostTemplate = (props) => {
  const { post } = props;

  const location = useStatikPageLocation();

  return (
    <article id={`post-${post.databaseId}`} className="post">
      <header
        className={clsx({
          post__header: true,
          'post__header--has-image': post.featuredImage,
        })}
      >
        {post.featuredImage?.node && (
          <StatikImage
            className="post__image"
            mediaItem={post.featuredImage.node}
          />
        )}
        <h1 className="post__title">{post.title}</h1>
      </header>

      <section className="post__meta">
        <p className="post__date">
          <time dateTime={post.date}>
            {post.dateFormat} {post.timeFormat}
          </time>
        </p>
        <p className="post__read-count">
          {post.readTime === 1
            ? `${post.readTime} min read`
            : `${post.readTime} mins read`}
        </p>
        <p className="post__author">{post.author.node.name}</p>
        <p className="post__category">
          {post.categories.nodes.map((category) => (
            <Link key={category.slug} to={`/category/${category.slug}/`}>
              {category.name}
            </Link>
          ))}
        </p>
      </section>
      <aside className="post__share">
        <ShareBox url={location.href} />
      </aside>
      <section className="post__content">
        {post.gutenbergBlocks?.nodes && (
          <StatikBlocks blocks={post.gutenbergBlocks.nodes} />
        )}
      </section>
    </article>
  );
};
