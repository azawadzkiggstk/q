import { getBlockTypes, unregisterBlockType } from '@wordpress/blocks';
import { dispatch, select } from '@wordpress/data';
import domReady from '@wordpress/dom-ready';

export function unregisterNotOverriddenBlocks() {
	domReady( () => {
		const overriddenBlocks = select( 'statik' ).getOverriddenBlocks();

		const allowedBlocks = [ 'core/paragraph', ...overriddenBlocks ];

		getBlockTypes().forEach( function ( blockType ) {
			const isCoreBlock = blockType.name.startsWith( 'core/' );
			const isAllowedBlock = allowedBlocks.includes( blockType.name );
			if ( isCoreBlock && ! isAllowedBlock ) {
				unregisterBlockType( blockType.name );
				dispatch( 'statik' ).addUnregisteredBlock( blockType.name );
			}
		} );
	} );
}
