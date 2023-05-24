import React from 'react';
import clsx from 'clsx';
import { Link } from 'gatsby';
import { useAllCategories } from '../../hooks/useAllCategories';
import { useStatikPageContext } from '@statik-space/gatsby-theme-statik';
import { getPath } from '../../utils/get-path';

export const CategoriesNav = () => {
  const categories = useAllCategories();
  const { pageContext } = useStatikPageContext();
  const { basePath, category } = pageContext;

  const isAllActive = !category;

  return (
    <nav
      className="categories-nav"
      role="navigation"
      aria-label="Categories Navigation"
    >
      <ul className="categories-nav__list">
        <li
          className={clsx({
            'categories-nav__list-item': true,
            'categories-nav__list-item--all': true,
            'categories-nav__list-item--active': isAllActive,
          })}
          role="none"
        >
          <Link
            to={'/insights/'}
            role="menuitem"
            aria-current={isAllActive}
            aria-label="Goto All Categories Page"
          >
            All
          </Link>
        </li>
        {categories.map((category) => {
          const isActive = getPath([basePath]) === getPath([category.uri]);
          return (
            <li
              key={category.uri}
              className={clsx({
                'categories-nav__list-item': true,
                [`categories-nav__list-item--${category.slug}`]: true,
                'categories-nav__list-item--active': isActive,
              })}
              role="none"
            >
              <Link
                to={category.uri}
                role="menuitem"
                aria-current={isActive}
                aria-label={`Goto ${category.name} Category Page`}
              >
                {category.name}
              </Link>
            </li>

          );
        })}
      </ul>
    </nav>
  );
};
