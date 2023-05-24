import React from 'react';

export const Main = (props) => {
  const { id, className, children } = props;

  return (
    <main id={id} className={className}>
      {children}
    </main>
  );
};
