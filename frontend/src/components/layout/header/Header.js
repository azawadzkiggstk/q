import clsx from 'clsx';
import { Link } from 'gatsby';
import React from 'react';
import { Logo } from '../logo/Logo';
import { useThemeSettings } from '../../../hooks/useThemeSettings';
import useScrollPosition from '@react-hook/window-scroll';
import { DesktopNav } from './navs/DesktopNav';
import { MobileNav } from './navs/MobileNav';

export const Header = () => {
  const options = useThemeSettings();

  const scrollY = useScrollPosition(10);

  return (
    <>
      <header
        className={clsx({
          'main-header': true,
          'main-header--is-sticky': scrollY > 0,
        })}
      >
        <div className="main-header__container">
          <div className="main-header__row">
            <div className="main-header__branding">
              <Link to="/" rel="home">
                <Logo mediaItem={options.primaryLogo} />
              </Link>
            </div>
            <MobileNav />
            <DesktopNav />
          </div>
        </div>
      </header>
    </>
  );
};
