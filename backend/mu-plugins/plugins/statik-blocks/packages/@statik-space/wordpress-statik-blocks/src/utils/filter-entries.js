import { isObjectLike, isArrayLike, isFunction } from 'lodash';

export function filterEntries( object, fn ) {
	if ( ! ( isObjectLike( object ) && ! isArrayLike( object ) ) ) {
		throw new Error( 'The passed value is not a valid object' );
	}
	if ( ! isFunction( fn ) ) {
		throw new Error( 'The passed value is not a valid function' );
	}
	return Object.fromEntries( Object.entries( object ).filter( fn ) );
}
