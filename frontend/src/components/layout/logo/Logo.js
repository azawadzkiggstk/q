import React from 'react';
import { StatikImage } from '@statik-space/gatsby-theme-statik';
import { DefaultLogo } from './icons/DefaultLogo';

export const Logo = (props) => {
  const { mediaItem, ...restProps } = props;

  if (!mediaItem) {
    return <DefaultLogo {...restProps} />;
  }

  return <StatikImage mediaItem={mediaItem} {...restProps} />;
};
