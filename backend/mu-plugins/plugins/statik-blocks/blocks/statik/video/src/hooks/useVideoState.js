import { useBlockAttributes } from '@statik-space/wordpress-statik-blocks';
import { useDispatch } from '@wordpress/data';
import { prepareVideoUrl } from '../utils/prepareVideoUrl';
import { useRef } from '@wordpress/element';
import { mediaPosition } from '../utils/mediaPosition';

export const useVideoState = () => {
	const { attributes, setAttributes } = useBlockAttributes();

	const {
		url,
		width,
		height,
		overlayColor,
		overlayColorOpacity,
		overlayImage,
		overlayFocalPoint,
		overlayHasParallax,
		overlayIsRepeated,
	} = attributes;

	const { toggleSelection } = useDispatch( 'core/block-editor' );

	const boxRef = useRef();
	const bgImageRef = useRef();

	const handleResizeStart = () => {
		toggleSelection( false );
	};

	const handleResizeStop = ( event, direction, elt, delta ) => {
		let _width = width;
		let _height = height;

		if ( delta.width !== 0 ) {
			_width = `${ parseInt( boxRef.current.size.width ) }px`;
		}
		if ( delta.height !== 0 ) {
			_height = `${ parseInt( boxRef.current.size.height ) }px`;
		}

		setAttributes( {
			width: _width,
			height: _height,
		} );

		toggleSelection( true );
	};

	const videoUrl = prepareVideoUrl( url );

	const blockStyle = {
		height,
	};

	const bgStyle = {
		backgroundColor: overlayColor,
		opacity: overlayColorOpacity / 100,
		width,
		height,
	};

	const bgImageStyle = {
		width,
		height,
		backgroundImage: overlayImage && `url(${ overlayImage })`,
		backgroundSize: overlayIsRepeated ? 'auto' : 'cover',
		backgroundRepeat: overlayIsRepeated ? 'repeat' : 'no-repeat',
		backgroundAttachment: overlayHasParallax ? 'fixed' : 'scroll',
		backgroundPosition:
			overlayImage && overlayFocalPoint
				? mediaPosition( overlayFocalPoint )
				: undefined,
	};

	const iframeStyle = {
		width,
		height,
		pointerEvents: 'none',
	};

	return {
		boxRef,
		bgImageRef,
		boxWidth: width,
		boxHeight: height,
		videoUrl,
		handleResizeStart,
		handleResizeStop,
		blockStyle,
		iframeStyle,
		bgStyle,
		bgImageStyle,
	};
};
