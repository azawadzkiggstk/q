import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function TimelineSave() {
	const blockProps = useBlockProps.save();

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
