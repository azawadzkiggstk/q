import React from 'react';
import { useCookieConsent } from './hooks/useCookieConsent';
import { useIsMounted } from '../../../hooks/useIsMounted';

export const CookieConsent = () => {
  const {
    cookieMessage,
    acceptMessage,
    rejectMessage,
    isAccepted,
    isRejected,
    accept,
    reject,
  } = useCookieConsent();

  const isMounted = useIsMounted();

  if (!isMounted || isAccepted || isRejected) {
    return null;
  }

  return (
    <section className="cookie-consent">
      <div className="cookie-consent__container">
        <div
          className="cookie-consent__message"
          dangerouslySetInnerHTML={{ __html: cookieMessage }}
        />
        <button
          className="cookie-consent__button cookie-consent__button--reject"
          onClick={reject}
        >
          {rejectMessage}
        </button>
        <button
          className="cookie-consent__button cookie-consent__button--accept"
          onClick={accept}
        >
          {acceptMessage}
        </button>
      </div>
    </section>
  );
};
