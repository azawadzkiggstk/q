export function addBlockIdAttributeDefinition( settings ) {
	const isStatikBlock = settings.name.startsWith( 'statik/' );
	const hasAttributes = 'undefined' !== typeof settings.attributes;

	if ( isStatikBlock && hasAttributes ) {
		settings.attributes = Object.assign( settings.attributes, {
			blockId: {
				type: 'string',
			},
		} );
	}
	return settings;
}
