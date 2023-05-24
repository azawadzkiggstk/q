const youTubeRegexp =
	/^(?:https?:\/\/)?(?:m\.|www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;

export const parseYouTubeUrl = ( url ) => {
	if ( url !== undefined || url !== '' ) {
		const match = url.match( youTubeRegexp );
		if ( match ) {
			return `https://www.youtube.com/embed/${ match[ 1 ] }?autoplay=0`;
		}
	}
	return null;
};
