import { useContext } from 'react';
import { SearchContext } from '../context/SearchContext';

export const useSearchContext = () => {
  const context = useContext(SearchContext);

  if (context === undefined) {
    throw new Error('useSearchContext was used outside of its SearchProvider.');
  }

  return context;
};
