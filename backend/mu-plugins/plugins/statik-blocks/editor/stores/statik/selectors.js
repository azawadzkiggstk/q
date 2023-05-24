export function getOverriddenBlocks( state ) {
	const { overriddenBlocks } = state;
	return overriddenBlocks;
}

export function getUnregisteredBlocks( state ) {
	const { unregisteredBlocks } = state;
	return unregisteredBlocks;
}

export function getBlockIdByName( state, block ) {
	const { blockIdByName } = state;
	return blockIdByName[ block ] ?? 0;
}

export function getEditorBlockIdByName( state, block ) {
	const { editorBlockIdByName } = state;
	return editorBlockIdByName[ block ] ?? 0;
}

export function getBlockIdPrefix( state ) {
	const { blockIdPrefix } = state;
	return blockIdPrefix;
}
