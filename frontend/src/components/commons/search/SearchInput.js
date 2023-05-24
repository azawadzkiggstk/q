import React from 'react';
import { useSearchContext } from './hooks/useSearchContext';

export const SearchInput = (props) => {
  const { searchPhrase, setSearchPhrase } = useSearchContext();

  const onInputChange = (event) => {
    setSearchPhrase(event.target.value);
  };

  return (
    <input
      autoFocus={true} // eslint-disable-line jsx-a11y/no-autofocus
      value={searchPhrase}
      onChange={onInputChange}
      className="search__input"
      {...props}
    />
  );
};
