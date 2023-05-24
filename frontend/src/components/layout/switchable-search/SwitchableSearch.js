import React from 'react';
import { Search } from '../../commons/search/Search';
import { SearchInput } from '../../commons/search/SearchInput';
import { useSwitchableSearch } from './hooks/useSwitchableSearch';
import { SearchIcon } from './icons/SearchIcon';

export const SwitchableSearch = () => {
  const { isSearchOpened, switchSearch } = useSwitchableSearch();

  const SearchInputWithPlaceholder = () => {
    return <SearchInput placeholder="Search..." />;
  };

  return (
    <div className="search search--switchable">
      <button onClick={switchSearch} className="search__button">
        <SearchIcon width="16px" height="16px" />
      </button>
      {isSearchOpened && (
        <div className="search__container">
          <Search inputConstructor={SearchInputWithPlaceholder} />
        </div>
      )}
    </div>
  );
};
