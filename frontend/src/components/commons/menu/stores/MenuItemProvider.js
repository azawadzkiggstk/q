import React, { useState } from 'react';
import { MenuItemContext } from './MenuItemContext';

export const MenuItemProvider = (props) => {
  const { item, level, children } = props;

  const [hasOpenState, setOpenState] = useState(false);
  const [hasHoverState, setHoverState] = useState(false);
  const [hasHoverChildState, setHoverChildState] = useState(false);

  const contextValue = {
    item,
    level,
    hasOpenState,
    hasHoverState,
    hasHoverChildState,
    setOpenState,
    setHoverState,
    setHoverChildState,
  };

  return (
    <MenuItemContext.Provider value={contextValue}>
      {children}
    </MenuItemContext.Provider>
  );
};
