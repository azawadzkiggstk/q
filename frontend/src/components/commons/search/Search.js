import React from 'react';
import { SearchProvider } from './context/SearchContext';
import { SearchInput } from './SearchInput';
import { SearchResults } from './SearchResults';

export const Search = (props) => {
  const {
    inputConstructor = () => <SearchInput />,
    resultsConstructor = () => <SearchResults />,
  } = props;

  return (
    <SearchProvider>
      {inputConstructor()}
      {resultsConstructor()}
    </SearchProvider>
  );
};
