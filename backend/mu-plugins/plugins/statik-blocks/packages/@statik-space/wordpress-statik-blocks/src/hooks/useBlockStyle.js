import { useBlockProps } from '@wordpress/block-editor';

export function useBlockStyle( value, options = {} ) {
	const blockProps = useBlockProps();

	const { before = '', after = '' } = options;

	const content = `
		${ before }
		#${ blockProps.id } {
			${ value }
		}
		${ after }
	`;

	return {
		type: 'text/css',
		dangerouslySetInnerHTML: {
			__html: content,
		},
	};
}
