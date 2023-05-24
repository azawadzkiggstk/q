import { createHigherOrderComponent } from '@wordpress/compose';
import { useSetBlockAtts } from '@statik-space/wordpress-statik-blocks';

export const withBlockAttsAttribute = createHigherOrderComponent(
	( BlockEdit ) => {
		return ( props ) => {
			useSetBlockAtts();
			return <BlockEdit { ...props } />;
		};
	},
	'withBlockAttsAttribute'
);
