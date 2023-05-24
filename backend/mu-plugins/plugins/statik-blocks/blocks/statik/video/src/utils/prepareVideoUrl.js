import { parseYouTubeUrl } from './parseYouTubeUrl';
import { parseVimeoUrl } from './parseVimeoUrl';

export const prepareVideoUrl = ( videoUrl ) => {
	if ( ! videoUrl ) {
		return null;
	}
	return parseYouTubeUrl( videoUrl ) ?? parseVimeoUrl( videoUrl );
};
