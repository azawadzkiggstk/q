import { HEIGHT_BREAKPOINTS, MAX_SPACER_HEIGHT } from '../consts';

export const getRoundedHeight = ( currentHeight, previuosHeight ) => {
	const delta = currentHeight - previuosHeight;

	let spacerHeight = currentHeight;
	if ( ! HEIGHT_BREAKPOINTS.includes( currentHeight ) ) {
		if ( delta > 0 ) {
			const nextDecValue = Math.ceil( currentHeight / 10.0 ) * 10;
			spacerHeight =
				HEIGHT_BREAKPOINTS.find(
					( val ) => val > currentHeight && val < nextDecValue
				) || nextDecValue;
		} else if ( delta < 0 ) {
			const prevDecValue = Math.floor( currentHeight / 10.0 ) * 10;
			spacerHeight =
				HEIGHT_BREAKPOINTS.find(
					( val ) => val < currentHeight && val > prevDecValue
				) || prevDecValue;
		}
	}

	spacerHeight = Math.min( parseInt( spacerHeight, 10 ), MAX_SPACER_HEIGHT );

	return spacerHeight;
};
