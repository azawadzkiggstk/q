const vimeoRegexp = /https?:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;

export const parseVimeoUrl = ( url ) => {
	if ( url !== undefined || url !== '' ) {
		const match = url.match( vimeoRegexp );
		if ( match ) {
			return `https://player.vimeo.com/video/${ match[ 2 ] }`;
		}
	}
	return null;
};
