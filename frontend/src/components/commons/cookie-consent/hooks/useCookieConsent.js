import { useState, useCallback } from 'react';
import { useStaticQuery, graphql } from 'gatsby';
import Cookies from 'universal-cookie';
import xss from 'xss';

const DEFAULT_COOKIE_MESSAGE =
  'This site uses cookies to offer you a better browsing experience. Find out more on how we use cookies and how to disable them in the browser settings in Cookies Policy. By clicking I accept, you consent to the use of cookies unless you have disabled them in the browser settings.';
const DEFAULT_ACCEPT_MESSAGE = 'I accept';
const DEFAULT_REJECT_MESSAGE = 'I reject';
const COOKIE_NAME = 'userHasAcceptedCookies';
const COOKIE_EXPIRES_TIME = 60 * 60 * 24 * 365 * 1000;
const ACCEPT_COOKIE_VALUE = '1';
const REJECT_COOKIE_VALUE = '0';

export const useCookieConsent = () => {
  const query = useStaticQuery(
    graphql`
      query CookieConsentQuery {
        allStatikSettings {
          cookieConsentSettings {
            cookieMessage
            acceptMessage
            rejectMessage
          }
        }
      }
    `,
  );
  const {
    allStatikSettings: {
      cookieConsentSettings: { cookieMessage, acceptMessage, rejectMessage },
    },
  } = query;

  const [cookies] = useState(() => new Cookies());

  const [isAccepted, setIsAccepted] = useState(
    cookies.get(COOKIE_NAME) === ACCEPT_COOKIE_VALUE,
  );
  const [isRejected, setIsRejected] = useState(
    cookies.get(COOKIE_NAME) === REJECT_COOKIE_VALUE,
  );

  const action = useCallback(
    (value = ACCEPT_COOKIE_VALUE) =>
      () => {
        const date = new Date();
        date.setTime(date.getTime() + COOKIE_EXPIRES_TIME);

        cookies.set(COOKIE_NAME, value, {
          path: '/',
          expires: date,
        });

        if (value === ACCEPT_COOKIE_VALUE) {
          setIsAccepted(true);
        }

        if (value === REJECT_COOKIE_VALUE) {
          setIsRejected(true);
        }
      },
    [cookies],
  );

  return {
    cookieMessage: Boolean(cookieMessage)
      ? xss(cookieMessage)
      : DEFAULT_COOKIE_MESSAGE,
    acceptMessage: Boolean(acceptMessage)
      ? xss(acceptMessage)
      : DEFAULT_ACCEPT_MESSAGE,
    rejectMessage: Boolean(rejectMessage)
      ? xss(acceptMessage)
      : DEFAULT_REJECT_MESSAGE,
    isAccepted: isAccepted,
    isRejected: isRejected,
    accept: action(ACCEPT_COOKIE_VALUE),
    reject: action(REJECT_COOKIE_VALUE),
  };
};
