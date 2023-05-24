import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';
import clsx from 'clsx';

export default function TabSave( props ) {
	const { attributes } = props;

	const { blockId, isPreSelected } = attributes;

	const tabId = `wp-block-${ blockId }-tab`;
	const tabpanelId = `wp-block-${ blockId }-tabpanel`;

	const isTabActive = isPreSelected;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			'wp-block-statik-tab--selected': isTabActive,
		} ),
		role: 'tabpanel',
		id: tabpanelId,
		'aria-labelledby': tabId,
	} );

	const innerBlocksProps = useInnerBlocksProps.save( {
		className: 'wp-block-statik-tab__inner-blocks',
	} );

	return (
		<div { ...blockProps }>
			<div { ...innerBlocksProps } />
		</div>
	);
}
