import { ACTION_TYPES } from './index';

const DEFAULT_STATE = {
	overriddenBlocks: [],
	unregisteredBlocks: [],
	blockIdByName: {},
	editorBlockIdByName: {},
	blockIdPrefix: null,
};

export default function reducer( state = DEFAULT_STATE, action ) {
	switch ( action.type ) {
		case ACTION_TYPES.ADD_OVERRIDDEN_BLOCK:
			return {
				...state,
				overriddenBlocks: [ ...state.overriddenBlocks, action.block ],
			};
		case ACTION_TYPES.ADD_UNREGISTERED_BLOCK:
			return {
				...state,
				unregisteredBlocks: [
					...state.unregisteredBlocks,
					action.block,
				],
			};
		case ACTION_TYPES.INC_BLOCK_ID:
			return {
				...state,
				blockIdByName: {
					...state.blockIdByName,
					[ action.block ]:
						( state.blockIdByName[ action.block ] ?? 0 ) + 1,
				},
			};
		case ACTION_TYPES.INC_EDITOR_BLOCK_ID:
			return {
				...state,
				editorBlockIdByName: {
					...state.editorBlockIdByName,
					[ action.block ]:
						( state.editorBlockIdByName[ action.block ] ?? 0 ) + 1,
				},
			};
		case ACTION_TYPES.RESET_EDITOR_BLOCK_ID:
			return {
				...state,
				editorBlockIdByName: {},
			};
		case ACTION_TYPES.SET_BLOCK_ID_PREFIX:
			return {
				...state,
				blockIdPrefix: action.prefix,
			};
	}

	return state;
}
