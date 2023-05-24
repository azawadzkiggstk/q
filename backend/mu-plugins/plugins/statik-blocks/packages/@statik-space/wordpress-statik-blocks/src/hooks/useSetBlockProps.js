import { useEffect } from '@wordpress/element';
import { getBlockType } from '@wordpress/blocks';
import { useBlockEditContext } from '@wordpress/block-editor';
import { applyFilters } from '@wordpress/hooks';
import { useBlockAttributes } from './useBlockAttributes';
import { clearEmpties } from '../utils';
import { isEmpty } from 'lodash';

const BLOCK_PROPS_KEY = 'blockProps';
const BLOCK_ATTS_KEY = 'blockAtts';

export function useSetBlockProps() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { name } = useBlockEditContext();

	const attributesValues = Object.entries( attributes )
		.filter(
			( [ attName ] ) =>
				attName !== BLOCK_PROPS_KEY && attName !== BLOCK_ATTS_KEY
		)
		.sort( ( [ attNameA ], [ attNameB ] ) =>
			attNameA.localeCompare( attNameB )
		)
		.map( ( [ , attValue ] ) => attValue );

	useEffect( () => {
		const blockProps = applyFilters(
			'blocks.getSaveContent.extraProps',
			{},
			getBlockType( name ),
			attributes
		);

		clearEmpties( blockProps );

		if ( ! isEmpty( blockProps ) ) {
			setAttributes( {
				blockProps,
			} );
		} else {
			setAttributes( {
				blockProps: undefined,
			} );
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ JSON.stringify( attributesValues ) ] );
}
