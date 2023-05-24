import { Link } from 'gatsby';
import { get } from 'lodash';
import React from 'react';
import { useSearchContext } from './hooks/useSearchContext';

export const SearchResults = () => {
  const { searchResults } = useSearchContext();

  if (!searchResults) {
    return null;
  }

  return (
    <div className="search__results">
      <ul className="search__results-list">
        {searchResults.length ? (
          searchResults.map((result) => (
            <li key={get(result, 'objectID')} className="search__single-result">
              <Link to={get(result, 'url')}>{get(result, 'title')}</Link>
            </li>
          ))
        ) : (
          <li className="search__single-result">
            <span>No results</span>
          </li>
        )}
      </ul>
    </div>
  );
};
