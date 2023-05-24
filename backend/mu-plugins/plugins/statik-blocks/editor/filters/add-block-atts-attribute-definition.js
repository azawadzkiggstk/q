export function addBlockAttsAttributeDefinition( settings ) {
	const isStatikBlock = settings.name.startsWith( 'statik/' );
	const hasAttributes = 'undefined' !== typeof settings.attributes;

	if ( isStatikBlock && hasAttributes ) {
		settings.attributes = Object.assign( settings.attributes, {
			blockAtts: {
				type: 'object',
				default: {},
			},
		} );
	}
	return settings;
}
