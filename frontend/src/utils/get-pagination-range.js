import { range } from 'lodash';

export function getPaginationRange(startLimit, endLimit, current, radius) {
  const desiredScope = radius * 2 + 1;
  let scopeStart = Math.max(current - radius, startLimit);
  let scopeEnd = Math.min(current + radius, endLimit);
  const currentScope = scopeEnd - scopeStart + 1;

  if (currentScope < desiredScope) {
    const scopeDiff = desiredScope - currentScope;
    if (scopeStart === startLimit) {
      scopeEnd = Math.min(scopeEnd + scopeDiff, endLimit);
    } else if (scopeEnd === endLimit) {
      scopeEnd = Math.max(scopeStart - scopeDiff, startLimit);
    }
  }

  return range(scopeStart, scopeEnd + 1, 1);
}
