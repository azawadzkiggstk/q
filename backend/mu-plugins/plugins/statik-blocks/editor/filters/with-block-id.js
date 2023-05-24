import { createHigherOrderComponent } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { select } from '@wordpress/data';
import {
	hasBlockIdAttributeDefinition,
	useBlockId,
} from '@statik-space/wordpress-statik-blocks';
import { replaceBlockIdClassName } from '../utils/replace-block-id-class-name';

export const withBlockId = createHigherOrderComponent( ( BlockEdit ) => {
	return ( props ) => {
		const { attributes, setAttributes, clientId, name } = props;
		const { blockId, className } = attributes;

		const id = useBlockId( name, clientId );

		const setBlockId = ( newBlockId ) => {
			setAttributes( {
				className: replaceBlockIdClassName(
					className,
					blockId,
					newBlockId
				),
				blockId: newBlockId,
			} );
		};

		useEffect( () => {
			if ( ! hasBlockIdAttributeDefinition( name ) ) {
				return;
			}

			if ( ! blockId ) {
				setBlockId( id );
				return;
			}

			if ( isBlockIdReserved( name, blockId, clientId ) ) {
				console.log(
					`Block with type: '${ name }' and id: '${ blockId }' already exists. Regenerating id...`
				);
				setBlockId( id );
			}

			// fallback to update the `className` attribute
			setBlockId( blockId );
			// eslint-disable-next-line react-hooks/exhaustive-deps
		}, [] );

		return <BlockEdit { ...props } />;
	};
}, 'withBlockId' );

const isBlockIdReserved = ( blockName, blockId, clientId ) => {
	const blocksClientIds =
		select( 'core/block-editor' ).getClientIdsWithDescendants();
	return blocksClientIds.some( ( _clientId ) => {
		const _blockName =
			select( 'core/block-editor' ).getBlockName( _clientId );
		if ( blockName !== _blockName ) {
			return false;
		}
		const { blockId: _blockId } =
			select( 'core/block-editor' ).getBlockAttributes( _clientId );
		return clientId !== _clientId && blockId === _blockId;
	} );
};
