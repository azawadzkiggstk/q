import { ACTION_TYPES } from './index';

export function addOverriddenBlock( block ) {
	return {
		type: ACTION_TYPES.ADD_OVERRIDDEN_BLOCK,
		block,
	};
}

export function addUnregisteredBlock( block ) {
	return {
		type: ACTION_TYPES.ADD_UNREGISTERED_BLOCK,
		block,
	};
}

export function incrementBlockId( block ) {
	return {
		type: ACTION_TYPES.INC_BLOCK_ID,
		block,
	};
}

export function incrementEditorBlockId( block ) {
	return {
		type: ACTION_TYPES.INC_EDITOR_BLOCK_ID,
		block,
	};
}

export function resetEditorBlockId() {
	return {
		type: ACTION_TYPES.RESET_EDITOR_BLOCK_ID,
	};
}

export function setBlockIdPrefix( prefix ) {
	return {
		type: ACTION_TYPES.SET_BLOCK_ID_PREFIX,
		prefix,
	};
}
