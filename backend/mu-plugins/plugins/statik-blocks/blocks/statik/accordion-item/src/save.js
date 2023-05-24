import {
	RichText,
	useBlockProps,
	useInnerBlocksProps,
} from '@wordpress/block-editor';
import { ChevronDown } from './components/icons/ChevronDown';
import clsx from 'clsx';

export default function AccordionItemSave( props ) {
	const { attributes } = props;

	const { blockId, defaultExpanded, title, headerSelector } = attributes;

	const isExpanded = defaultExpanded;

	const blockProps = useBlockProps.save( {
		className: clsx( {
			'wp-block-statik-accordion-item--expanded': isExpanded,
		} ),
	} );

	const innerBlocksProps = useInnerBlocksProps.save( {
		className: 'wp-block-statik-accordion-item__inner-blocks',
	} );

	const headerId = `wp-block-${ blockId }__header`;
	const bodyId = `wp-block-${ blockId }__body`;

	return (
		<div { ...blockProps }>
			{ /* eslint-disable-next-line jsx-a11y/role-supports-aria-props */ }
			<header
				id={ headerId }
				className="wp-block-statik-accordion-item__header"
				tabIndex={ 0 }
				role="heading"
				aria-expanded={ isExpanded }
				aria-disabled={ false }
				aria-controls={ bodyId }
			>
				<RichText.Content
					tagName={ headerSelector }
					value={ title }
					className="wp-block-statik-accordion-item__title"
				/>
				<ChevronDown className="wp-block-statik-accordion-item__icon" />
			</header>
			<div
				id={ bodyId }
				className="wp-block-statik-accordion-item__body"
				role="region"
				aria-labelledby={ headerId }
			>
				<div { ...innerBlocksProps } />
			</div>
		</div>
	);
}
