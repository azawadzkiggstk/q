import React from 'react';
import xss, { safeAttrValue } from 'xss';
import { formatBytes } from '../../utils/format-bytes';

export const DocumentTemplate = (props) => {
  const { document } = props;
  const { description, media } = document.acf;

  return (
    <article id={`document-${document.databaseId}`} className="document">
      <h1 className="document__title">{document.title}</h1>
      <p className="document__date">
        <time dateTime={document.date}>
          {document.dateFormat} {document.timeFormat}
        </time>
      </p>
      <div
        className="document__description"
        dangerouslySetInnerHTML={{ __html: xss(description) }}
      />
      <a
        className="document__download"
        href={safeAttrValue('a', 'href', media.mediaItemUrl)}
      >
        Download{' '}
        <span className="document__size">{formatBytes(media.fileSize)}</span>
      </a>
    </article>
  );
};
