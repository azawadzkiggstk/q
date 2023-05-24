import React from 'react';
import clsx from 'clsx';
import { Link } from 'gatsby';
import { getPath } from '../../../utils/get-path';
import { getPaginationRange } from '../../../utils/get-pagination-range';

export const Pagination = (props) => {
  const {
    basePath,
    firstPath,
    prevPath,
    nextPath,
    lastPath,
    current,
    total,
    scope = 2,
  } = props;

  const resolvePath = (segments) => {
    return getPath(segments);
  };

  const pages = getPaginationRange(1, total, current, scope);

  const hasPreEllipsis = pages[0] !== 1;
  const hasPostEllipsis = pages[pages.length - 1] !== total;

  return (
    <nav
      className="pagination"
      role="navigation"
      aria-label="Pagination Navigation"
    >
      <ul className="pagination__list">
        <li
          className={clsx({
            'pagination__list-item': true,
            'pagination__list-item--first': true,
            'pagination__list-item--disabled': !(prevPath && firstPath),
          })}
          aria-label="Goto First Page"
        >
          <Link to={firstPath} disabled={!(prevPath && firstPath)}>
            &larr; First
          </Link>
        </li>
        <li
          className={clsx({
            'pagination__list-item': true,
            'pagination__list-item--prev': true,
            'pagination__list-item--disabled': !prevPath,
          })}
          aria-label="Goto Previous Page"
        >
          <Link to={prevPath || firstPath} disabled={!prevPath}>
            &laquo; Prev
          </Link>
        </li>
        {hasPreEllipsis && (
          <li className="pagination__list-item pagination__list-item--pre-ellipsis">
            &hellip;
          </li>
        )}
        {pages.map((page) => {
          const pageNumber = page;
          const isActive = current === pageNumber;
          const isLast = pageNumber === total;
          const pagePath = isLast
            ? lastPath
            : resolvePath([basePath, pageNumber]);
          return (
            <li
              key={page}
              className={clsx({
                'pagination__list-item': true,
                [`pagination__list-item--${pageNumber}`]: true,
                'pagination__list-item--active': isActive,
              })}
              aria-current={isActive}
              aria-label={`Goto Page ${pageNumber}`}
            >
              <Link to={pagePath}>{pageNumber}</Link>
            </li>
          );
        })}
        {hasPostEllipsis && (
          <li className="pagination__list-item pagination__list-item--post-ellipsis">
            &hellip;
          </li>
        )}
        <li
          className={clsx({
            'pagination__list-item': true,
            'pagination__list-item--next': true,
            'pagination__list-item--disabled': !nextPath,
          })}
          aria-label="Goto Next Page"
        >
          <Link to={nextPath || lastPath} disabled={!nextPath}>
            Next &raquo;
          </Link>
        </li>
        <li
          className={clsx({
            'pagination__list-item': true,
            'pagination__list-item--last': true,
            'pagination__list-item--disabled': !(nextPath && lastPath),
          })}
          aria-label="Goto Last Page"
        >
          <Link to={lastPath} disabled={!(nextPath && lastPath)}>
            Last &rarr;
          </Link>
        </li>
      </ul>
    </nav>
  );
};
