export function addBlockPropsAttributeDefinition( settings ) {
	if ( 'undefined' !== typeof settings.attributes ) {
		settings.attributes = Object.assign( settings.attributes, {
			blockProps: {
				type: 'object',
				default: {},
			},
		} );
	}
	return settings;
}
