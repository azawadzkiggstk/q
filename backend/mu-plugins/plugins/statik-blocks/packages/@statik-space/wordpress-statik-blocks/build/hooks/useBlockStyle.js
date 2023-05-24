import { useBlockProps } from '@wordpress/block-editor';
export function useBlockStyle(value) {
  let options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  const blockProps = useBlockProps();
  const {
    before = '',
    after = ''
  } = options;
  const content = `
		${before}
		#${blockProps.id} {
			${value}
		}
		${after}
	`;
  return {
    type: 'text/css',
    dangerouslySetInnerHTML: {
      __html: content
    }
  };
}