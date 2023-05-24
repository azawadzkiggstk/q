import { InspectorControls } from './components/ui/InspectorControls';
import { useBlockProps } from '@wordpress/block-editor';
import { PostTypeTable } from './components/PostTypeTable';
import { Placeholder } from './components/ui/Placeholder';
import { BlockControls } from './components/ui/BlockControls';
import clsx from 'clsx';
import './editor.scss';

export default function PostTypeTableEdit( props ) {
	const { attributes } = props;

	const { postType } = attributes;

	const blockProps = useBlockProps( {
		className: clsx( {
			[ `wp-block-statik-post-type-table--${ postType }` ]: !! postType,
		} ),
	} );

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				{ postType && <PostTypeTable /> }
				{ ! postType && <Placeholder /> }
			</div>
		</>
	);
}
