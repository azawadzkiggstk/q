import { useDispatch } from '@wordpress/data';
import { useState } from '@wordpress/element';
import {
	useBlockAttributes,
	useRwdAttribute,
} from '@statik-space/wordpress-statik-blocks';
import { getRoundedHeight } from '../utils/getRoundedHeight';

export const useUniversalBlockState = () => {
	const { attributes, setAttributes } = useBlockAttributes();
	const { height } = attributes;
	const rwdHeight = useRwdAttribute( height );
	const blockHeight = rwdHeight.default;

	const [ isResizing, setIsResizing ] = useState( false );
	const [ boxHeight, setBoxHeight ] = useState( blockHeight );

	const { toggleSelection } = useDispatch( 'core/editor' );

	const handleResizeStart = () => {
		setIsResizing( true );
		toggleSelection( false );
	};

	const handleResize = ( event, direction, elt ) => {
		setBoxHeight( elt.clientHeight );
		setIsResizing( true );
		toggleSelection( false );
	};

	const handleResizeStop = ( event, direction, elt, delta ) => {
		if ( delta.height !== 0 ) {
			const newHeight = getRoundedHeight( elt.clientHeight );
			rwdHeight.setDefault( `${ newHeight }px` );
			setBoxHeight( newHeight );
			setAttributes( {
				height: rwdHeight.toRwd(),
			} );
		}

		setIsResizing( false );
		toggleSelection( true );
	};

	return {
		boxHeight: isResizing ? boxHeight : blockHeight,
		isResizing,
		setIsResizing,
		handleResizeStart,
		handleResize,
		handleResizeStop,
	};
};
