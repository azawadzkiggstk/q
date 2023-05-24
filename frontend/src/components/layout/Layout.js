import React from 'react';
import { CookieConsent } from '../commons/cookie-consent/CookieConsent';
import { Footer } from './footer/Footer';
import { Header } from './header/Header';
import { Main } from './Main';

export const Layout = (props) => {
  const { id, className, children } = props;

  return (
    <>
      <Header />
      <Main id={id} className={className}>
        {children}
      </Main>
      <Footer />
      <CookieConsent />
    </>
  );
};
