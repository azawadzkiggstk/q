import { InnerBlocks } from '@wordpress/block-editor';

export function resolveSave( save, fallback = <InnerBlocks.Content /> ) {
	return function ( props ) {
		if ( isSaveExecutionEnabled() ) {
			return save( props );
		}
		return fallback;
	};
}

function isSaveExecutionEnabled() {
	if ( typeof statikBlocks !== 'undefined' ) {
		return Boolean( statikBlocks?.config?.saveJsExecution );
	}
	throw new Error( '`statikBlocks` variable does not exist' );
}
