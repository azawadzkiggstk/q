import { createHigherOrderComponent } from '@wordpress/compose';
import { useSetBlockProps } from '@statik-space/wordpress-statik-blocks';

export const withBlockPropsAttribute = createHigherOrderComponent(
	( BlockEdit ) => {
		return ( props ) => {
			useSetBlockProps();
			return <BlockEdit { ...props } />;
		};
	},
	'withBlockPropsAttribute'
);
