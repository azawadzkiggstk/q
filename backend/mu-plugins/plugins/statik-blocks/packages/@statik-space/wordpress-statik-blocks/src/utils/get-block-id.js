import { simpleHash } from './simple-hash';
import { dispatch, select } from '@wordpress/data';

export function getBlockId( name, clientId ) {
	const prefix = getBlockIdPrefix();
	if ( clientId ) {
		const block = select( 'core/block-editor' ).getBlock( clientId );
		if ( block ) {
			return simpleHash(
				`${ prefix }-${ name }-${ createEditorBlockId( name ) }`
			);
		}
	}

	return simpleHash( `${ prefix }-${ name }-${ createBlockId( name ) }` );
}

function getBlockIdPrefix() {
	return (
		select( 'statik' ).getBlockIdPrefix() ??
		select( 'core/editor' ).getCurrentPostId()
	);
}

function createEditorBlockId( blockName ) {
	dispatch( 'statik' ).incrementEditorBlockId( blockName );
	return select( 'statik' ).getEditorBlockIdByName( blockName );
}

function createBlockId( blockName ) {
	dispatch( 'statik' ).incrementBlockId( blockName );
	return select( 'statik' ).getBlockIdByName( blockName );
}
