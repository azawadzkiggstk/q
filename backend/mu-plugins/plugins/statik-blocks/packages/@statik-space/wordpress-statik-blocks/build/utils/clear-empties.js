import { isEmpty } from 'lodash';
export function clearEmpties(o) {
  for (const k in o) {
    if (isEmpty(o[k])) {
      delete o[k];
    }
  }
}