import { InspectorControls } from './components/ui/InspectorControls';
import { Placeholder } from './components/ui/Placeholder';
import { Map } from './components/Map';
import { useBlockProps } from '@wordpress/block-editor';
import { BlockControls } from './components/ui/BlockControls';
import { ResizableBox } from '@wordpress/components';
import { useDispatch } from '@wordpress/data';
import './editor.scss';

export default function GoogleMapEdit( props ) {
	const { attributes, setAttributes, isSelected } = props;

	const { latitude, longitude, zoom, height } = attributes;

	const blockHeight = height;

	const { toggleSelection } = useDispatch( 'core/block-editor' );

	const blockProps = useBlockProps( {
		style: {
			height: blockHeight,
		},
	} );

	const handleResizeStart = () => {
		toggleSelection( false );
	};

	const handleResizeStop = ( event, direction, elt ) => {
		setAttributes( {
			height: `${ elt.clientHeight }px`,
		} );
		toggleSelection( true );
	};

	// eslint-disable-next-line eqeqeq
	if ( ! ( latitude != null && longitude != null && zoom != null ) ) {
		return <Placeholder />;
	}

	return (
		<>
			<BlockControls />
			<InspectorControls />
			<div { ...blockProps }>
				<ResizableBox
					className="wp-block-statik-google-map__resizable-box"
					size={ {
						height,
					} }
					enable={ {
						top: false,
						right: false,
						bottom: true,
						left: false,
					} }
					lockAspectRatio={ false }
					onResizeStart={ handleResizeStart }
					onResizeStop={ handleResizeStop }
					showHandle={ isSelected }
				>
					<Map />
				</ResizableBox>
			</div>
		</>
	);
}
