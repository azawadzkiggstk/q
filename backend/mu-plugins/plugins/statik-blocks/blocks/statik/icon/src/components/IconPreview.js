import {
	useBlockAttributes,
	useMedia,
} from '@statik-space/wordpress-statik-blocks';
import { Iconoir } from './Iconoir';

export const IconPreview = () => {
	const { attributes } = useBlockAttributes();
	const { icon, iconUrl, iconSize, id } = attributes;
	const iconData = useMedia( id );

	let IconComponent;
	if ( iconUrl ) {
		IconComponent = (
			<img
				src={ iconUrl }
				alt={ iconData?.alt_text }
				width={ iconSize }
				height={ iconSize }
			/>
		);
	} else if ( icon ) {
		return <Iconoir icon={ icon } />;
	}

	return IconComponent;
};
