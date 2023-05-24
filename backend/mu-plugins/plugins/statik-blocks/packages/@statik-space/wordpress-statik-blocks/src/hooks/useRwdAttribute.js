import { rwdAttribute } from '../utils';
import { useMemo } from '@wordpress/element';

export function useRwdAttribute( attribute ) {
	return useMemo( () => rwdAttribute( attribute ), [ attribute ] );
}
