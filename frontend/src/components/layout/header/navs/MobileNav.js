import React, { useState, useEffect } from 'react';
import clsx from 'clsx';
import { HeaderMobileMenu } from '../../../menus/HeaderMobileMenu';

export const MobileNav = () => {
  const [isMobileMenuActive, setMobileMenuActive] = useState(false);

  useEffect(() => {
    if (isMobileMenuActive) {
      document.body.style.overflow = 'hidden';
      document.body.style.top = `-${window.scrollY}px`;
    } else {
      const scrollY = document.body.style.top;
      document.body.style.overflow = '';
      document.body.style.top = '';
      window.scrollTo(0, parseInt(scrollY || '0') * -1);
    }
  }, [isMobileMenuActive]);

  return (
    <div className="main-header__mobile-nav" role="navigation">
      <button
        aria-label="Open the menu"
        type="button"
        className="main-header__mobile-menu-toggle"
        onClick={() => setMobileMenuActive(!isMobileMenuActive)}
      >
        Mobile menu
      </button>
      <div
        className={clsx({
          'main-header__mobile-menu': true,
          'main-header__mobile-menu--active': isMobileMenuActive,
        })}
      >
        <HeaderMobileMenu />
      </div>
    </div>
  );
};
