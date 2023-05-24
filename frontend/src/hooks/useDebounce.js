import { useCallback, useEffect } from 'react';

export const useDebounce = (effect = () => {}, delay = 100, deps = []) => {
  const callback = useCallback(effect, deps); // eslint-disable-line react-hooks/exhaustive-deps

  useEffect(() => {
    const handler = setTimeout(() => {
      callback();
    }, delay);

    return () => {
      clearTimeout(handler);
    };
  }, [callback, delay]);
};
