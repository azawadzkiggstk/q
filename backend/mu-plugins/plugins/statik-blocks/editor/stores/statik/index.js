import { createReduxStore, register } from '@wordpress/data';

import * as selectors from './selectors';
import * as actions from './actions';
import * as resolvers from './resolvers';
import reducer from './reducer';
import controls from './controls';

export const STORE_NAME = 'statik';

export const ACTION_TYPES = {
	ADD_OVERRIDDEN_BLOCK: 'ADD_OVERRIDDEN_BLOCK',
	ADD_UNREGISTERED_BLOCK: 'ADD_UNREGISTERED_BLOCK',
	SET_BLOCK_ID_PREFIX: 'SET_BLOCK_ID_PREFIX',
	INC_BLOCK_ID: 'INC_BLOCK_ID',
	INC_EDITOR_BLOCK_ID: 'INC_EDITOR_BLOCK_ID',
	RESET_EDITOR_BLOCK_ID: 'RESET_EDITOR_BLOCK_ID',
};

export function registerStatikStore() {
	const store = createReduxStore( STORE_NAME, {
		selectors,
		actions,
		resolvers,
		reducer,
		controls,
	} );
	register( store );
}
