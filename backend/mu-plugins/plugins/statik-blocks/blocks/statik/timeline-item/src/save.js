import { InnerBlocks, useBlockProps, RichText } from '@wordpress/block-editor';
import clsx from 'clsx';

export default function TimelineItemSave( props ) {
	const { attributes } = props;
	const { blockId, label, labelAlign } = attributes;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			'wp-block-statik-timeline-item--ltr':
				labelAlign === 'left' || labelAlign === undefined,
			'wp-block-statik-timeline-item--rtl': labelAlign === 'right',
		} ),
	} );

	const labelId = `wp-block-${ blockId }-label`;
	const contentId = `wp-block-${ blockId }-content`;

	return (
		<div { ...blockProps }>
			<div
				className="wp-block-statik-timeline-item__label"
				tabIndex={ 0 }
				id={ labelId }
				aria-controls={ contentId }
			>
				<RichText.Content
					tagName={ 'p' }
					value={ label }
					className="wp-block-statik-accordion-item__title"
				/>
			</div>
			<div className="wp-block-statik-timeline-item__bar">
				<div className="wp-block-statik-timeline-item__indicator" />
				<div className="wp-block-statik-timeline-item__dot" />
			</div>
			<div
				className="wp-block-statik-timeline-item__content"
				role="region"
				id={ contentId }
				aria-labelledby={ labelId }
			>
				<div className="wp-block-statik-timeline-item__inner-blocks">
					<InnerBlocks.Content />
				</div>
			</div>
		</div>
	);
}
