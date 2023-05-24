import { useEffect } from '@wordpress/element';
import { getBlockType } from '@wordpress/blocks';
import { useBlockEditContext } from '@wordpress/block-editor';
import { isEmpty } from 'lodash';
import { useBlockAttributes } from './useBlockAttributes';
import { hasBlockIdAttributeDefinition } from '../utils';

export function useSetBlockAtts() {
	const { attributes, setAttributes } = useBlockAttributes();
	const { name } = useBlockEditContext();

	const blockType = getBlockType( name );
	const attributesDefs = blockType.attributes;

	const sourceAttributesEntries = Object.entries( attributes )
		.filter(
			( [ attName ] ) => attributesDefs[ attName ]?.source !== undefined
		)
		.filter( ( [ , attValue ] ) => attValue !== undefined )
		.sort( ( [ attNameA ], [ attNameB ] ) =>
			attNameA.localeCompare( attNameB )
		);

	const sourceAttributesValues = sourceAttributesEntries.map(
		( [ , attValue ] ) => attValue
	);

	useEffect( () => {
		if ( ! hasBlockIdAttributeDefinition( name ) ) {
			return;
		}

		const blockAtts = Object.fromEntries( sourceAttributesEntries );

		if ( ! isEmpty( blockAtts ) ) {
			setAttributes( {
				blockAtts,
			} );
		} else {
			setAttributes( {
				blockAtts: undefined,
			} );
		}

		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [ JSON.stringify( sourceAttributesValues ) ] );
}
