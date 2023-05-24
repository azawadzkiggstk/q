import { useCallback, useRef } from 'react';

const defaultClassNames = {
  headerAttached: 'header--attached',
  headerDetached: 'header--detached',
  headerSticky: 'header--sticky',
  headerUnsticky: 'header--unsticky',
};

export const useStickyMenu = (offsetY = 0, customClasses = {}) => {
  const ref = useRef(null);
  const currentScrollPosition = useRef(0);
  const headerHeight = useRef(0);
  const previousScrollPosition = useRef(0);
  const SCROLL_THRESHOLD = useRef(0);

  const classNames = Object.assign({}, defaultClassNames, customClasses);

  const handleScroll = useCallback(
    (headerEl, headerHeight, THRESHOLD) => {
      const headerClasslist = headerEl.classList;
      const windowYOffset = window.pageYOffset;

      if (
        windowYOffset >= THRESHOLD &&
        !headerClasslist.contains(classNames.headerDetached)
      ) {
        requestAnimationFrame(() => {
          headerClasslist.remove(classNames.headerAttached);
          headerClasslist.add(classNames.headerDetached);
        });
      } else if (
        windowYOffset < previousScrollPosition.current &&
        windowYOffset <= THRESHOLD - headerHeight
      ) {
        requestAnimationFrame(() => {
          headerClasslist.remove(classNames.headerDetached);
          headerClasslist.remove(classNames.headerSticky);
          headerClasslist.add(classNames.headerAttached);
        });
      } else if (
        windowYOffset < previousScrollPosition.current &&
        windowYOffset > THRESHOLD
      ) {
        requestAnimationFrame(() => {
          headerClasslist.remove(classNames.headerUnsticky);
          headerClasslist.add(classNames.headerSticky);
          headerClasslist.add(classNames.headerDetached);
        });
      } else if (
        windowYOffset > previousScrollPosition.current &&
        headerClasslist.contains(classNames.headerSticky)
      ) {
        requestAnimationFrame(() => {
          headerClasslist.add(classNames.headerUnsticky);
          headerClasslist.remove(classNames.headerSticky);
        });
      }
      previousScrollPosition.current = window.pageYOffset;
    },
    [
      classNames.headerAttached,
      classNames.headerDetached,
      classNames.headerSticky,
      classNames.headerUnsticky,
    ],
  );

  const setHeaderRef = useCallback(
    (headerEl) => {
      if (ref.current) {
        window.removeEventListener(
          'scroll',
          handleScroll.bind(
            null,
            headerEl,
            headerHeight.current,
            SCROLL_THRESHOLD.current,
          ),
          false,
        );
      }

      headerHeight.current = headerEl.offsetHeight;
      currentScrollPosition.current = window.pageYOffset;
      SCROLL_THRESHOLD.current = headerHeight.current + offsetY;
      if (window.pageYOffset > SCROLL_THRESHOLD.current) {
        headerEl.classList.remove(classNames.headerAttached);
        headerEl.classList.add(classNames.headerDetached);
      } else {
        headerEl.classList.add(classNames.headerAttached);
        headerEl.classList.remove(classNames.headerDetached);
      }
      window.addEventListener(
        'scroll',
        handleScroll.bind(
          null,
          headerEl,
          headerHeight.current,
          SCROLL_THRESHOLD.current,
        ),
        false,
      );

      ref.current = headerEl;
    },
    [
      classNames.headerAttached,
      classNames.headerDetached,
      handleScroll,
      offsetY,
    ],
  );

  return [setHeaderRef];
};
