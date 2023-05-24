import { useState } from 'react';

export const useSwitchableSearch = () => {
  const [isSearchOpened, setSearchOpened] = useState(false);
  const switchSearch = () => setSearchOpened(!isSearchOpened);

  return { isSearchOpened, switchSearch, setSearchOpened };
};
