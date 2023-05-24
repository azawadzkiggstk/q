import React, { createContext, useState } from 'react';
import { useDebounce } from '../../../../hooks/useDebounce';
import { useSearch } from '../hooks/useSearch';

export const SearchContext = createContext({});

export const SearchProvider = (props) => {
  const { children } = props;
  const [searchResults, setSearchResults] = useState(undefined);
  const [searchPhrase, setSearchPhrase] = useState('');
  const { fetchSearchResults } = useSearch(searchPhrase);

  useDebounce(
    async () => {
      setSearchResults(await fetchSearchResults());
    },
    500,
    [searchPhrase],
  );

  return (
    <SearchContext.Provider
      value={{ searchResults, searchPhrase, setSearchPhrase }}
    >
      {children}
    </SearchContext.Provider>
  );
};
