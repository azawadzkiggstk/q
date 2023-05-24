import {
	InnerBlocks,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import clsx from 'clsx';

const v2 = {
	attributes: {
		title: {
			type: 'string',
		},
		description: {
			type: 'string',
		},
		tabClassName: {
			type: 'string',
		},
		isPreSelected: {
			type: 'boolean',
			default: false,
		},
	},

	save( props ) {
		const { attributes } = props;

		const { blockId, isPreSelected } = attributes;

		const tabId = `wp-block-${ blockId }-tab`;
		const tabpanelId = `wp-block-${ blockId }-tabpanel`;

		const isTabActive = isPreSelected;

		const blockProps = useBlockProps.save( {
			className: clsx( `wp-block-${ blockId }`, {
				'wp-block-statik-tab--selected': isTabActive,
			} ),
			tabIndex: '0',
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
	},
};

const v1 = {
	attributes: {
		title: {
			type: 'string',
		},
		defaultExpanded: {
			type: 'boolean',
			default: false,
		},
	},
	save() {
		return <InnerBlocks.Content />;
	},
};

const deprecated = [ v1, v2 ];

export default deprecated;
