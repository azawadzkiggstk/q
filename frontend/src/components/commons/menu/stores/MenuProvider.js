import React from 'react';
import { MenuContext } from './MenuContext';

export const MenuProvider = (props) => {
  const { interaction, menuItems, children } = props;

  const contextValue = {
    interaction,
    items: menuItems,
  };

  return (
    <MenuContext.Provider value={contextValue}>{children}</MenuContext.Provider>
  );
};
